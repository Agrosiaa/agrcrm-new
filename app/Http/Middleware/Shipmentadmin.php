<?php

namespace App\Http\Middleware;

use Closure;
use App\Role;
use Illuminate\Support\Facades\Auth;

class Shipmentadmin
{
    public function handle($request, Closure $next)
    {
        $userRole = Role::findOrFail(Auth::user()->role_id);
        if($userRole['slug'] != 'shipmentadmin' && $userRole['slug'] != 'shipmentpartner'){
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                $request->session()->flash('error','Unauthorized access');
                return redirect('home');
            }
        }
        return $next($request);
    }
}
