<?php

namespace LaravelCaseMapperRequest\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use LaravelCaseMapperRequest\Attributes\MapName;
use ReflectionClass;

trait HasMapNameTransformers
{
    protected array $keyMap = [];

    public function validationData(): array
    {
        $original = parent::validationData();

        $reflection = new ReflectionClass(static::class);
        $attributes = $reflection->getAttributes(MapName::class);

        if (empty($attributes)) {
            return $original;
        }

        $attribute = $attributes[0]->newInstance();
        $mapper = $attribute->mapperClass;

        if (! class_exists($mapper) || ! method_exists($mapper, 'map')) {
            return $original;
        }

        $mapped = $mapper::map($original);

        foreach ($original as $originalKey => $value) {
            $transformed = $mapper::map([$originalKey => $value]);
            $mappedKey = array_key_first($transformed);
            if ($mappedKey) {
                $this->keyMap[$mappedKey] = $originalKey;

                if (is_array($value)) {
                    foreach (array_keys($value) as $index) {
                        $this->keyMap["{$mappedKey}.{$index}"] = "{$originalKey}.{$index}";
                    }
                }
            }
        }

        return $mapped;
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();

        $remappedErrors = collect($errors)->mapWithKeys(function ($messages, $mappedKey) {
            $originalKey = $this->keyMap[$mappedKey] ?? $mappedKey;

            return [$originalKey => $messages];
        })->toArray();

        throw ValidationException::withMessages($remappedErrors);
    }
}
