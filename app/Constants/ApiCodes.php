<?php

namespace App\Constants;

use MarcinOrlowski\ResponseBuilder\ApiCodesHelpers;
class ApiCodes extends BaseCodes
{
    use ApiCodesHelpers;

    public const UNCAUGHT_EXCEPTION = 1000; //Internal Server
    public const HTTP_NOT_FOUND = 1001;
    public const UNAUTHORIZED_EXCEPTION = 1002;
    public const UNAUTHENTICATED_EXCEPTION = 1004;
    public const VALIDATION_EXCEPTION = 1005;

    public const HTTP_FORBIDDEN = 1006;

    public const READABLE_CODE_MAP = [
        'default' => 'UnknownException',
        self::UNCAUGHT_EXCEPTION => 'ServerErrorException',
        self::HTTP_NOT_FOUND => 'ResourceNotFound',
        self::UNAUTHORIZED_EXCEPTION => 'PermissionDenied',
        self::UNAUTHENTICATED_EXCEPTION => 'LoginRequired',
        self::VALIDATION_EXCEPTION => 'ValidationFailed',
        self::HTTP_FORBIDDEN => 'Forbidden',
    ];
}
