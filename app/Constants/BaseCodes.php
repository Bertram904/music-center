<?php

namespace App\Constants;

abstract class BaseCodes
{
    public static function convertToReadable(int $code): string
    {
        // static instate of self in order to PHP know get variable from subclass
        return static::READABLE_CODE_MAP[$code] ?? static::READABLE_CODE_MAP['default'];
    }
}
