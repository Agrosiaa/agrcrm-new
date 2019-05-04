<?php

namespace App\Http\Controllers\Lead;

use App\CallStatus;
use App\CustomerNumberStatus;
use App\CustomerNumberStatusDetails;
use App\SalesChat;
use App\User;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    public function manage(Request $request,$type){
        try{
            $status = $type;
            $user = Auth::user();
            $callStatus = CallStatus::get()->toArray();
            return view('backend.Lead.manage')->with(compact('user','status','callStatus'));
        }catch(\Exception $exception){
            $data =[
                'action' => 'get Lead manage page',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
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
            foreach($reader->getSheetIterator() as $sheet){
                if($sheetIndex==1){
                    $rowIndex = 1;
                    foreach ($sheet->getRowIterator() as $rows) {
                        /* Create Array To data Insert */
                        if($rowIndex > 1){
                            Log::info($rows);
                            if($rows[0] == null){
                                $message = "Please Insert Number";
                                $request->session()->flash('error', $message);
                                return redirect('/leads/export-customer-number');
                            }else{
//                                $users = User::where();
                                $customerData['customer_number_status_id'] = CustomerNumberStatus::where('slug','new')->pluck('id');
                                $customerData['user_id'] = 2;
                                $customerData['number'] = $rows[0];
                                CustomerNumberStatusDetails::create($customerData);
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
    }

    public function saleAdminListing(Request $request, $status){
        try{
            $user = Auth::user();
            $tableData = $request->all();
            $searchData = NULL;
            $orderName=null;
            $statusId = CustomerNumberStatus::where('slug', $status)->first();
            if($statusId !=null){
                $statusIds = CustomerNumberStatus::where('id','>',$statusId['id'])->get();
                if($user['role_id'] == 1){
                    $customerId = CustomerNumberStatusDetails::where('customer_number_status_id',$statusId['id'])->lists('id');
                }else{
                    $customerId = CustomerNumberStatusDetails::where('customer_number_status_id',$statusId['id'])->where('user_id',$user['id'])->lists('id');
                }
                $resultFlag = true;
                // Search customer mobile number
                if($request->has('mobile_number') && $tableData['mobile_number']!=""){
                    $customerId = CustomerNumberStatusDetails::whereIn('id',$customerId)->where('number','like','%'.$tableData['mobile_number'].'%')->lists('id');
                    if(count($customerId) <= 0){
                        $resultFlag = false;
                    }
                }
                // Filter Customer listing with respect to sales parson name
                if($resultFlag == true && $request->has('agent_name') && $tableData['agent_name']!=""){
                    $agentId = User::where('name','like','%'.$tableData['agent_name'].'%')->lists('id');
                    Log::info($agentId);
                    if(!empty($agentId)) {
                        $customerId = CustomerNumberStatusDetails::whereIn('id', $customerId)->whereIn('user_id', $agentId)->lists('id');
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
                $limitedProducts = CustomerNumberStatusDetails::where('customer_number_status_id',$statusId['id'])->whereIn('id',$customerId)->take($iDisplayLength)->skip($iDisplayStart)->orderBy('created_at','desc')->get()->toArray();
                for($i=0,$j = $iDisplayStart; $j < $end; $i++,$j++) {

                    if ($user['role_id'] == 1) {
                        $records["data"][] = array(
                            $limitedProducts[$j]['number'],
                            User::where('id',$limitedProducts[$j]['user_id'])->pluck('name'),
                            date('d F Y H:i:s',strtotime($limitedProducts[$j]['created_at'])),
                            '<a href="#" class="btn btn-sm btn-default btn-circle btn-editable chat_reply" onclick="passId('.$limitedProducts[$j]['id'].','.$limitedProducts[$j]['number'].')"><i class="fa fa-pencil"></i> Chat</a>',
                        );
                    } else {
                        $records["data"][] = array(
                            $limitedProducts[$j]['number'],
                            date('d F Y H:i:s',strtotime($limitedProducts[$j]['created_at'])),
                            '<a class="btn btn-sm btn-default btn-circle btn-editable chat_reply" onclick="passId('.$limitedProducts[$j]['id'].','.$limitedProducts[$j]['number'].')"><i class="fa fa-pencil"></i> Chat</a>
                            <a class="btn btn-sm btn-default btn-circle btn-editable" onclick="createCustomer('.$limitedProducts[$j]['number'].')"><i class="fa fa-pencil"></i> Create</a>',
                        );
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
            $chatHistoryData = array();
            $chatData = SalesChat::where('customer_number_details_id',$id)->get()->toArray();
            $i = 0;
            foreach ($chatData as $value){
                if($value['user_id'] != null){
                    $chatHistoryData[$i]['userName'] = User::where('id',$value['user_id'])->value('name');
                }
                $chatHistoryData[$i]['time'] = date('d F Y H:i:s',strtotime($value['created_at']));
                $chatHistoryData[$i]['message'] = $value['message'];
                if($value['call_status_id'] != null) {
                    $chatHistoryData[$i]['status'] = CallStatus::where('id',$value['call_status_id'])->value('name');
                } else {
                    $chatHistoryData[$i]['status'] = null;
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
            $chatData['customer_number_details_id'] = $request['customer_id'];
            $chatData['call_status_id'] = $request['reply_status_id'];
            $chatData['message'] = $request['reply_message'];
            SalesChat::create($chatData);
            return back();
        }catch(\Exception $e){
            $data = [
                'action' => 'Create Chat',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

}
