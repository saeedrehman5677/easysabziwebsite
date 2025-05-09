<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
     protected $except = [
        '/pay-via-ajax', '/success','/cancel','/fail','/ipn','/payment-razor','/bkash/*','/system_settings','sslcommerz/*'
    ];
}
