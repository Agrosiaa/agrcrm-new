<?php

namespace App\Http\Controllers\Crm;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
}
