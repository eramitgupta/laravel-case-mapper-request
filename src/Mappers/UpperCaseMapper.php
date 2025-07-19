<?php

namespace LaravelCaseMapperRequest\Mappers;

use Illuminate\Support\Str;

class UpperCaseMapper
{
    public static function map(array $data): array
    {
        return collect($data)
            ->mapWithKeys(function ($value, $key) {
                return [Str::snake(Str::lower($key)) => $value];
            })
            ->toArray();
    }
}
