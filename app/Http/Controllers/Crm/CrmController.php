<?php

namespace App\Http\Controllers\Crm;

use App\CustomerNumberStatus;
use App\CustomerNumberStatusDetails;
use App\Reminder;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CrmController extends Controller
{
    public function manage(Request $request){
        try{
            return view('backend.crm.manage');
        }catch(\Exception $exception){
            $data =[
                'action' => 'get crm manage page',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }

    public function createLead(Request $request, $agentId, $number){
        try{
            $user = Auth::User();
            /*$agentId = User::join('customer_number_status_details','customer_number_status_details.user_id','=','users.id')
                ->where('customer_number_status_details.number',$request->mobile_number)
                ->where('users.is_active',true)
                ->select('users.id')->first();*/
            if($agentId != null){
                $customerData['user_id'] = $agentId;
            } else{
                $lastRecord = CustomerNumberStatusDetails::orderBy('id','desc')->first();
                $saleAgents = User::where('id','>',$lastRecord['user_id'])->where('admin_id',$user['id'])->where('role_id',2)->where('is_active',true)->first();
                if($saleAgents == null) {
                    $saleAgents = User::where('id', '<=', $lastRecord['user_id'])->where('admin_id',$user['id'])->where('role_id', 2)->where('is_active', true)->first();
                }
                $customerData['user_id'] = $saleAgents['id'];
            }
            $customerData['customer_number_status_id'] = CustomerNumberStatus::where('slug', 'new')->value('id');
            $customerData['number'] = $number;
            CustomerNumberStatusDetails::create($customerData);
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
            $data['customer_number_status_details_id'] = $request->cust_detail_id;
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
}
