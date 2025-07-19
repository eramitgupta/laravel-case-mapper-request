<?php

namespace LaravelCaseMapperRequest\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class MapName
{
    public function __construct(public string $mapperClass) {}
}
