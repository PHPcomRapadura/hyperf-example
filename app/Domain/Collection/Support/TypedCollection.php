<?php

declare(strict_types=1);

namespace App\Domain\Collection\Support;

use App\Domain\Contract\Serializer;
use DomainException;

abstract class TypedCollection extends Collection
{
    final private function __construct()
    {
        parent::__construct([]);
    }

    final public static function createFrom(array $data, ?Serializer $serializable = null): static
    {
        $collection = new static();
        foreach ($data as $datum) {
            $collection->push($datum, $serializable);
        }
        return $collection;
    }

    final protected function push(mixed $datum, ?Serializer $serializable = null): void
    {
        if ($serializable) {
            $this->data[] = $serializable->in($datum);
            return;
        }
        $this->data[] = $datum;
    }

    /**
     * @template T of object
     * @param class-string<T> $type
     * @param mixed $datum
     * @return T
     */
    final protected function validate(string $type, mixed $datum): mixed
    {
        if ($datum instanceof $type) {
            return $datum;
        }
        $message = sprintf('Invalid type. Expected "%s", got "%s"', $type, get_debug_type($datum));
        throw new DomainException($message);
    }
}
