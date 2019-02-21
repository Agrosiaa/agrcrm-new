<?php
/**
 * Created by Ameya Joshi.
 * Date: 16/10/17
 * Time: 11:56 AM
 */

namespace App\Http\Middleware;

use App\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class CustomerSupport
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
        if($userRole['slug']!='customersupport'){
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