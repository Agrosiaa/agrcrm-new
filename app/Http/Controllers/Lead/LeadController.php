<?php

namespace App\Http\Controllers\Lead;

use App\CallBack;
use App\CallStatus;
use App\CustomerNumberStatus;
use App\CrmCustomer;
use App\CustomerTagRelation;
use App\LoggedCustomerProfile;
use App\Reminder;
use App\SalesChat;
use App\TagCloud;
use App\User;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('logged-customer-profile',['except'=>array('CustomerDetailsView','saleChat','saleChatListing')]);
    }

    public function manage(Request $request,$type){
        try{
            $status = $type;
            $user = Auth::user();
            $callStatuses = CallStatus::get()->toArray();
            $callBacks = CallBack::all()->toArray();
            return view('backend.Lead.manage')->with(compact('user','status','callStatuses','callBacks'));
        }catch(\Exception $exception){
            $data =[
                'action' => 'get Lead manage page',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }

    public function assignCustomerNumber(Request $request){
        try{
            $user = Auth::User();
            $alreadyExist = CrmCustomer::where('number',$request->mobile_number)->whereIn('customer_number_status_id',[1,2])->first();
            if($alreadyExist == null){
                $agentId = User::join('crm_customer','crm_customer.user_id','=','users.id')
                    ->where('crm_customer.number',$request->mobile_number)
                    ->where('users.is_active',true)
                    ->select('users.id')->first();
                if($agentId != null){
                    $customerData['user_id'] = $agentId['id'];
                } else{
                    $lastRecord = CrmCustomer::orderBy('id','desc')->first();
                    $saleAgents = User::where('id','>',$lastRecord['user_id'])->where('admin_id',$user['id'])->where('role_id',2)->where('is_active',true)->first();
                    if($saleAgents == null) {
                        $saleAgents = User::where('id', '<=', $lastRecord['user_id'])->where('admin_id',$user['id'])->where('role_id', 2)->where('is_active', true)->first();
                    }
                    $customerData['user_id'] = $saleAgents['id'];
                }
                $customerData['customer_number_status_id'] = CustomerNumberStatus::where('slug', 'new')->value('id');
                $customerData['number'] = $request->mobile_number;
                $customerData['lead_source'] = $request->lead_source;
                CrmCustomer::create($customerData);
                $request->session()->flash('success','Lead created successfully');
            }else{
                $request->session()->flash('error','Lead already exist');
            }
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

    public function saleLeadListing(Request $request, $status){
        try{
            $user = Auth::user();
            $tableData = $request->all();
            $searchData = NULL;
            $orderName=null;
            $createdCustomers = Curl::to(env('BASE_URL')."/created-customers")->asJson()->get();
            $statusId = CustomerNumberStatus::where('slug', $status)->first();
            $completeStatusId = CustomerNumberStatus::where('slug','=','complete')->value('id');
            if($statusId !=null){
                if($user['role_id'] == 1){
                    $customerId = CrmCustomer::where('is_active',true)->where('customer_number_status_id',$statusId['id'])->lists('id');
                }else{
                    $customerId = CrmCustomer::where('is_active',true)->where('customer_number_status_id',$statusId['id'])->where('user_id',$user['id'])->lists('id');
                }
                $resultFlag = true;
                // Search customer mobile number
                if($request->has('mobile_number') && $tableData['mobile_number']!=""){
                    $customerId = CrmCustomer::whereIn('id',$customerId)->where('number','like','%'.$tableData['mobile_number'].'%')->lists('id');
                    if(count($customerId) <= 0){
                        $resultFlag = false;
                    }
                }
                // Filter Customer listing with respect to sales parson name
                if($resultFlag == true && $request->has('agent_name') && $tableData['agent_name']!=""){
                    $agentId = User::where('name','like','%'.$tableData['agent_name'].'%')->lists('id');
                    if(!empty($agentId)) {
                        $customerId = CrmCustomer::whereIn('id', $customerId)->whereIn('user_id', $agentId)->lists('id');
                        if (count($customerId) <= 0) {
                            $resultFlag = false;
                        }
                    } else {
                        $resultFlag = false;
                    }
                }
                $iTotalRecords = count($customerId);
                $iDisplayLength = intval($request->length);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                $iDisplayStart = intval($request->start);
                $sEcho = intval($request->draw);
                $records = array();
                $records["data"] = array();
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                $limitedProducts = CrmCustomer::where('customer_number_status_id',$statusId['id'])->whereIn('id',$customerId)->take($iDisplayLength)->skip($iDisplayStart)->orderBy('created_at','desc')->get()->toArray();
                for($i=0,$j = $iDisplayStart; $j < $end; $i++,$j++) {
                    $chat = SalesChat::where('crm_customer_id',$limitedProducts[$j]['id'])->first();
                    $reminder = Reminder::where('crm_customer_id',$limitedProducts[$j]['id'])->first();
                    if ($user['role_id'] == 1) {
                        if(in_array($limitedProducts[$j]['number'],$createdCustomers)){
                            if($limitedProducts[$j]['is_abandoned'] == true){
                                $records["data"][] = array(
                                    '<a target="_blank" href="/customer/customer-details/'.$limitedProducts[$j]['number'].'/'.$limitedProducts[$j]['id'].'">'.$limitedProducts[$j]['number'].'</a>'.'<br><br>'.'<span class="tag label label-info" style="font-size: 90%;">'.'Abandoned Cart'.'</span>',
                                    User::where('id',$limitedProducts[$j]['user_id'])->pluck('name'),
                                    date('d F Y H:i:s',strtotime($limitedProducts[$j]['created_at'])),
                                    '<a href="#" class="btn btn-sm btn-default btn-circle btn-editable chat_reply" onclick="passId('.$limitedProducts[$j]['id'].','.$limitedProducts[$j]['number'].')"><i class="fa fa-pencil"></i> Log</a>',
                                );
                            }else{
                                $records["data"][] = array(
                                    '<a target="_blank" href="/customer/customer-details/'.$limitedProducts[$j]['number'].'/'.$limitedProducts[$j]['id'].'">'.$limitedProducts[$j]['number'].'</a>',
                                    User::where('id',$limitedProducts[$j]['user_id'])->pluck('name'),
                                    date('d F Y H:i:s',strtotime($limitedProducts[$j]['created_at'])),
                                    '<a href="#" class="btn btn-sm btn-default btn-circle btn-editable chat_reply" onclick="passId('.$limitedProducts[$j]['id'].','.$limitedProducts[$j]['number'].')"><i class="fa fa-pencil"></i> Log</a>',
                                );
                            }
                            /*$mobileNumber = CrmCustomer::where('number',$limitedProducts[$j]['number'])->get()->toArray();
                            if(count($mobileNumber) == 1){
                                if($limitedProducts[$j]['customer_number_status_id'] != $completeStatusId){
                                    $updateStatus['customer_number_status_id'] = $completeStatusId;
                                    CrmCustomer::where('id',$limitedProducts[$j]['id'])->update($updateStatus);
                                }
                            }*/
                        }else {
                            if($chat == null && $reminder == null){
                                $records["data"][] = array(
                                    $limitedProducts[$j]['number'].'<span class="tag label label-info" style="font-size: 90%;">'.'new'.'</span>',
                                    User::where('id',$limitedProducts[$j]['user_id'])->pluck('name'),
                                    date('d F Y H:i:s',strtotime($limitedProducts[$j]['created_at'])),
                                    '<a href="#" class="btn btn-sm btn-default btn-circle btn-editable chat_reply" onclick="passId('.$limitedProducts[$j]['id'].','.$limitedProducts[$j]['number'].')"><i class="fa fa-pencil"></i> Log</a>
                                <a href="/leads/remove-lead/'.$limitedProducts[$j]['id'].'" class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> Remove</a>',
                                );
                            }else{
                                $records["data"][] = array(
                                    $limitedProducts[$j]['number'],
                                    User::where('id',$limitedProducts[$j]['user_id'])->pluck('name'),
                                    date('d F Y H:i:s',strtotime($limitedProducts[$j]['created_at'])),
                                    '<a href="#" class="btn btn-sm btn-default btn-circle btn-editable chat_reply" onclick="passId('.$limitedProducts[$j]['id'].','.$limitedProducts[$j]['number'].')"><i class="fa fa-pencil"></i> Log</a>
                                <a href="/leads/remove-lead/'.$limitedProducts[$j]['id'].'" class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> Remove</a>',
                                );
                            }
                        }
                    } else {
                        if(in_array($limitedProducts[$j]['number'],$createdCustomers)){
                            if($limitedProducts[$j]['is_abandoned'] == true){
                                $records["data"][] = array(
                                    '<a target="_blank" href="/customer/customer-details/'.$limitedProducts[$j]['number'].'/'.$limitedProducts[$j]['id'].'">'.$limitedProducts[$j]['number'].'</a>'.'<br><br>'.'<span class="tag label label-info" style="font-size: 90%;">'.'Abandoned Cart'.'</span>',
                                    date('d F Y H:i:s', strtotime($limitedProducts[$j]['created_at'])),
                                    '<a class="btn btn-sm btn-default btn-circle btn-editable chat_reply" onclick="passId(' . $limitedProducts[$j]['id'] . ',' . $limitedProducts[$j]['number'] . ')"><i class="fa fa-pencil"></i> Log</a>'
                                );
                            }else{
                                $records["data"][] = array(
                                    '<a target="_blank" href="/customer/customer-details/'.$limitedProducts[$j]['number'].'/'.$limitedProducts[$j]['id'].'">'.$limitedProducts[$j]['number'].'</a>',
                                    date('d F Y H:i:s', strtotime($limitedProducts[$j]['created_at'])),
                                    '<a class="btn btn-sm btn-default btn-circle btn-editable chat_reply" onclick="passId(' . $limitedProducts[$j]['id'] . ',' . $limitedProducts[$j]['number'] . ')"><i class="fa fa-pencil"></i> Log</a>'
                                );
                            }
                            /*$mobileNumber = CrmCustomer::where('number',$limitedProducts[$j]['number'])->get()->toArray();
                            if(count($mobileNumber) == 1){
                                if($limitedProducts[$j]['customer_number_status_id'] != $completeStatusId){
                                    $updateStatus['customer_number_status_id'] = $completeStatusId;
                                    CrmCustomer::where('id',$limitedProducts[$j]['id'])->update($updateStatus);
                                }
                            }*/
                        }else {
                            if($chat == null && $reminder == null){
                                $records["data"][] = array(
                                    $limitedProducts[$j]['number'].'<span class="tag label label-info" style="font-size: 90%;">'.'new'.'</span>',
                                    date('d F Y H:i:s', strtotime($limitedProducts[$j]['created_at'])),
                                    '<a class="btn btn-sm btn-default btn-circle btn-editable chat_reply" onclick="passId(' . $limitedProducts[$j]['id'] . ',' . $limitedProducts[$j]['number'] . ')"><i class="fa fa-pencil"></i> Log</a>
                                    <a class="btn btn-sm btn-default btn-circle btn-editable" onclick="createCustomer(' . $limitedProducts[$j]['number'] . ')"><i class="fa fa-pencil"></i> Create</a>',
                                );
                            }else{
                                $records["data"][] = array(
                                    $limitedProducts[$j]['number'],
                                    date('d F Y H:i:s', strtotime($limitedProducts[$j]['created_at'])),
                                    '<a class="btn btn-sm btn-default btn-circle btn-editable chat_reply" onclick="passId(' . $limitedProducts[$j]['id'] . ',' . $limitedProducts[$j]['number'] . ')"><i class="fa fa-pencil"></i> Log</a>
                                    <a class="btn btn-sm btn-default btn-circle btn-editable" onclick="createCustomer(' . $limitedProducts[$j]['number'] . ')"><i class="fa fa-pencil"></i> Create</a>',
                                );
                            }

                        }
                    }
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

    public function saleChatListing($id){
        try{
            $user = Auth::User();
            $chatHistoryData = array();
            $mobileNumber = CrmCustomer::where('id',$id)->value('number');
            $custDetailIds = CrmCustomer::where('number',$mobileNumber)->lists('id');
            $allocationData = CrmCustomer::where('number',$mobileNumber)->orderBy('created_at','asc')->get()->toArray();
            $reminderDetails = Reminder::where('crm_customer_id',$id)->orderBy('created_at','asc')->get()->toArray();
            $chatData = SalesChat::whereIn('crm_customer_id',$custDetailIds)->orderBy('created_at','asc')->get()->toArray();
            $createdTagData = CustomerTagRelation::whereIn('crm_customer_id',$custDetailIds)->orderBy('created_at','asc')->get()->toArray();
            $deleteTagData = CustomerTagRelation::whereIn('crm_customer_id',$custDetailIds)->where('is_deleted',true)->orderBy('created_at','asc')->select('user_id','crm_customer_id','tag_cloud_id as deleted_tag_id','is_deleted','deleted_datetime as created_at','deleted_tag_user')->get()->toArray();
            $tagData = array_merge($createdTagData,$deleteTagData);
            $chatRemainderData = array_merge($chatData,$reminderDetails);
            $chatRemainderAllocationData = array_merge($allocationData,$chatRemainderData);
            $chatTagRemainderAllocationData = array_merge($chatRemainderAllocationData,$tagData);
            $logData = collect($chatTagRemainderAllocationData)->sortBy('created_at');
            $i = 0;
            foreach ($logData as $value){
                if(array_key_exists('number',$value)){
                    $chatHistoryData[$i]['is_allocation'] = true;
                    $chatHistoryData[$i]['number'] = $value['number'];
                    $chatHistoryData[$i]['sale_agent'] = User::where('id',$value['user_id'])->value('name');
                    $chatHistoryData[$i]['time'] = date('d F Y H:i:s',strtotime($value['created_at']));
                }elseif (array_key_exists('deleted_tag_id',$value) && $value['is_deleted'] == true){
                    $chatHistoryData[$i]['is_deleted_tag'] = true;
                    $chatHistoryData[$i]['name'] = TagCloud::where('id',$value['deleted_tag_id'])->value('name');
                    $chatHistoryData[$i]['sale_agent'] = User::where('id',$value['deleted_tag_user'])->value('name');
                    $chatHistoryData[$i]['time'] = date('d F Y H:i:s',strtotime($value['created_at']));
                }elseif (array_key_exists('tag_cloud_id',$value)){
                    $chatHistoryData[$i]['is_created_tag'] = true;
                    $chatHistoryData[$i]['name'] = TagCloud::where('id',$value['tag_cloud_id'])->value('name');
                    $chatHistoryData[$i]['sale_agent'] = User::where('id',$value['user_id'])->value('name');
                    $chatHistoryData[$i]['time'] = date('d F Y H:i:s',strtotime($value['created_at']));
                } else {
                    $chatHistoryData[$i]['is_allocation'] = false;
                    if(array_key_exists('reminder_time',$value)){
                        $callBack = CallBack::where('id',$value['call_back_id'])->value('slug');
                        if($callBack == 'call-back-1'){
                            $chatHistoryData[$i]['reminder_time'] = true;
                            $chatHistoryData[$i]['call'] = CallBack::where('slug','call-back-1')->value('name');
                            $chatHistoryData[$i]['callTime'] = date('d F Y H:i:s',strtotime($value['created_at']));
                            $chatHistoryData[$i]['nextCall'] = CallBack::where('slug','call-back-2')->value('name');
                            $chatHistoryData[$i]['reminder'] = date('d F Y H:i:s',strtotime($value['reminder_time']));
                        } elseif ($callBack == 'call-back-2') {
                            $chatHistoryData[$i]['reminder_time'] = true;
                            $chatHistoryData[$i]['call'] = CallBack::where('slug','call-back-2')->value('name');
                            $chatHistoryData[$i]['callTime'] = date('d F Y H:i:s',strtotime($value['created_at']));
                            $chatHistoryData[$i]['nextCall'] = CallBack::where('slug','call-back-3')->value('name');
                            $chatHistoryData[$i]['reminder'] = date('d F Y H:i:s',strtotime($value['reminder_time']));
                        }elseif($callBack == 'call-back-3'){
                            $chatHistoryData[$i]['reminder_time'] = true;
                            $chatHistoryData[$i]['call'] = CallBack::where('slug','call-back-3')->value('name');
                            $chatHistoryData[$i]['callTime'] = date('d F Y H:i:s',strtotime($value['created_at']));
                            $chatHistoryData[$i]['reminder'] = null;
                        }else{
                            $chatHistoryData[$i]['reminder_time'] = false;
                            $chatHistoryData[$i]['is_schedule'] = true;
                            $chatHistoryData[$i]['reminder'] = date('d F Y H:i:s',strtotime($value['reminder_time']));
                        }
                    }else{
                        $chatHistoryData[$i]['reminder_time'] = false;
                        if($value['user_id'] == $user['id']){
                            $chatHistoryData[$i]['userName'] = User::where('id',$value['user_id'])->value('name');
                            $chatHistoryData[$i]['time'] = date('d F Y H:i:s',strtotime($value['created_at']));
                            $chatHistoryData[$i]['message'] = $value['message'];
                            $chatHistoryData[$i]['user'] = true;
                            if($value['call_status_id'] != null) {
                                $chatHistoryData[$i]['status'] = CallStatus::where('id',$value['call_status_id'])->value('name');
                            } else {
                                $chatHistoryData[$i]['status'] = null;
                            }
                        }else{
                            $chatHistoryData[$i]['userName'] = User::where('id',$value['user_id'])->value('name');
                            $chatHistoryData[$i]['time'] = date('d F Y H:i:s',strtotime($value['created_at']));
                            $chatHistoryData[$i]['message'] = $value['message'];
                            $chatHistoryData[$i]['user'] = false;
                            if($value['call_status_id'] != null) {
                                $chatHistoryData[$i]['status'] = CallStatus::where('id',$value['call_status_id'])->value('name');
                            } else {
                                $chatHistoryData[$i]['status'] = null;
                            }
                        }
                    }
                }
                $i++;
            }
            return $chatHistoryData;
        }catch(\Exception $e){
            $data = [
                'action' => 'Chat details',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function saleChat(Request $request){
        try{
            $user = Auth::user();
            $chatData['user_id'] = $user['id'];
            $chatData['crm_customer_id'] = $request['customer_id'];
            $chatData['call_status_id'] = $request['reply_status_id'];
            $chatData['message'] = $request['reply_message'];
            SalesChat::create($chatData);
            $connectStatusId = CallStatus::where('slug','connected')->value('id');
            $failedCallStatus = CallStatus::whereIn('slug',['invalid','out-of-coverage-area','rejected-busy','ringing','mobile-switched-off'])->lists('id')->toArray();
            if($connectStatusId == $request['reply_status_id']){
                $mobileNumber = CrmCustomer::where('id',$request['customer_id'])->value('number');
                    $createdCustomers = Curl::to(env('BASE_URL')."/created-customers")->asJson()->get();
                    if(in_array($mobileNumber,$createdCustomers)){
                        $completeStatusId = CustomerNumberStatus::where('slug','complete')->value('id');
                        $updateCust['customer_number_status_id'] = $completeStatusId;
                        CrmCustomer::where('id',$request['customer_id'])->update($updateCust);
                        $request->session()->flash('success','Lead status updated to complete successfully');
                    }
            }else{
                $callBackThree = Reminder::where('call_back_id','=',3)
                                    ->where('crm_customer_id','=',$request['customer_id'])
                                    ->value('id');
                if($callBackThree != null && array_key_exists($request['reply_status_id'],$failedCallStatus)){
                    $failStatusId = CustomerNumberStatus::where('slug','failed')->value('id');
                    $updateCust['customer_number_status_id'] = $failStatusId;
                    CrmCustomer::where('id',$request['customer_id'])->update($updateCust);
                    $request->session()->flash('success','Lead status updated to failed successfully');
                }
            }
            if($request->has('in_profile') && $request->has('reply_status_id') && $request->reply_status_id != null  &&  $user['role_id'] == 2){
                $loggedCustomer = LoggedCustomerProfile::where('user_id',$user['id'])->first();
                if($loggedCustomer != null){
                    $loggedCustomer->update(['session_url' => null]);
                    $inProfileData['user_id'] = $user['id'];
                    $inProfileData['crm_customer_id'] = $request['customer_id'];
                    SalesChat::create($inProfileData);
                }
            }
        }catch(\Exception $e){
            $data = [
                'action' => 'Create Chat',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function setReminder(Request $request){
        try{
            $inputDate = str_replace('-','',$request->reminder_time);
            if($request->reminder_time != ''){
                $data['reminder_time'] = Carbon::parse($inputDate);
            }
            $data['call_back_id'] = $request->call_back_id;
            $data['crm_customer_id'] = $request->customer_status_detail_id;
            $setReminder = Reminder::create($data);
            $callBack1 = CallBack::where('slug','call-back-1')->value('id');
            if($setReminder->call_back_id == $callBack1){
                $callBackStatusId = CustomerNumberStatus::where('slug','call-back')->value('id');
                $updateStatus['customer_number_status_id'] = $callBackStatusId;
                CrmCustomer::where('id',$request->customer_status_detail_id)->update($updateStatus);
            }
            return back();
        }catch(\Exception $e){
            $data = [
                'action' => 'Set Reminder',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function callBackStatus($custDetailId){
        try{
            $reminderStatus = Reminder::where('crm_customer_id',$custDetailId)->orderBy('call_back_id','desc')->get()->first();
            $data['status_id'] = $reminderStatus['call_back_id'];
            $currentDateTime = Carbon::now();
            if($reminderStatus['reminder_time'] != null) {
                if ($currentDateTime > $reminderStatus['reminder_time']){
                    $data['setNextCall'] = true;
                } else {
                    $data['setNextCall'] = false;
                }
            }else{
                $data['setNextCall'] = true;
            }
            return $data;
        }catch(\Exception $e){
            $data = [
                'action' => 'Set Reminder',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function syncAbandonedCart(Request $request){
        try{
            $user = Auth::User();
            $abandonedCartAgents = User::where('is_active',true)->where('is_abandoned_cart_agent',true)->where('admin_id',$user['id'])->first();
            if($abandonedCartAgents != null){
                $newLead = CustomerNumberStatus::where('slug','new')->value('id');
                $abandonedData= Curl::to(env('BASE_URL')."/get-abandoned-carts")->asJson()->get();
                foreach ($abandonedData as $abandonedDatum){
                    $agentId = User::join('crm_customer','crm_customer.user_id','=','users.id')
                        ->where('crm_customer.number',$abandonedDatum->mobile)
                        ->where('users.is_active',true)
                        ->where('users.is_abandoned_cart_agent',true)
                        ->select('users.id')->first();
                    if($agentId != null){
                        $alreadyExist = CrmCustomer::where('number',$abandonedDatum->mobile)->whereIn('customer_number_status_id',[1,2])->value('id');
                        if(empty($alreadyExist)){
                            CrmCustomer::create(['customer_number_status_id' => $newLead, 'user_id' => $agentId['id'], 'number' => $abandonedDatum->mobile, 'is_abandoned' => true]);
                        }else{
                            CrmCustomer::where('id',$alreadyExist)->update(['user_id' => $agentId['id'], 'is_abandoned' => true]);
                        }
                    } else{
                        $lastRecord = CrmCustomer::where('is_abandoned',true)->orderBy('id','desc')->first();
                        if($lastRecord == null){
                            CrmCustomer::create(['customer_number_status_id' => $newLead, 'user_id' => $abandonedCartAgents['id'], 'number' => $abandonedDatum->mobile, 'is_abandoned' => true]);
                        }else{
                            $saleAgents = User::where('id','>',$lastRecord['user_id'])->where('is_abandoned_cart_agent',true)->where('admin_id',$user['id'])->where('role_id',2)->where('is_active',true)->first();
                            if($saleAgents == null) {
                                $saleAgents = User::where('id', '<=', $lastRecord['user_id'])->where('is_abandoned_cart_agent',true)->where('admin_id',$user['id'])->where('role_id', 2)->where('is_active', true)->first();
                            }
                            $alreadyExist = CrmCustomer::where('number',$abandonedDatum->mobile)->whereIn('customer_number_status_id',[1,2])->value('id');
                            if(empty($alreadyExist)){
                                CrmCustomer::create(['customer_number_status_id' => $newLead, 'user_id' => $saleAgents['id'], 'number' => $abandonedDatum->mobile, 'is_abandoned' => true]);
                            }else{
                                CrmCustomer::where('id',$alreadyExist)->update(['user_id' => $saleAgents['id'], 'is_abandoned' => true]);
                            }
                        }
                    }
                }
                $request->session()->flash('success','Abandoned cart sync successfully');
            }else{
                $request->session()->flash('error','No abandoned cart agent available');
            }

            return back();
        }catch(\Exception $exception){
            $data =[
                'action' => 'Abandoned cart lead',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }

    public function removeLead(Request $request, $id){
        try{
            CrmCustomer::where('id',$id)->delete();
            return back();
        }catch(\Exception $e){
            $data = [
                'action' => 'Remove lead',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }


    public function importCustomerCallDataView(Request $request){
        try{
            return view('backend.admin.importCustomerCallDataExcel');
        }catch(\Exception $exception){
            $data =[
                'action' => 'export excel view',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
    public function importCustomerCallDataSheet(Request $request){
        try{
            $user = Auth::user();
            $lastCallDate = Carbon::now();
            $completedLead = CustomerNumberStatus::where('slug', 'complete')->value('id');
            $reader = ReaderFactory::create(Type::XLSX); // for XLSX files
            $reader->open($request->file('excel_file'));
            $sheetIndex = 1;
            foreach($reader->getSheetIterator() as $sheet){
                if($sheetIndex==1){
                    $rowIndex = 1;
                    $customerData = array();
                    foreach ($sheet->getRowIterator() as $rows) {
                        $leadPresent = CrmCustomer::where('number',$rows[3])->first();
                        if($rowIndex > 1 && $leadPresent == null && !(str_contains($rows['11'],'invalid') || str_contains($rows['11'],'Invalid'))){
                            if($rows[1] != null){
                                $customerData['user_id'] = $user->id;
                            }else{
                                $customerData['user_id'] = $user->id;
                            }
                            $customerData['customer_number_status_id'] = $completedLead;
                            $customerData['number'] = $rows[3];
                            $customerData['is_active'] = false;
                            $customerData['lead_source'] = 'Customer call data sheet';
                            if(empty($rows[0])){
                                $customerData['created_at'] = $lastCallDate;
                            }else{
                                $customerData['created_at'] = $rows[0];
                                $lastCallDate = $rows[0];
                            }
                            $customerData['updated_at'] = Carbon::now();
                            $crmCustomer = CrmCustomer::insertGetId($customerData);
                            // Create-Assign tag to customer from call tag index
                            for ($tagIndex = 11 ; $tagIndex <= 24 ; $tagIndex++){
                                if(array_key_exists($tagIndex,$rows) && $rows[$tagIndex] != ''){
                                    $tag = TagCloud::where('name','like',$rows[$tagIndex])->first();
                                    if($tag == null){
                                        $tag = TagCloud::create(['name' => $rows[$tagIndex],'user_id' => $user['id']]);
                                    }
                                    $customerTag = CustomerTagRelation::where('crm_customer_id',$crmCustomer)->where('tag_cloud_id',$tag['id'])->first();
                                    if($customerTag == null){
                                        CustomerTagRelation::create(['tag_cloud_id' => $tag['id'], 'crm_customer_id' => $crmCustomer,'user_id' => $user['id']]);
                                    }
                                }
                            }
                            $agrUserData = array();
                            $agrUserData['fname'] = $rows[2] == '' ? '' : $rows[2];
                            $agrUserData['lname'] = '';
                            $agrUserData['mobile'] = $rows[3];
                            $agrUserData['dob'] = '';
                            $agrUserData['email'] = '';
                            $agrUserData['address_mobile'] = $rows[3];
                            $agrUserData['address_fname'] = $rows[2] == '' ? '' : $rows[2];
                            $agrUserData['house_block'] = 'NA';
                            $agrUserData['road'] = 'NA';
                            $agrUserData['state'] = 'NA';
                            $agrUserData['village_premises'] = $rows[5] == '' ? 'NA' : $rows[5];
                            $agrUserData['area'] = $rows[10] == '' ? 'NA' : $rows[10];
                            $agrUserData['taluka'] = $rows[7] == '' ? 'NA' : $rows[7];
                            $agrUserData['dist'] = $rows[8] == '' ? 'NA' : $rows[8];
                            $agrUserData['state'] = 'NA';
                            $agrUserData['pincode'] = $rows[9] == '' ? 'NA' : $rows[9];
                            $response = Curl::to(env('BASE_URL')."/create-customer")->withData($agrUserData)->asJson()->get();
                        }elseif ($rowIndex > 1 && $leadPresent != null && !(str_contains($rows['11'],'invalid') || str_contains($rows['11'],'Invalid'))){
                            for ($tagIndex = 11 ; $tagIndex <= 24 ; $tagIndex++){
                                if(array_key_exists($tagIndex,$rows) && $rows[$tagIndex] != ''){
                                    $tag = TagCloud::where('name','like',$rows[$tagIndex])->first();
                                    if($tag == null){
                                        $tag = TagCloud::create(['name' => $rows[$tagIndex],'user_id' => $user['id']]);
                                    }
                                    $customerTag = CustomerTagRelation::where('crm_customer_id',$leadPresent['id'])->where('tag_cloud_id',$tag['id'])->first();
                                    if($customerTag == null){
                                        CustomerTagRelation::create(['tag_cloud_id' => $tag['id'], 'crm_customer_id' => $leadPresent['id'],'user_id' => $user['id']]);
                                    }
                                }
                            }
                        }
                        $rowIndex++;
                    }
                }
                $sheetIndex++;
            }
            $reader->close();
            $message = "File uploaded successfully";
            $request->session()->flash('success', $message);
            return redirect('/leads/import-customer-call-data');
        }catch(\Exception $exception){
            $data =[
                'action' => 'export excel upload',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }

    /*
    public function exportCustomerView(Request $request){
        try{
            return view('backend.Lead.customerExcel.logistic-Import-Excel');
        }catch(\Exception $exception){
            $data =[
                'action' => 'export excel view',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
    public function exportCustomerSheet(Request $request){
        try{
            $user = Auth::user();
            $reader = ReaderFactory::create(Type::XLSX); // for XLSX files
            $reader->open($request->file('excel_file'));
            $sheetIndex = 1;
            $numberArray = array();
            $lastRecord = CrmCustomer::orderBy('id','desc')->first();
            $saleAgentArray1 = User::where('id','>',$lastRecord['user_id'])->where('admin_id',$user['id'])->where('role_id',2)->where('is_active',true)->get()->toArray();
            $saleAgentArray2 = User::where('id','<=',$lastRecord['user_id'])->where('admin_id',$user['id'])->where('role_id',2)->where('is_active',true)->get()->toArray();
            $activeAgents = User::where('role_id',2)->where('admin_id',$user['id'])->where('is_active',true)->pluck('id')->toArray();
            $saleAgents = array_merge($saleAgentArray1,$saleAgentArray2);
            foreach($reader->getSheetIterator() as $sheet){
                if($sheetIndex==1){
                    $rowIndex = 1;
                    $setIndex = 0;
                    foreach ($sheet->getRowIterator() as $rows) {
                        if($rows[0] != 'Mobile' && $rowIndex == 1){
                            $message = "File Header name should be -Mobile";
                            Session::flash('error',$message);
                            return redirect('/leads/export-customer-number');
                        }else{
                            if($rowIndex > 1){
                                if($rows[0] == null){
                                    $message = "Please Insert Number";
                                    $request->session()->flash('error', $message);
                                    return redirect('/leads/export-customer-number');
                                }else{
                                    if(!in_array($rows[0],$numberArray)){
                                        $agentId = User::join('crm_customer','crm_customer.user_id','=','users.id')
                                            ->where('crm_customer.number',$rows[0])
                                            ->where('users.is_active',true)
                                            ->select('users.id')->first();
                                        if($agentId != null){
                                            if($agentId['id'] == $saleAgents[$setIndex]['id']){
                                                $numberArray[] = $rows[0];
                                                $customerData['customer_number_status_id'] = CustomerNumberStatus::where('slug','new')->value('id');
                                                $customerData['user_id'] = $agentId['id'];
                                                $customerData['number'] = $rows[0];
                                                CrmCustomer::create($customerData);
                                                if($setIndex >= count($saleAgents)-1){
                                                    $setIndex = 0;
                                                }else{
                                                    $setIndex++;
                                                }
                                            }else{
                                                $numberArray[] = $rows[0];
                                                $customerData['customer_number_status_id'] = CustomerNumberStatus::where('slug','new')->value('id');
                                                $customerData['user_id'] = $agentId['id'];
                                                $customerData['number'] = $rows[0];
                                                CrmCustomer::create($customerData);
                                            }
                                        }else{
                                            $numberArray[] = $rows[0];
                                            $customerData['customer_number_status_id'] = CustomerNumberStatus::where('slug','new')->value('id');
                                            $customerData['user_id'] = $saleAgents[$setIndex]['id'];
                                            $customerData['number'] = $rows[0];
                                            CrmCustomer::create($customerData);
                                            if($setIndex >= count($saleAgents)-1){
                                                $setIndex = 0;
                                            }else{
                                                $setIndex++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $rowIndex++;
                    }
                }
                $sheetIndex++;
            }
            $reader->close();
            $message = "File uploaded successfully";
            $request->session()->flash('success', $message);
            return redirect('/leads/export-customer-number');
        }catch(\Exception $exception){
            $data =[
                'action' => 'export excel upload',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }*/
}
