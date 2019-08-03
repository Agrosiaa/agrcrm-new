<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/leads/sales-lead-listing/*','/leads/sales-chat','/agents/sales-agent-listing','/leads/customer-order-listing/*'
    ];
}
