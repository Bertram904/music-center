<?php

namespace App\Shared\Filters;

use Illuminate\Http\Request;

/**
 * Transforms HTTP Requests into standard QueryFilter DTOs
 */
class QueryFilterFactory
{
    public static function fromRequest(Request $request, array $exactKeys = [], array $rangeKeys = []): QueryFilter
    {
        // 1. extract exact match parameters
        $exact = $request->only($exactKeys);
        $exact = array_filter($exact, fn($value) => $value !== null);

        // 2. Extract range parameters automatically
        $range = [];
        foreach ($rangeKeys as $rangeKey) {
            if ($request->has("{$rangeKey}_from") || $request->has("{$rangeKey}_to")) {
                $range[$rangeKey] = [
                    'from' => $request->get("{$rangeKey}_from"),
                    'to' => $request->get("{$rangeKey}_to"),
                ];
            }
        }

        return new QueryFilter(
            keyword: $request->input("keyword"),
            exact: $exact,
            range: $range,
            sortBy: $request->input("sortBy", 'created_at'), // default sort by created_at
            sortDir: strtolower($request->input("sortDir", 'desc')) == 'asc' ? 'asc' : 'desc',
        );
    }
}
