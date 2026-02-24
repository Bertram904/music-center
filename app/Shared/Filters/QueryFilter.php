<?php

declare(strict_types = 1);

namespace App\Shared\Filters;

/**
 * DTO
 * Carries filtering parameters safely across layers without depending on Http request
 */
final class QueryFilter
{
    public function __construct(
        public readonly ?string $keyword = null,
        public readonly array $exact = [],
        public readonly array $range = [],
        public readonly ?string $sortBy = null,
        public  readonly string $sortDir = 'desc'
    )
    {}
}
