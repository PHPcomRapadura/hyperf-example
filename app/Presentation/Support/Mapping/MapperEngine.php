<?php

declare(strict_types=1);

namespace App\Presentation\Support\Mapping;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

use function App\Presentation\Support\gettype;

abstract class MapperEngine
{
    /**
     * @template T of mixed
     * @param class-string<T> $class
     * @param array<string, mixed> $values
     *
     * @return array<string, array>|T
     * @throws ReflectionException
     */
    protected function resolve(string $class, array $values): mixed
    {
        $errors = [];
        $reflectionClass = new ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $args = [];
        $parameters = $constructor->getParameters();
        foreach ($parameters as $parameter) {
            try {
                $args[] = $this->resolveParameter($parameter, $values);
            } catch (InvalidArgumentException $e) {
                $errors[] = new MapperError(
                    $e->getMessage(),
                    $values[$parameter->getName()] ?? null,
                    $parameter->getName(),
                );
            }
        }
        if (empty($errors)) {
            return $reflectionClass->newInstanceArgs($args);
        }
        return $errors;
    }

    /**
     * @throws ReflectionException
     */
    private function resolveParameter(ReflectionParameter $parameter, array $values): mixed
    {
        $name = $parameter->getName();

        if (! array_key_exists($name, $values)) {
            return $this->handleMissingParameter($parameter);
        }

        $value = $values[$name];
        $this->validateParameterType($parameter, $value);

        return $value;
    }

    /**
     * @throws ReflectionException
     */
    private function handleMissingParameter(ReflectionParameter $parameter): mixed
    {
        if ($parameter->isOptional() || $parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
        if ($parameter->allowsNull()) {
            return null;
        }

        throw new InvalidArgumentException('required');
    }

    private function validateParameterType(ReflectionParameter $parameter, mixed $value): void
    {
        $type = $parameter->getType();

        if ($type !== null && ! $this->isValidType($value, $type->getName())) {
            throw new InvalidArgumentException('invalid');
        }
    }

    private function isValidType(mixed $value, string $expected): bool
    {
        $type = gettype($value);
        $actual = match ($type) {
            'double' => 'float',
            'integer' => 'int',
            'boolean' => 'bool',
            default => $type,
        };

        return $actual === $expected || ($type === 'object' && $value instanceof $expected);
    }
}
