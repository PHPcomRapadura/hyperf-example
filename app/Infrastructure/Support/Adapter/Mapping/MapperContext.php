<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Adapter\Mapping;

use App\Domain\Support\Values;

readonly class MapperContext
{
    public function __construct(
        public string $class,
        public Values $values,
    ) {
    }
}
