<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\UserRoles;
use App\WorkOrderStatusDetail;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('admin');
        if(!Auth::guest()) {
            $this->user = Auth::user();
            if (Session::has('role_type')) {
                $this->userRoleType = Session::get('role_type');
            }
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
    public function home(Request $request){
        try{
            $user = Auth::User();
            $response = Curl::to(env('BASE_URL')."/order-detail")
                ->withData( array( 'role_id' => $user['role_id'] ,'sales_id' => $user['id']) )->asJson()->get();
            return view('backend.admin.home')->with(compact('response'));
        }catch(\Exception $e){
            $data = [
                'action' => 'validate mobile from cart',
                'request'=> $request->all(),
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
        }

    }

    public function manageAgents(Request $request){
        try{
            $user = Auth::User();
            $saleAgents = User::where('role_id',2)->get()->toArray();
            return view('backend.admin.manageAgents')->with(compact('user','saleAgents'));
        }catch (\Exception $e){
            $data = [
                'action' => 'Manage Sales Agents',
                'request' =>  $request->all(),
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
        }
    }

    /*public function salesAgentListing(Request $request){
        try{
            $user = Auth::user();
            $tableData = $request->all();
            $searchData = NULL;
            $orderName=null;
            $agentId = User::where('role_id',2)->lists('id');
            if($agentId !=null){
                $resultFlag = true;
                // Search agent by name
                if($request->has('agent_name') && $tableData['agent_name']!=""){
                    $agentId = User::whereIn('id',$agentId)->where('name','like','%'.$tableData['agent_name'].'%')->lists('id');
                    if(count($agentId) <= 0){
                        $resultFlag = false;
                    }
                }
                $iTotalRecords = count($agentId);
                $iDisplayLength = intval($request->length);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                $iDisplayStart = intval($request->start);
                $sEcho = intval($request->draw);
                $records = array();
                $records["data"] = array();
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                $limitedProducts = User::whereIn('id',$agentId)->take($iDisplayLength)->skip($iDisplayStart)->orderBy('created_at','desc')->get()->toArray();
                for($i=0,$j = $iDisplayStart; $j < $end; $i++,$j++) {
                    if($limitedProducts[$j]['is_active'] == true){
                        $records["data"][] = array(
                            $limitedProducts[$j]['name'],
                            '<div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-small bootstrap-switch-animate bootstrap-switch-on" style="width: 90px;"><div class="bootstrap-switch-container" onclick="changeStatus('.$limitedProducts[$j]['id'].')" style="width: 132px; margin-left: 0px;"><span class="bootstrap-switch-handle-on bootstrap-switch-primary" style="width: 44px;">ON</span><span class="bootstrap-switch-label" style="width: 44px;">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default" style="width: 44px;">OFF</span></div></div>'
                            //'<input class="switch-light switch-material" onclick="changeStatus('.$limitedProducts[$j]['id'].')" type="checkbox" checked>',
                        );
                    } else {
                        $records["data"][] = array(
                            $limitedProducts[$j]['name'],
                            '<div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-small bootstrap-switch-animate bootstrap-switch-off" style="width: 90px;"><div class="bootstrap-switch-container" onclick="changeStatus('.$limitedProducts[$j]['id'].')" style="width: 132px; margin-left: -44px;"><span class="bootstrap-switch-handle-on bootstrap-switch-primary" style="width: 44px;">ON</span><span class="bootstrap-switch-label" style="width: 44px;">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default" style="width: 44px;">OFF</span></div></div>'
                            // '<input class="switch-light switch-material" onclick="changeStatus('.$limitedProducts[$j]['id'].')" type="checkbox">',
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
    }*/

    public function changeAgentStatus(Request $request, $id){
        try{
            $saleAgent = User::where('id',$id)->first();
            if($saleAgent['is_active'] == true){
                $saleAgentData['is_active'] = false;
                $saleAgent->update($saleAgentData);
            } else {
                $saleAgentData['is_active'] = true;
                $saleAgent->update($saleAgentData);
            }
        }catch (\Exception $e){
            $data = [
                'action' => 'Change Sales Admin Status',
                'request' => $request->all(),
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
        }
    }
}
