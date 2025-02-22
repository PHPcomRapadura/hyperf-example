<?php

declare(strict_types=1);

namespace App\Presentation\Support\Mapping;

use App\Domain\Exception\MappingException;
use Throwable;

class Mapper extends MapperEngine
{
    /**
     * @template T of mixed
     * @param class-string<T> $class
     * @param array<string, mixed> $values
     *
     * @return T
     * @throws MappingException
     */
    public function map(string $class, array $values): mixed
    {
        try {
            $resolution = $this->resolve($class, $values);
            if (is_object($resolution)) {
                return $resolution;
            }
            $errors = $resolution;
        } catch (Throwable $e) {
            $errors = [
                new MapperError(kind: 'panic', value: $class, message: $e->getMessage()),
            ];
        }
        throw new MappingException($values, $errors);
    }
}
