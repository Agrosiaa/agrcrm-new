<?php

namespace App\Http\Controllers\Logs;

use App\CustomerTagRelation;
use App\CustomerUpdateActionLog;
use App\TagCloud;
use App\CrmCustomer;
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
            $updateProfileCustIds = CustomerUpdateActionLog::join('crm_customer','crm_customer.number','=','customer_update_action_log.mobile')
                        ->lists('crm_customer.number')->toArray();
            $taggedCustIds = CustomerTagRelation::join('crm_customer','crm_customer.id','=','customer_tag_relation.crm_customer_id')
            ->lists('crm_customer.number')->toArray();
            $customerLogId = array_unique(array_merge($updateProfileCustIds,$taggedCustIds));
            if($customerLogId != null){
                $resultFlag = true;
                // Search with tag name
                if($resultFlag && $request->has('mobile') && $tableData['mobile']!=""){
                    $customerLogId = CrmCustomer::where('number','like','%'.$tableData['mobile'].'%')
                        ->whereIn('number',$customerLogId)
                        ->lists('number')->toArray();
                    if(count($customerLogId) <= 0){
                        $resultFlag = false;
                    }
                }
                if($resultFlag && $request->has('user_id') && $tableData['user_id']!=""){
                    $taggedCustIds = CustomerTagRelation::join('crm_customer','crm_customer.id','=','customer_tag_relation.crm_customer_id')
                        ->whereIn('crm_customer.number',$customerLogId)
                        ->where('customer_tag_relation.user_id',$tableData['user_id'])
                        ->lists('crm_customer.number')->toArray();
                    
                    $updateProfileCustIds = CustomerUpdateActionLog::join('crm_customer','crm_customer.number','=','customer_update_action_log.mobile')
                        ->whereIn('crm_customer.number',$customerLogId)
                        ->where('customer_update_action_log.user_id',$tableData['user_id'])
                        ->lists('crm_customer.number')->toArray();
                    
                    $customerLogId = array_unique(array_merge($updateProfileCustIds,$taggedCustIds));
                    if(count($customerLogId) <= 0){
                        $resultFlag = false;
                    }
                }

                if($resultFlag && $request->has('from_date') && $tableData['from_date']!=""){
                    $taggedCustIds = CustomerTagRelation::join('crm_customer','crm_customer.id','=','customer_tag_relation.crm_customer_id')
                        ->whereIn('crm_customer.number',$customerLogId)
                        ->where('customer_tag_relation.updated_at','>=',$tableData['from_date'])
                        ->lists('crm_customer.number')->toArray();
                    
                    $updateProfileCustIds = CustomerUpdateActionLog::join('crm_customer','crm_customer.number','=','customer_update_action_log.mobile')
                        ->whereIn('crm_customer.number',$customerLogId)
                        ->where('customer_update_action_log.created_at','>=',$tableData['from_date'])
                        ->lists('crm_customer.number')->toArray();
                    
                    $customerLogId = array_unique(array_merge($updateProfileCustIds,$taggedCustIds));
                    if(count($customerLogId) <= 0){
                        $resultFlag = false;
                    }
                }

                if($resultFlag && $request->has('to_date') && $tableData['to_date']!=""){
                    $taggedCustIds = CustomerTagRelation::join('crm_customer','crm_customer.id','=','customer_tag_relation.crm_customer_id')
                        ->whereIn('crm_customer.number',$customerLogId)
                        ->where('customer_tag_relation.updated_at','<=',$tableData['to_date'])
                        ->lists('crm_customer.number')->toArray();
                    
                    $updateProfileCustIds = CustomerUpdateActionLog::join('crm_customer','crm_customer.number','=','customer_update_action_log.mobile')
                        ->whereIn('crm_customer.number',$customerLogId)
                        ->where('customer_update_action_log.created_at','<=',$tableData['to_date'])
                        ->lists('crm_customer.number')->toArray();
                    
                    $customerLogId = array_unique(array_merge($updateProfileCustIds,$taggedCustIds));
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
                $taggedCustomers = CustomerTagRelation::join('crm_customer','crm_customer.id','=','customer_tag_relation.crm_customer_id')
                        ->whereIn('crm_customer.number',array_unique($taggedCustIds))
                        ->orderBy('customer_tag_relation.id','DESC')
                        ->select('customer_tag_relation.*','crm_customer.number')->get()->toArray();
                    
                $updateProfileCustomers = CustomerUpdateActionLog::join('crm_customer','crm_customer.number','=','customer_update_action_log.mobile')
                    ->whereIn('crm_customer.number',array_unique($updateProfileCustIds))
                    ->orderBy('customer_update_action_log.id','DESC')
                    ->select('customer_update_action_log.*','crm_customer.number')->get()->toArray();

                $allCustomers = array_merge($taggedCustomers, $updateProfileCustomers);
                $sortedCustomers = collect($allCustomers)->sortByDesc('created_at');
                $customerNumbers = array_unique($sortedCustomers->lists('number')->toArray());
                
                $i = 0;
                foreach($customerNumbers as $index => $customerNumber){
                    if($i < $iDisplayLength && $index >= $iDisplayStart){
                        $limitedProducts[$i] = CrmCustomer::where('number',$customerNumber)->first();
                    }
                    $i++;
                }
                // $limitedProducts = CrmCustomer::take($iDisplayLength)->skip($iDisplayStart)
                //             ->whereIn('number',$customerNumbers)
                //             ->get()->toArray();
                for($i=0,$j = $iDisplayStart; $j < $end; $i++,$j++) {
                    $href = '/customer/customer-details/'.$limitedProducts[$j]['number'].'/'.$limitedProducts[$j]['id'];
                    $agent = '';
                    $latestUpdateDt = null;
                    
                    $custCrmIds = CrmCustomer::where('number',$limitedProducts[$j]['number'])->lists('id')->toArray();
                    $latestTag = CustomerTagRelation::whereIn('crm_customer_id',$custCrmIds)->orderBy('updated_at','DESC')->first();
                    $latestProfileUpdate = CustomerUpdateActionLog::where('mobile',$limitedProducts[$j]['number'])->orderBy('id','DESC')->first();
                    
                    if($latestTag && 
                        (($latestProfileUpdate && $latestTag['updated_at'] > $latestProfileUpdate['updated_at']) ||
                        (!$latestProfileUpdate))){
                        $latestUpdateDt = $latestTag['updated_at'];
                        $agentId = $latestTag['deleted_tag_user'] ? $latestTag['deleted_tag_user'] : $latestTag['user_id'];
                        $agent = User::where('id',$agentId)->value('name');
                    }elseif($latestProfileUpdate && 
                    (($latestTag && $latestProfileUpdate['updated_at'] > $latestTag['updated_at']) ||
                    (!$latestTag))){
                        $latestUpdateDt = $latestProfileUpdate['updated_at'];
                        $agent = User::where('id',$latestProfileUpdate['user_id'])->value('name');
                    }
                    
                    $records["data"][] = array(
                        $agent,
                        $limitedProducts[$j]['number'],
                        date('d F Y', strtotime($latestUpdateDt)),
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
