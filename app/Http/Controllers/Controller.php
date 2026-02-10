<?php

namespace App\Http\Controllers;

use App\Constants\BusinessCodes;
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
        return ResponseBuilder::asSuccess()
            ->withData($data)
            ->withMessage($message)
            ->build();
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
        return ResponseBuilder::asSuccess(BusinessCodes::CREATED)
            ->withData($data)
            ->withMessage($message)
            ->withHttpCode(Response::HTTP_CREATED)
            ->build();
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
    protected function responndError($data = null, ?string $message = null, int $apiCode, int $httpCode): Response
    {
        return ResponseBuilder::asError($apiCode)
            ->withData($data)
            ->withMessage($message)
            ->withHttpCode($httpCode)
            ->build();
    }
}
