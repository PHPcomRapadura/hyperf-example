<?php

declare(strict_types=1);

namespace App\Domain\Collection\Support;

use App\Domain\Contract\Serializer;
use DomainException;

/**
 * @template T
 * @extends Collection<T>
 */
abstract class TypedCollection extends Collection
{
    final private function __construct()
    {
        parent::__construct([]);
    }

    /**
     * @param array $data
     * @param Serializer|null $serializable
     * @return static<T>
     */
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
     * @template U of object
     * @param class-string<U> $type
     * @param mixed $datum
     * @return U
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
