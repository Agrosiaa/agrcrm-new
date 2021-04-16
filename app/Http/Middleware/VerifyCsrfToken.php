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
        '/leads/sales-lead-listing/*','/leads/sales-chat','/agents/sales-agent-listing','/customer/customer-order-listing/*','/customer/abandoned-cart/*',
        '/crm/csr-orders','/tag/tag-listing'
    ];
}
