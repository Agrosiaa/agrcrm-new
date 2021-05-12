<?php

namespace App\Http\Controllers\Customer;

use App\CustomerNumberStatus;
use App\CrmCustomer;
use App\CustomerTagRelation;
use App\LoggedCustomerProfile;
use App\Reminder;
use App\TagCloud;
use App\TagType;
use App\UserRoles;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\CallStatus;
use App\SalesChat;
use App\User;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = Auth::user();
    }
    public function customerOrderListing(Request $request, $mobile){
        try{
            $user = Auth::user();
            $customerOrders = Curl::to(env('BASE_URL')."/customer-orders")
                ->withData( array( 'mobile' => $mobile, 'retrieve' => 'ids'))->asJson()->get();
            $tableData = $request->all();
            $searchData = NULL;
            $orderName=null;
            if(!empty($customerOrders->orders)){
                $resultFlag = true;
                // Search customer mobile number
                if($request->has('order_no') && $tableData['order_no']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/customer-orders")
                        ->withData( array( 'mobile' => $mobile, 'filter' => true , 'order_no' => $tableData['order_no'],'ids' => $customerOrders->orders))->asJson()->get();
                    if(empty($customerOrders->orders)){
                        $resultFlag = false;
                    }
                }
                // Filter Customer listing with respect to sales parson name
                if($resultFlag == true && $request->has('product') && $tableData['product']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/customer-orders")
                        ->withData( array( 'mobile' => $mobile, 'filter' => true,'product' => $tableData['product'],'ids' => $customerOrders->orders))->asJson()->get();
                    if(empty($customerOrders->orders)){
                        $resultFlag = false;
                    }
                }

                if($resultFlag == true && $request->has('quantity') && $tableData['quantity']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/customer-orders")
                        ->withData( array( 'mobile' => $mobile, 'filter' => true,'quantity' => $tableData['quantity'], 'ids' => $customerOrders->orders))->asJson()->get();
                    if(empty($customerOrders->orders)){
                        $resultFlag = false;
                    }
                }

                if($resultFlag == true && $request->has('skuid') && $tableData['skuid']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/customer-orders")
                        ->withData( array( 'mobile' => $mobile, 'filter' => true,'skuid' => $tableData['skuid'],'ids' => $customerOrders->orders))->asJson()->get();
                    if(empty($customerOrders->orders)){
                        $resultFlag = false;
                    }
                }

                if($resultFlag == true && $request->has('status') && $tableData['status']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/customer-orders")
                        ->withData( array( 'mobile' => $mobile,'filter' => true, 'status' => $tableData['status'], 'ids' => $customerOrders->orders))->asJson()->get();
                    if(empty($customerOrders->orders)){
                        $resultFlag = false;
                    }
                }

                if($resultFlag == true && $request->has('awb_no') && $tableData['awb_no']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/customer-orders")
                        ->withData( array( 'mobile' => $mobile,'filter' => true, 'awb_no' => $tableData['awb_no'], 'ids' => $customerOrders->orders))->asJson()->get();
                    if(empty($customerOrders->orders)){
                        $resultFlag = false;
                    }
                }

                $iTotalRecords = count($customerOrders->orders);
                $iDisplayLength = intval($request->length);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                $iDisplayStart = intval($request->start);
                $sEcho = intval($request->draw);
                $records = array();
                $records["data"] = array();
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                $limitedOrders = $customerOrders = Curl::to(env('BASE_URL')."/customer-orders")
                    ->withData( array( 'mobile' => $mobile, 'retrieve' => 'data','filteredIds' => $customerOrders->orders))->asJson()->get();
                for($i=0,$j = $iDisplayStart; $j < $end; $i++,$j++) {
                    if($limitedOrders[$j]->is_configurable == true){
                        $displayPrice = (($limitedOrders[$j]->discounted_price * (($limitedOrders[$j]->length) * ($limitedOrders[$j]->width)) * $limitedOrders[$j]->quantity) +$limitedOrders[$j]->delivery_amount-$limitedOrders[$j]->coupon_discount);
                    }else{
                        $displayPrice = (($limitedOrders[$j]->discounted_price * $limitedOrders[$j]->quantity) +$limitedOrders[$j]->delivery_amount - $limitedOrders[$j]->coupon_discount);
                    }
                    if($limitedOrders[$j]->agrosiaa_discount != null && $limitedOrders[$j]->agrosiaa_discount > 0){
                        $displayPrice = "<del>".$displayPrice."</del> | ".($displayPrice - $limitedOrders[$j]->agrosiaa_discount);
                    }
                    $records["data"][] = array(
                        'AGR'.str_pad($limitedOrders[$j]->id, 9, "0", STR_PAD_LEFT),
                        $limitedOrders[$j]->created_at,
                        $limitedOrders[$j]->product_name,
                        $limitedOrders[$j]->quantity,
                        $limitedOrders[$j]->seller_sku,
                        $limitedOrders[$j]->status,
                        $limitedOrders[$j]->consignment_number,
                        $displayPrice,
                    );

                }
                if (isset($request->customActionType) && $request->customActionType == "group_action") {
                    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                    $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
                }
                $records["draw"] = $sEcho;
                $records["recordsTotal"] = $iTotalRecords;
                $records["recordsFiltered"] = $iTotalRecords;
            }else{
                $records = '';
            }
        }catch(\Exception $e){
            $records = $e->getMessage();
        }
        return response()->json($records);
    }
    public function CustomerDetailsView(Request $request, $mobile, $id){
        try{
            $user = Auth::user();
            $admin = UserRoles::where('slug','admin')->value('id');
            $callStatuses = CallStatus::get()->toArray();
            if($id == 'null'){
                $id = CrmCustomer::where('number','=',$mobile)->value('id');
                if(empty($id)){
                    $id = 'null';
                }
            }

            $customerInfo = Curl::to(env('BASE_URL')."/customer-profile")
                ->withData( array( 'mobile' => $mobile))->asJson()->get();
            if($request->has('is_crm_search')){
                if($customerInfo->profile != null){
                    return 'true';
                }else{
                    return 'false';
                }
            }
            $customerTags = CrmCustomer::join('customer_tag_relation','customer_tag_relation.crm_customer_id','=','crm_customer.id')
                        ->join('tag_cloud','customer_tag_relation.tag_cloud_id','=','tag_cloud.id')
                        ->where('crm_customer.number','=',$mobile)
                        ->where('is_deleted','!=',true)
                        ->select('customer_tag_relation.tag_cloud_id','tag_cloud.name','customer_tag_relation.crm_customer_id')->get()->toArray();
            if($user['role_id'] != $admin){
                $sessionUrl = '/customer/customer-details/'.$mobile.'/'.$id;
                $loggedCustomer = LoggedCustomerProfile::where('user_id',$user['id'])->first();
                if($loggedCustomer != null){
                    if($loggedCustomer['session_url'] == null){
                        $loggedCustomer->update(['session_url' => $sessionUrl]);
                    }
                }else{
                    LoggedCustomerProfile::create(['user_id' => $user['id'], 'session_url' => $sessionUrl]);
                }
            }
            return view('backend.Lead.customerDetails')->with(compact('user','id','callStatuses','mobile','customerInfo','customerTags'));
        }catch(\Exception $exception){
            $data =[
                'action' => 'customer detail',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
    public function abandonedCartListing(Request $request, $mobile){
        try{
            $user = Auth::user();
            $abandonedCart = Curl::to(env('BASE_URL')."/get-abandoned-cart-data")
                ->withData( array( 'mobile' => $mobile, 'retrieve' => 'ids'))->asJson()->get();
            $tableData = $request->all();
            $searchData = NULL;
            $orderName=null;
            if(!empty($abandonedCart->cart)){
                $resultFlag = true;
                // Search customer mobile number
                if($request->has('toDate') && $tableData['toDate']!="" && $request->has('fromDate') && $tableData['fromDate']!=""){
                    $abandonedCart = Curl::to(env('BASE_URL')."/get-abandoned-cart-data")
                        ->withData( array( 'mobile' => $mobile, 'filter' => true , 'toDate' => $tableData['toDate'], 'fromDate' => $tableData['fromDate'], 'ids' => $abandonedCart->cart))->asJson()->get();
                    if(empty($abandonedCart->cart)){
                        $resultFlag = false;
                    }
                }
                // Filter Customer listing with respect to sales parson name
                if($resultFlag == true && $request->has('toUpdatedDate') && $tableData['toUpdatedDate']!="" && $request->has('fromUpdatedDate') && $tableData['fromUpdatedDate']!=""){
                    $abandonedCart = Curl::to(env('BASE_URL')."/get-abandoned-cart-data")
                        ->withData( array( 'mobile' => $mobile, 'filter' => true,'toUpdatedDate' => $tableData['toUpdatedDate'],'fromUpdatedDate' => $tableData['fromUpdatedDate'],'ids' => $abandonedCart->cart))->asJson()->get();
                    if(empty($abandonedCart->cart)){
                        $resultFlag = false;
                    }
                }

                $iTotalRecords = count($abandonedCart->cart);
                $iDisplayLength = intval($request->length);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                $iDisplayStart = intval($request->start);
                $sEcho = intval($request->draw);
                $records = array();
                $records["data"] = array();
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                $limitedOrders = $customerOrders = Curl::to(env('BASE_URL')."/get-abandoned-cart-data")
                    ->withData( array( 'mobile' => $mobile, 'retrieve' => 'data','filteredIds' => $abandonedCart->cart))->asJson()->get();
                //$limitedOrders = CrmCustomer::where('customer_number_status_id',$statusId['id'])->whereIn('id',$customerId)->take($iDisplayLength)->skip($iDisplayStart)->orderBy('created_at','desc')->get()->toArray();
                for($i=0,$j = $iDisplayStart; $j < $end; $i++,$j++) {
                    if($limitedOrders[$j]->is_web === false){
                        $is_web = "Mobile";
                    }elseif($limitedOrders[$j]->is_web === null && $limitedOrders[$j]->is_web === NULL){
                        $is_web = "-";
                    }else{
                        $is_web = "Web";
                    }
                    $records["data"][] = array(
                        $is_web,
                        date('d M Y h:i:s ',strtotime($limitedOrders[$j]->created_at)),
                        date('d M Y h:i:s ',strtotime($limitedOrders[$j]->updated_at)),
                        '<a href="javascript:void(0);" class="btn btn-xs green dropdown-toggle" data-target="#AbandonedDetailModal" data-toggle="modal" type="button" aria-expanded="true" onclick="openCustomerDetails('.$limitedOrders[$j]->id.')">Open Detail</a>'
                    );

                }
                if (isset($request->customActionType) && $request->customActionType == "group_action") {
                    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                    $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
                }
                $records["draw"] = $sEcho;
                $records["recordsTotal"] = $iTotalRecords;
                $records["recordsFiltered"] = $iTotalRecords;
            }else{
                $records = '';
            }
        }catch(\Exception $e){
            $records = $e->getMessage();
        }
        return response()->json($records);
    }
    public function cartDetails(Request $request ,$id){
        try{
            $cartDetails = Curl::to(env('BASE_URL')."/abandoned-cart-details/$id")->asJson()->get();
            return view('backend.partials.customers.abandonedCartDetail')->with(compact('cartDetails'));
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }
    public function removeTag(Request $request ,$tagId, $crmCustId){
        try{
            $user = Auth::user();
            $dateTime = Carbon::now();
            $updateData['deleted_tag_user'] = $user['id'];
            $updateData['deleted_datetime'] = $dateTime;
            $updateData['is_deleted'] = true;
            if(is_numeric($tagId)){
                CustomerTagRelation::where('crm_customer_id',$crmCustId)->where('tag_cloud_id',$tagId)->update($updateData);
            }else{
                $tagName = trim($tagId);
                Log::info($tagName);
                $tag = TagCloud::where('name',$tagName)->value('id');
                Log::info($tag);
                Log::info($crmCustId);
                CustomerTagRelation::where('crm_customer_id',$crmCustId)->where('tag_cloud_id',$tag)->update($updateData);
            }
            return back();
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }
    public function createCustomer(Request $request){
        try{
            $data = $request->all();
            $response = Curl::to(env('BASE_URL')."/create-customer")->withData($data)->asJson()->get();
            if($response == 200){
                $newRequestObject = new Request();
                $request->session()->flash('success','Customer created successfully');
                $this->CustomerDetailsView($newRequestObject,$data['mobile'],'null');
            }else{
                $request->session()->flash('error','Customer not created');
                return back();
            }
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }
    public function editCustomer(Request $request){
        try{
            $user = Auth::user();
            $data['f_name'] = $request->f_name;
            $data['l_name'] = $request->l_name;
            $data['dob'] = $request->dob;
            $data['mobile'] = $request->profile_mobile;
            $data['id'] = $request->user_id;
            $data['email'] = $request->profile_email;
            $response = Curl::to(env('BASE_URL')."/edit-profile")->withData($data)->asJson()->get();
            if($response == 200){
                if($request->has('create_lead') && $request->create_lead == 'true' && $user['role_id'] != 1){
                    $customerData['user_id'] = $user['id'];
                    $customerData['customer_number_status_id'] = CustomerNumberStatus::where('slug', 'new')->value('id');
                    $customerData['number'] = $request->profile_mobile;
                    CrmCustomer::create($customerData);
                    $request->session()->flash('success','Lead created successfully');
                }else{
                    $request->session()->flash('success','Customer edited successfully');
                }
                return back();
            }else{
                $request->session()->flash('error','Customer not created');
                return back();
            }
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }
    public function createAssignTag(Request $request){
        try{
            $status = 200;
            $user = Auth::User();
            if($request->has('tag_name') && $request->tag_name != null){
                $tag = TagCloud::create(['name' => $request->tag_name,'user_id' => $user['id']]);
                CustomerTagRelation::create(['tag_cloud_id' => $tag->id, 'crm_customer_id' => $request->customer_id,'user_id' => $user['id']]);
            }
        }catch (\Exception $exception){
            $status = 500;
            $data = [
                'action' => 'Create/Edit new tag',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
        return response()->json($status);
    }
    public function createOrder(Request $request){
        try{
            $data = $request->all();
            unset($data['_token']);
            $response = Curl::to(env('BASE_URL')."/generate-order")->withData($data)->asJson()->post();
            if($response->status == 200){
                foreach($response->data as $product){
                    $this->createNewCustomerTag($product, $data['crm_customer_id'], $data['customer_mobile'], 'order');
                }
                $request->session()->flash('success','Order Placed successfully');
            }else{
                $request->session()->flash('error','Order Not Placed');
            }
            return back();
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }
    public function addAddress(Request $request){
        try{
            $data = $request->all();
            unset($data['_token']);
            unset($data['crm_customer_id']);
            $response = Curl::to(env('BASE_URL')."/add-address")->withData($data)->asJson()->post();
            if($response->status == 200){
                $this->createNewCustomerTag($data['pincode'], $request->crm_customer_id, $data['customer_mobile'], 'pincode');
                $this->createNewCustomerTag($data['state'], $request->crm_customer_id, $data['customer_mobile'], 'state');
                $this->createNewCustomerTag($data['taluka'], $request->crm_customer_id, $data['customer_mobile'], 'city');
                $this->createNewCustomerTag($data['district'], $request->crm_customer_id, $data['customer_mobile'], 'district');
                $request->session()->flash('success','Address added successfully');
            }else{
                $request->session()->flash('error','Address Not Added');
            }
            return back();
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }
    public function editAddress(Request $request){
        try{
            $data = $request->all();
            unset($data['_token']);
            unset($data['crm_customer_id']);
            unset($data['customer_mobile']);
            $response = Curl::to(env('BASE_URL')."/edit-address")->withData($data)->asJson()->post();
            if($response->status == 200){
                $this->createNewCustomerTag($data['pincode'], $request->crm_customer_id, $request->customer_mobile, 'pincode');
                $this->createNewCustomerTag($data['state'], $request->crm_customer_id, $request->customer_mobile, 'state');
                $this->createNewCustomerTag($data['taluka'], $request->crm_customer_id, $request->customer_mobile, 'city');
                $this->createNewCustomerTag($data['district'], $request->crm_customer_id, $request->customer_mobile, 'district');
                $request->session()->flash('success','Address Edited successfully');
            }else{
                $request->session()->flash('error','Address Not Edited');
            }
            return back();
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }
        public function createNewCustomerTag($tagName, $crmCustId, $custNumber, $tagType){
        try{
            $tag = TagCloud::where('name',$tagName)->first();
            $tagTypeId = TagType::where('slug',$tagType)->value('id');
            if(!$tag){
                $tag = TagCloud::create(['name' => $tagName,'user_id' => $this->user->id,'tag_type_id' => $tagTypeId]);
            }
            if($crmCustId == 'null'){
                $crmCustId = CrmCustomer::where('number',$custNumber)->value('id');
            }
            if(!$crmCustId || $crmCustId != 'null'){
                $customerTag = CustomerTagRelation::where('tag_cloud_id',$tag['id'])->where('crm_customer_id',$crmCustId)->where('tag_type_id',$tagTypeId)->first();
                if(!$customerTag){
                    CustomerTagRelation::create(['tag_cloud_id'=> $tag['id'], 'user_id' => $this->user->id,'tag_type_id' => $tagTypeId, 'crm_customer_id' => $crmCustId]);
                }
            }
            return true;
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }
}
