<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\CustomTraits\OrderTrait;
use App\Order;
use App\OrderStatus;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('seller');
        if(!Auth::guest()) {
            $this->user = Auth::user();
            $this->seller = $this->user->seller()->first();
            if (Session::has('role_type')) {
                $this->userRoleType = Session::get('role_type');
            }else{
		$this->userRoleType = $this->user->role->slug;
	    }
        }
    }

    use OrderTrait;


}
