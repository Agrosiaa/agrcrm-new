<?php

namespace App\Http\Controllers\Admin;

use App\CrmCustomer;
use App\Reminder;
use App\User;
use App\UserRoles;
use App\WorkOrderStatusDetail;
use Carbon\Carbon;
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
        $this->middleware('logged-customer-profile',['except'=>array('CustomerDetailsView')]);
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
            $currentDateTime = Carbon::now();
            $response = Curl::to(env('BASE_URL')."/order-detail")
                ->withData( array( 'role_id' => $user['role_id'] ,'sales_id' => $user['id']) )->asJson()->get();
            if($user['role_id'] == 2){
                $custDetailIds = CrmCustomer::where('user_id',$user['id'])->lists('id');
                $callBackReminders = User::join('crm_customer','crm_customer.user_id','=','users.id')
                    ->join('reminder','reminder.crm_customer_id','=','crm_customer.id')
                    ->whereIn('reminder.crm_customer_id',$custDetailIds)
                    ->where('reminder.reminder_time','>=',$currentDateTime)
                    ->select('crm_customer.number','reminder.reminder_time','reminder.call_back_id')->get()->toArray();
            }else{
                $callBackReminders = User::join('crm_customer','crm_customer.user_id','=','users.id')
                    ->join('reminder','reminder.crm_customer_id','=','crm_customer.id')
                    ->where('reminder.reminder_time','>=',$currentDateTime)
                    ->select('crm_customer.number','reminder.reminder_time','reminder.call_back_id','users.name')->get()->toArray();
            }
            return view('backend.admin.home')->with(compact('user','response','callBackReminders'));
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
            $saleAgents = User::where('role_id',2)->where('admin_id',$user['id'])->get()->toArray();
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
            $request->session()->flash('success','Sales agent status changed successful');
        }catch (\Exception $e){
            $data = [
                'action' => 'Change Sales Admin Status',
                'request' => $request->all(),
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
        }
    }

    public function assignAbandonedCartAgent(Request $request, $id){
        try{
            $abandonedCartUser = User::where('id',$id)->first();
            if($abandonedCartUser['is_abandoned_cart_agent'] == true){
                $abandonedCartUser->update(['is_abandoned_cart_agent' => false]);
            }else{
                $abandonedCartUser->update(['is_abandoned_cart_agent' => true]);
            }
            $request->session()->flash('success','Abandoned cart agent status changed successful');
        }catch (\Exception $e){
            $data = [
                'action' => 'Assign agent for abandoned cart customers',
                'request' => $request->all(),
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
        }
    }
}
