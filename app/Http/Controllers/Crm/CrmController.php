<?php

namespace App\Http\Controllers\Crm;

use App\CustomerNumberStatus;
use App\CrmCustomer;
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

class CrmController extends Controller
{
    public function __construct()
    {
        $this->middleware('logged-customer-profile',['except'=>array('CustomerDetailsView','createLead','saleChat')]);

    }
    public function manage(Request $request){
        try{
            $user = Auth::user();
            $role = UserRoles::where('id',$user['role_id'])->value('slug');
            return view('backend.crm.manage')->with(compact('user','role'));
        }catch(\Exception $exception){
            $data =[
                'action' => 'get crm manage page',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }

    public function csrOrders(Request $request){
        try{
            $user = Auth::user();
            return view('backend.csrOrder.manage');
        }catch(\Exception $exception){
            $data =[
                'action' => 'get crm orders',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }

    public function createLead(Request $request, $agentId, $number){
        try{
            $user = Auth::User();
            $customerData['user_id'] = $agentId;
            $customerData['customer_number_status_id'] = CustomerNumberStatus::where('slug', 'new')->value('id');
            $customerData['number'] = $number;
            CrmCustomer::create($customerData);
            return back();
        }catch (\Exception $exception){
            $data = [
                'action' => 'Assign Customer Number to Agent',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }

    public function setSchedule(Request $request){
        try{
            $inputDate = str_replace('-','',$request->reminder_time);
            if($request->reminder_time != ''){
                $data['reminder_time'] = Carbon::parse($inputDate);
            }
            $data['crm_customer_id'] = $request->cust_detail_id;
            $data['is_schedule'] = true;
            Reminder::create($data);
            return back();
        }catch (\Exception $exception){
            $data = [
                'action' => 'Set Schedule',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }

    public function CsrOrderListing(Request $request){
        try{
            $user = Auth::user();
            $customerOrders = Curl::to(env('BASE_URL')."/csr-orders")
                ->withData( array( 'csr_id' => $user['id'], 'retrieve' => 'ids'))->asJson()->post();
            $tableData = $request->all();
            $searchData = NULL;
            $orderName=null;
            if(!empty($customerOrders->orders)){
                $resultFlag = true;
                // Search customer mobile number
                if($request->has('order_no') && $tableData['order_no']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/csr-orders")
                        ->withData( array( 'csr_id' => $user['id'], 'filter' => true , 'order_no' => $tableData['order_no'],'ids' => $customerOrders->orders))->asJson()->post();
                    if(empty($customerOrders->orders)){
                        $resultFlag = false;
                    }
                }
                // Filter Customer listing with respect to sales parson name
                if($resultFlag == true && $request->has('product') && $tableData['product']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/csr-orders")
                        ->withData( array( 'csr_id' => $user['id'], 'filter' => true,'product' => $tableData['product'],'ids' => $customerOrders->orders))->asJson()->post();
                    if(empty($customerOrders->orders)){
                        $resultFlag = false;
                    }
                }

                if($resultFlag == true && $request->has('quantity') && $tableData['quantity']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/csr-orders")
                        ->withData( array( 'csr_id' => $user['id'], 'filter' => true,'quantity' => $tableData['quantity'], 'ids' => $customerOrders->orders))->asJson()->post();
                    if(empty($customerOrders->orders)){
                        $resultFlag = false;
                    }
                }

                if($resultFlag == true && $request->has('customer') && $tableData['customer']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/csr-orders")
                        ->withData( array( 'csr_id' => $user['id'], 'filter' => true,'customer' => $tableData['customer'],'ids' => $customerOrders->orders))->asJson()->post();
                    if(empty($customerOrders->orders)){
                        $resultFlag = false;
                    }
                }

                if($resultFlag == true && $request->has('status') && $tableData['status']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/csr-orders")
                        ->withData( array( 'csr_id' => $user['id'],'filter' => true, 'status' => $tableData['status'], 'ids' => $customerOrders->orders))->asJson()->post();
                    if(empty($customerOrders->orders)){
                        $resultFlag = false;
                    }
                }

                if($resultFlag == true && $request->has('awb_no') && $tableData['awb_no']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/csr-orders")
                        ->withData( array( 'csr_id' => $user['id'],'filter' => true, 'awb_no' => $tableData['awb_no'], 'ids' => $customerOrders->orders))->asJson()->post();
                    if(empty($customerOrders->orders)){
                        $resultFlag = false;
                    }
                }
                if($resultFlag == true && $request->has('shipment') && $tableData['shipment']!=""){
                    $customerOrders = Curl::to(env('BASE_URL')."/csr-orders")
                        ->withData( array( 'csr_id' => $user['id'],'filter' => true, 'shipment' => $tableData['shipment'], 'ids' => $customerOrders->orders))->asJson()->post();
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
                $limitedOrders = $customerOrders = Curl::to(env('BASE_URL')."/csr-orders")
                    ->withData( array( 'csr_id' => $user['id'], 'retrieve' => 'data','filteredIds' => $customerOrders->orders))->asJson()->post();
                for($i=0,$j = $iDisplayStart; $j < $end; $i++,$j++) {
                    $records["data"][] = array(
                        'AGR'.str_pad($limitedOrders[$j]->id, 9, "0", STR_PAD_LEFT),
                        $limitedOrders[$j]->created_at,
                        $limitedOrders[$j]->full_name.'<br>'.$limitedOrders[$j]->mobile,
                        $limitedOrders[$j]->product_name,
                        $limitedOrders[$j]->quantity,
                        $limitedOrders[$j]->status,
                        $limitedOrders[$j]->shipment.'<br>'.$limitedOrders[$j]->consignment_number,
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

}
