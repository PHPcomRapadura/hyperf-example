<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Adapter;

use App\Domain\Contract\Serializer as Contract;
use App\Domain\Support\Outputable;
use App\Domain\Support\Values;
use App\Infrastructure\Support\Adapter\Mapping\Mapper;

use function Util\Type\Cast\toArray;

class Serializer implements Contract
{
    /**
     * @template T of object
     * @param Mapper $mapper
     * @param class-string<T> $type
     */
    public function __construct(
        private readonly Mapper $mapper,
        private readonly string $type,
    ) {
    }

    public function in(mixed $datum): mixed
    {
        return $this->mapper->map($this->type, Values::createFrom($datum));
    }

    public function out(mixed $mapped): array
    {
        if ($mapped instanceof Outputable) {
            return $mapped->jsonSerialize();
        }
        return toArray($mapped);
    }
}
