<?php

namespace App\Http\Controllers\Logs;

use App\CustomerTagRelation;
use App\CustomerUpdateActionLog;
use App\TagCloud;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\User;

class CustomerUpdateLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function manage(Request $request){
        try{
            $user = Auth::user();
            $agents = User::where('role_id',2)->get();
            return view('backend.log.manage')->with(compact('agents'));
        }catch(\Exception $exception){
            $data =[
                'action' => 'agent customer update logs',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
    public function logListing(Request $request){
        try{
            $tableData = $request->all();
            $searchData = NULL;
            $customerLogId = CustomerUpdateActionLog::join('crm_customer','crm_customer.number','=','customer_update_action_log.mobile')
                        ->lists('crm_customer.id')->toArray();
            if($customerLogId != null){
                $resultFlag = true;
                // Search with tag name
                if($resultFlag && $request->has('mobile') && $tableData['mobile']!=""){
                    $customerLogId = CustomerUpdateActionLog::join('crm_customer','crm_customer.number','=','customer_update_action_log.mobile')
                        ->whereIn('crm_customer.id',$customerLogId)->where('customer_update_action_log.mobile','like','%'.$tableData['mobile'].'%')->lists('crm_customer.id');
                    if(count($customerLogId) <= 0){
                        $resultFlag = false;
                    }
                }
                if($resultFlag && $request->has('user_id') && $tableData['user_id']!=""){
                    $customerLogId = CustomerUpdateActionLog::join('crm_customer','crm_customer.number','=','customer_update_action_log.mobile')
                            ->whereIn('crm_customer.id',$customerLogId)->where('customer_update_action_log.user_id',$tableData['user_id'])->lists('crm_customer.id');
                    if(count($customerLogId) <= 0){
                        $resultFlag = false;
                    }
                }

                $iTotalRecords = count($customerLogId);
                $iDisplayLength = intval($request->length);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                $iDisplayStart = intval($request->start);
                $sEcho = intval($request->draw);
                $records = array();
                $records["data"] = array();
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                $limitedProducts = CustomerUpdateActionLog::join('crm_customer','crm_customer.number','=','customer_update_action_log.mobile')
                            ->join('users','users.id','=','customer_update_action_log.user_id')
                            ->take($iDisplayLength)->skip($iDisplayStart)
                            ->orderBy('customer_update_action_log.created_at','desc')
                            ->select('customer_update_action_log.*','crm_customer.id as cust_id','users.name as csr_name')
                            ->get()->toArray();
                for($i=0,$j = $iDisplayStart; $j < $end; $i++,$j++) {
                    $href = '/customer/customer-details/'.$limitedProducts[$j]['mobile'].'/'.$limitedProducts[$j]['cust_id'];
                    $records["data"][] = array(
                        $limitedProducts[$j]['csr_name'],
                        $limitedProducts[$j]['mobile'],
                        date('d F Y', strtotime($limitedProducts[$j]['created_at'])),
                        '<a href="'.$href.'" class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i>View</a>',
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
