<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        // solve xdebug cookie decryption exception
        // Illuminate\Contracts\Encryption\DecryptException: The payload is invalid
        'XDEBUG_SESSION',
    ];
}
