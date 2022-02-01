<?php

namespace App\Http\Middleware;

use App\LoggedCustomerProfile;
use App\UserRoles;
use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoggedInCustomerProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $csrEmployee = UserRoles::where('slug','sales_employee')->value('id');
        if($user['role_id'] == $csrEmployee){
            $loggedCustomer = LoggedCustomerProfile::where('user_id',$user['id'])->first();
            if($loggedCustomer != null && $loggedCustomer['session_url'] != null){
                return redirect(env("APP_URL").$loggedCustomer['session_url']);
            }
            return $next($request);
        }
        return $next($request);
    }
}
