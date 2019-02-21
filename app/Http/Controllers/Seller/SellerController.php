<?php

namespace App\Http\Controllers\Seller;

use App\HSNCodeTaxRelation;
use App\Http\Controllers\CustomTraits\NotificationTrait;
use App\Http\Controllers\CustomTraits\PostOfficeTrait;
use App\Http\Controllers\CustomTraits\ProfileTrait;
use App\Http\Controllers\CustomTraits\UserTrait;
use App\Order;
use App\PostOffice;
use App\SellerAddress;
use App\Tax;
use App\User;
use App\WorkOrderStatusDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\VendorLicenses;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('seller');
        if(!Auth::guest()) {
            $this->user = Auth::user();
            $this->seller = $this->user->seller()->first();
            if (Session::has('role_type')) {
                $this->userRoleType = Session::get('role_type');
            }
        }

        if (Session::has('applocale')) {
            $this->language = Session::get('applocale');
            App::setLocale(Session::get('applocale'));
        }else{
            $this->language = App::getLocale();
        }
    }
    use ProfileTrait;
    use PostOfficeTrait;
    use UserTrait;
    use NotificationTrait;
    public function home(){
        $users = User::with('seller')->where('id',Auth::user()->id)->first();
        $orders = Order::where('seller_id',$users['seller']['id'])->lists('id');
        $pendingDueToVendor = WorkOrderStatusDetail::where('work_order_status_id',1)->whereIn('order_id',$orders)->with('orders')->get()->toArray();
        $pendingForVendorCancelation = WorkOrderStatusDetail::where('work_order_status_id',2)->whereIn('order_id',$orders)->with('orders')->get()->toArray();
        $pendingForCustomerCancelation = WorkOrderStatusDetail::where('work_order_status_id',3)->whereIn('order_id',$orders)->with('orders')->get()->toArray();
        $customerIssue = WorkOrderStatusDetail::where('work_order_status_id',5)->whereIn('order_id',$orders)->with('orders')->get()->toArray();
        return view('backend.seller.home')->with(compact('pendingDueToVendor','pendingForCustomerCancelation','pendingForVendorCancelation','customerIssue'));
    }

    public function viewTax(){
        $taxes = Tax::where('is_active',true)->orderBy('id')->get();
        return view('backend.seller.tax.view')->with(compact('taxes'));
    }

    public function getProfileImage(){
        if(!empty($this->user->profile_image)){
            $vendorUploadPath = env('SELLER_FILE_UPLOAD');
            $vendorOwnDirecory = $vendorUploadPath."/".sha1($this->user->id)."/"."profile_image/".$this->user->profile_image;
            return $vendorOwnDirecory;
        }else{
            return null;
        }
    }
    public function viewProfile(){
        try{
            $users = User::with('seller')->where('id',Auth::user()->id)->first();
            $user = $users->toArray();
            $districts = PostOffice::select('district')->orderBy('district','asc')->distinct()->get();
            $bankDetails = $this->seller->bankDetails()->first();
            $isDefaultUsed = SellerAddress::where('address_unique_name','default')->where('seller_id',$this->seller['id'])->count();
            $defaultAddressDetails = SellerAddress::where('address_unique_name','ILIKE','default%')->where('seller_id',$this->seller['id'])->first();
            $addressDetails = SellerAddress::where('address_unique_name','<>','default')->where('seller_id',$this->seller['id'])->get();

            $addressCount = count($addressDetails);
            $profileImage = $this->getProfileImage();
            $seller = $this->seller;
            $licenses = VendorLicenses::where('vendor_id',$seller->id)->with('license')->get();
            $licenses = $licenses->toArray();
            $vendorUploadPath = env('SELLER_FILE_UPLOAD');
            $vendorOwnDirectory = $vendorUploadPath."/".sha1($this->user->id)."/"."personal_documents/";
            return view('backend.seller.profile')->with(compact('licenses','isDefaultUsed','user','bankDetails','profileImage','seller','vendorOwnDirectory','addressDetails','addressCount','defaultAddressDetails','districts'));
        }catch (\Exception $e) {

            abort(500,$e->getMessage());
        }
    }

    public function editProfile(Requests\Web\Seller\ProfileRequest $request){
        try{
            $message = "Profile updated successfully";
            $user = Auth::user();
            $userData = $request->only('first_name','last_name','gender','dob','mobile');
            $userData = array_map('trim', $userData);
            $updateUser = User::where('id',$user->id)->update($userData);
            $request->session()->flash('success', $message);
            return redirect('profile');
        }catch (\Exception $e) {
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'user update profile changes',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function updateBankDetails(Requests\Web\Seller\BankDetailsRequest $request){
        try{
            $data = $request->only('bank_name','ifsc_code','pan_number','tan_number','account_type','branch_name','company_identification_number','beneficiary_name','account_no');
            $bankDetails = $this->seller->bankDetails()->first();
            $data = array_map('trim', $data);
            if($bankDetails==null){ //Insert If not exists
                $data['created_at'] = Carbon::now();
                $data['updated_at'] = Carbon::now();
                $data['seller_id'] = $this->seller->id;
                $this->seller->bankDetails()->insert($data);
            }else{  //Update if exists
                $this->seller->bankDetails()->update($data);
            }
            $message = 'Bank details updated successfully';
            $request->session()->flash('success', $message);
            return redirect('profile');
        }catch(\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'user update Bank details',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function updateProfileImage(Requests\Web\Seller\ProfileImageRequest $request){
        try{
            $vendorUploadPath = public_path().env('SELLER_FILE_UPLOAD');
            $vendorOwnDirecory = $vendorUploadPath.sha1($this->user->id);
            $vendorImageUploadPath = $vendorOwnDirecory."/".'profile_image';
            /* Create Upload Directory If Not Exists */
            if (!file_exists($vendorImageUploadPath)) {
                File::makeDirectory($vendorImageUploadPath, $mode = 0777, true, true);
            }
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $filename = sha1($this->user->id.time()).".{$extension}";
            $request->file('profile_image')->move($vendorImageUploadPath,$filename);
            $data = [
                'profile_image' => $filename,
            ];
            $this->user->update($data);
            $message = 'Profile image updated successfully';
            $request->session()->flash('success', $message);
            return redirect('profile');
        }catch(\Exception $e){
            abort(500,$e->getMessage());
        }
    }



    public function addNewAddress(Requests\Web\Seller\AddNewAddressRequest $request){
        try{
            $addressData = $request->all();
            $currentNow = Carbon::now();
            $addressData['address_unique_name'] = strtolower($request->address_unique_name);
            $addressData['seller_id'] = $this->seller->id;
            $addressData['created_at'] = $currentNow;
            $addressData['updated_at'] = $currentNow;
            $address = SellerAddress::create($addressData);
            $message = 'Sellers new address added successfully';
            $request->session()->flash('success', $message);
            return back();
        }catch (\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'add new address',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function deleteAddress(Requests\Web\Seller\DeleteAddressRequest $request, $id){
        try{
            $address = SellerAddress::findOrFail($id);
            $address->delete();
            $message = "Address Successfully Deleted.";
            $request->session()->flash('success', $message);
            return back();
        }catch(\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'address id' => $id,
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'delete address',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function changeLanguage(Request $request){
        try{
            $language = $request->language;
            Session::set('applocale', $language);
            if(Session::has('applocale')){
                return "true";
            }
        }catch(\Exception $e){
            $data = [
                'action' => 'validate mobile from cart',
                'request'=> $request->all(),
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
        }
    }
}
