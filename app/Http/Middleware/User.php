<?php

namespace App\Http\Middleware;

use App\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class User
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
        $userRole = Role::findOrFail(Auth::user()->role_id);
        if($userRole['slug'] != 'customer' && $userRole['slug'] != 'krishimitra'){
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                $request->session()->flash('error','Unauthorized access');
                return redirect('/');
            }
        }
        return $next($request);
    }
}
