<?php

namespace LaravelCaseMapperRequest\Mappers;

use Illuminate\Support\Str;

class StudlyCaseMapper
{
    public static function map(array $data): array
    {
        return collect($data)
            ->mapWithKeys(function ($value, $key) {
                return [Str::snake($key) => $value];
            })
            ->toArray();
    }
}
