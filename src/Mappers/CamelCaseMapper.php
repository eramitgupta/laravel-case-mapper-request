<?php

namespace LaravelCaseMapperRequest\Mappers;

use Illuminate\Support\Str;

class CamelCaseMapper
{
    public static function map(array $data): array
    {
        return collect($data)
            ->mapWithKeys(fn ($value, $key) => [Str::camel($key) => $value])
            ->toArray();
    }
}
