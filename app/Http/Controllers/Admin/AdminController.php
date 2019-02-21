<?php

namespace App\Http\Controllers\Admin;

use App\WorkOrderStatusDetail;
use Illuminate\Http\Request;

use App\Http\Requests;
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
    public function home(){
        return view('backend.admin.home');
    }
}
