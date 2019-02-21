<?php

namespace App\Http\Controllers\seller;

use App\Http\Controllers\CustomTraits\ImageMagickTrait;
use App\Http\Controllers\CustomTraits\OtpTrait;
use App\Http\Controllers\CustomTraits\PostOfficeTrait;
use App\License;
use App\OtpVerification;
use App\PostOffice;
use App\SellerAddress;
use App\VendorLicenses;
use Illuminate\Http\Request;
use App\City;
use App\BankDetails;
use App\Role;
use App\Seller;
use App\State;
use App\User;
use App\UserOtp;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Helpers\LanguageHelper;

class RegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        if (Session::has('applocale')) {
            $this->language = Session::get('applocale');
            App::setLocale(Session::get('applocale'));
        }else{
            $this->language = App::getLocale();
        }
    }

    use OtpTrait;
    use PostOfficeTrait;
    use ImageMagickTrait;
    public function getCity($id){
        try{
            $state = State::findOrFail($id);
            $cities = City::where('state_id',$state->id)->get()->toArray();
            $status = 200;
            $message = 'success';
        }catch(\Exception $e){
            $cities = null;
            $status = 500;
            $message = $e->getMessage();
        }
        $response = [
            'city' =>$cities,
            'message'=>$message
        ];
        return response($response,$status);
    }

    public function viewLoginRegistration(){
        try{
            $districts = PostOffice::distinct()->orderBy('district','asc')->lists('district');
            return view('frontend.seller.login-registration')->with(compact('districts'));
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }

    public function sendSaveOtp(Request $request){
        try{
            $otpData['mobile_number'] = $request['mobile_number'];
            $otpData['otp'] = $request['otp'];
            $otpData['created_at'] = Carbon::now();
            $otpData['updated_at'] = Carbon::now();
            UserOtp::insert($otpData);
        }catch(\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'action' => 'Save and send OTP',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function checkOtp(Request $request){
        try{
            $otpCount = UserOtp::where('mobile_number',$request->mobile_number)->where('otp',$request->otp)->count();
            if($otpCount == 1){
                return 'true';
            }else{
                return 'false';
            }
        }catch(\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'action' => 'Check OTP',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function registerUser(Requests\RegistrationRequest $request){
        try{
            $userData = $request->all();
            $currentNow = Carbon::now();
            $currentTimeStamp = strtotime($currentNow);
            $rememberToken = $currentTimeStamp.$userData['_token'];
            $role_id = Role::where('slug','seller')->first();
            $userData['password'] = bcrypt($request['password']);
            $userData['remember_token'] = $rememberToken;
            $userData['is_active'] = 0;
            $userData['is_email'] = 0;
            $userData['role_id'] = $role_id->id;
            $userData['created_at'] = $currentNow;
            $userData['updated_at'] = $currentNow;
            $user = User::create($userData);
            $data = array(
                'mobile'=>$request->mobile,
                'otp'=>$request->otp
            );
            $otpData = OtpVerification::where($data)->first();
            $otpData->delete();
            $userData['user_id'] = $user->id;
            if($request->hasFile('pan_card')){
                $userData['pan_card'] = $request->file('pan_card')->getClientOriginalName();
                $this->uploadDocuments($request->file('pan_card'),$user->id);
            }
            if($request->hasFile('shop_act')){
                $userData['shop_act'] = $request->file('shop_act')->getClientOriginalName();
                $this->uploadDocuments($request->file('shop_act'),$user->id);
            }
            if($request->hasFile('gstin_certificate')){
                $userData['gstin_certificate'] = $request->file('gstin_certificate')->getClientOriginalName();
                $this->uploadDocuments($request->file('gstin_certificate'),$user->id);
            }
            if($request->hasFile('cancelled_cheque')){
                $userData['cancelled_cheque'] = $request->file('cancelled_cheque')->getClientOriginalName();
                $this->uploadDocuments($request->file('cancelled_cheque'),$user->id);
            }
            $seller = Seller::create($userData);
            $userData['seller_id'] = $seller->id;
            $userData['address_unique_name'] = 'default';
            $sellerAddress = SellerAddress::create($userData);
            $bank = BankDetails::create($userData);
            $licences = $request->license;
            $licenceData = array();
            foreach($licences as $key=>$value){
                if($value['license_number'] != ""){
                $licenceId = License::where('slug',$key)->first();
                $licenceData['expiry_date'] = $value['exp_mm'];
                $licenceData['license_number'] = $value['license_number'];
                if($request->hasFile($key.'_licence')){
                    $licenceData['license_image'] = $request->file($key.'_licence')->getClientOriginalName();
                    $this->uploadDocuments($request->file($key.'_licence'),$user->id);
                }
                $licenceData['vendor_id'] = $seller->id;
                $licenceData['license_id'] = $licenceId->id;
                $licenceData['created_at']=$currentNow;
                $licenceData['updated_at']=$currentNow;
                VendorLicenses::insert($licenceData);
                $licenceData = null;
                }
            }
            /* Send Registration Email */
            $user = $user->toArray();
            Mail::send('emails.Seller.welcome', $user, function($message) use ($user){
                $message->subject('Welcome - Post Registration Approval');
                $message->to($user['email']);
                $message->from(env('FROM_EMAIL'));
            });
          $url = "http://".env('SELLER_SUB_DOMAIN_NAME').".".env('DOMAIN_NAME');
          return Redirect::to($url)->with('msg', 'Seller registration completed successfully');

        }catch(\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'action' => 'User registration',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function uploadDocuments($file,$userId){
        try{
            $vendorUploadPath = public_path().env('SELLER_FILE_UPLOAD');
            $vendorOwnDirecory = $vendorUploadPath.sha1($userId);
            $vendorImageUploadPath = $vendorOwnDirecory.DIRECTORY_SEPARATOR.'personal_documents';
            /* Create Upload Directory If Not Exists */
            if (!file_exists($vendorImageUploadPath)) {
                File::makeDirectory($vendorImageUploadPath, $mode = 0777, true, true);
            }
            if(File::exists($vendorImageUploadPath)){
                $file->move($vendorImageUploadPath,$file->getClientOriginalName());
            }
            $this->waterMarkSellerDocuments($vendorImageUploadPath,$file->getClientOriginalName());
        }catch(\Exception $e){
            $data = [
                'input_params' => $file,
                'user' => $userId,
                'action' => 'upload documents',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }


    public function checkUserEmail(Request $request){
        try{
            $emailCount = User::where('email',$request->email)->count();
            if($emailCount >= 1){
                return 'false';
            }else{
                return 'true';
            }
        }catch(\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'action' => 'Check uesr email',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
}
