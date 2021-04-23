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
        'api_token',
    ];

    public function isDisabled($name)
    {
        if (app()->runningUnitTests()) {
            return true;    // Disable cookies encryption/decryption during unit testing
        }

        return parent::isDisabled($name);
    }
}
