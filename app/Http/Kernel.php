<?php

namespace App\Http;

use App\Http\Middleware\AccountAdmin;
use App\Http\Middleware\CustomerSupport;
use App\Http\Middleware\VendorSupport;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'seller' => \App\Http\Middleware\Seller::class,
        'common' => \App\Http\Middleware\Common::class,
        'admin' => \App\Http\Middleware\Admin::class,
        'superadmin' => \App\Http\Middleware\Superadmin::class,
        'user' => \App\Http\Middleware\User::class,
        'shipmentadmin' => \App\Http\Middleware\Shipmentadmin::class,
        'setlanguage' => \App\Http\Middleware\SetLanguage::class,
        'financeadmin' => \App\Http\Middleware\Financeadmin::class,
        'data-entry-admin' => \App\Http\Middleware\DataEntry::class,
        'customersupport' => CustomerSupport::class,
        'vendorsupport' => VendorSupport::class,
        'accountadmin'=> AccountAdmin::class
    ];
}
