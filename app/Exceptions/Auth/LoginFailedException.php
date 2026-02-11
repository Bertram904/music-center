<?php

namespace App\Exceptions\Auth;

use App\Constants\BusinessCodes;

class LoginFailedException extends \Exception
{
    protected $code = BusinessCodes::AUTH_LOGIN_FAILED;
    protected $message = 'Login failed, email or password is incorrect.';
}
