<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLanguage
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
        if (Session::has('applocale')) {
            App::setLocale(Session::get('applocale'));
            $language = Session::get('applocale');
        }
        else { // This is optional as Laravel will automatically set the fallback language if there is none specified

            $language = App::getLocale();
            Session::set('applocale', $language);
        }
        return $next($request);
    }
}
