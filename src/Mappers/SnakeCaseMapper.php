<?php

namespace LaravelCaseMapperRequest\Mappers;

use Illuminate\Support\Str;

class SnakeCaseMapper
{
    public static function map(array $data): array
    {
        return collect($data)
            ->mapWithKeys(fn ($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }
}
