<?php

namespace App\Http\Controllers\Customer;

use App\CustomerNumberStatus;
use App\CrmCustomer;
use App\LoggedCustomerProfile;
use App\Reminder;
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
                    $records["data"][] = array(
                        'AGR'.str_pad($limitedOrders[$j]->id, 9, "0", STR_PAD_LEFT),
                        $limitedOrders[$j]->created_at,
                        $limitedOrders[$j]->product_name,
                        $limitedOrders[$j]->quantity,
                        $limitedOrders[$j]->seller_sku,
                        $limitedOrders[$j]->status,
                        $limitedOrders[$j]->consignment_number,
                        $limitedOrders[$j]->subtotal,
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
            /*if($id != 'null' && $user['role_id'] != $admin){
                $inProfileData['user_id'] = $user['id'];
                $inProfileData['customer_number_details_id'] = $id;
                SalesChat::create($inProfileData);
            }*/
            $saleAgents = User::where('role_id','=',2)->select('id','name')->get()->toArray();
            $customerInfo = Curl::to(env('BASE_URL')."/customer-profile")
                ->withData( array( 'mobile' => $mobile))->asJson()->get();
            if($request->has('is_crm_search')){
                if($customerInfo->profile != null){
                    return 'true';
                }else{
                    return 'false';
                }
            }
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
            return view('backend.Lead.customerDetails')->with(compact('user','id','callStatuses','mobile','customerInfo','saleAgents'));
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

}
