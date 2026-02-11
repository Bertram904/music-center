<?php

namespace App\Exceptions\Auth;

use App\Constants\BusinessCodes;

class AccountLockedException extends \Exception
{
    protected $code = BusinessCodes::ACCOUNT_LOCKED;
    protected $message = 'Account Locked, please contact the administrator.';
}
