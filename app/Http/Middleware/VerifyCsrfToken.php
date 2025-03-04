<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '*/ipay88test',
        '*/paymaya-success_wh',
        '*/paymaya-failure_wh',
        '*/paymaya-expired_wh',
        '*/paymaya-checkout_success',
        '*/paymaya-checkout_failure',
        '*/paymaya-checkout_dropout'
    ];
}
