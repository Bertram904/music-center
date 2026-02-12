<?php

namespace App\Http\Controllers;

use App\Constants\BusinessCodes;
use App\Http\Response\ApiResponse;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    /**
     * Return a success response
     *
     * @param mixed|null $data Payload to returned
     * @param string|null $message Optional message
     * @return Response
     */
    protected function respondSuccess($data = null, ?string $message = null): Response
    {
        return ApiResponse::success($data, $message);
    }

    /**
     * Return a created response
     *
     * @param mixed|null $data Created resource data
     * @param string|null $message Optional message
     * @return Response
     */
    protected function respondCreated($data = null, ?string $message = null): Response
    {
        return ApiResponse::created($data, $message);
    }

    /**
     * Return a standard error response
     *
     * @param int $apiCode Internal API Code
     * @param int $httpCode HTTP status
     * @param string|null $message custom error message
     * @param mixed|null $data
     * @return Response
     */
    protected function respondError(
        int $apiCode,
        int $httpCode,
        $data = null,
        ?string $message = null
    ): Response
    {
        return ApiResponse::error($apiCode, $httpCode, $data, $message);
    }
}
