<?php

namespace App\Http\Controllers\Lead;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    public function manage(Request $request){
        try{
            $user = Auth::user();
            return view('backend.Lead.manage')->with(compact('user'));
        }catch(\Exception $exception){
            $data =[
                'action' => 'get Lead manage page',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
}
