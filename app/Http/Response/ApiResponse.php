<?php

namespace App\Http\Response;

use App\Constants\BusinessCodes;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    public static function success($data = null, ?string $message = null)
    {
        return ResponseBuilder::asSuccess(BusinessCodes::SUCCESS)
            ->withData($data)
            ->withMessage($message)
            ->build();
    }

    public static function created($data = null, ?string $message = null)
    {
        return ResponseBuilder::asSuccess(BusinessCodes::CREATED)
            ->withData($data)
            ->withMessage($message)
            ->withHttpCode(Response::HTTP_CREATED)
            ->build();
    }

    public static function error(int $apiCode, int $httpCode, $data = null, ?string $message = null)
    {
        return ResponseBuilder::asError($apiCode)
            ->withData($data)
            ->withMessage($message)
            ->withHttpCode($httpCode)
            ->build();
    }
}
