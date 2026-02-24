<?php

namespace App\Exceptions;

use App\Constants\BusinessCodes;
use Illuminate\Auth\AuthenticationException;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class Handler
{
    public function unauthenticated($request, AuthenticationException $exception) {
        return ResponseBuilder::asError(BusinessCodes::UNAUTHORIZED)
            ->withMessage('UNAUTHORIZED')
            ->withHttpCode(401)
            ->build();
    }
}
