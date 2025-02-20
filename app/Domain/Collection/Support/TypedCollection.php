<?php

declare(strict_types=1);

namespace App\Domain\Collection\Support;

use DomainException;
use JsonSerializable;

abstract class TypedCollection extends Collection implements JsonSerializable
{
    final protected function __construct()
    {
        parent::__construct([]);
    }

    final public static function createFrom(array $data): static
    {
        $collection = new static();
        foreach ($data as $datum) {
            $collection->push($datum);
        }
        return $collection;
    }

    public function jsonSerialize(): array
    {
        return $this->data();
    }

    final protected function push(JsonSerializable $datum): static
    {
        $this->data[] = $this->enforce($datum);
        return $this;
    }

    final protected function fail(string $type, mixed $datum): DomainException
    {
        $message = sprintf('Invalid type. Expected "%s", got "%s"', $type, get_debug_type($datum));
        return new DomainException($message);
    }

    abstract protected function enforce(mixed $datum): mixed;
}
