<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Adapter\Mapping;

use App\Domain\Support\Values;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;

use function gettype;

abstract class MapperEngine
{
    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    protected function resolveArgsParameter(ReflectionParameter $parameter, Values $values): mixed
    {
        $name = $parameter->getName();

        if (! $values->has($name)) {
            return $this->handleArgsMissingParameter($parameter);
        }

        $value = $values->get($name);
        $this->validateArgsParameterType($parameter, $value);

        return $value;
    }

    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    protected function handleArgsMissingParameter(ReflectionParameter $parameter): mixed
    {
        if ($parameter->isOptional() || $parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
        if ($parameter->allowsNull()) {
            return null;
        }

        throw new InvalidArgumentException('required');
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function validateArgsParameterType(ReflectionParameter $parameter, mixed $value): void
    {
        $type = $parameter->getType();
        if (! ($type instanceof ReflectionNamedType) || $this->isValidType($value, $type->getName())) {
            return;
        }
        throw new InvalidArgumentException('invalid');
    }

    /**
     * @param ReflectionParameter $parameter
     * @param mixed $value
     * @return null|class-string<object>
     */
    protected function resolveDataParameterClass(ReflectionParameter $parameter, mixed $value): ?string
    {
        $type = $parameter->getType();
        if (! $type instanceof ReflectionNamedType) {
            return null;
        }
        $class = $type->getName();
        if (! class_exists($class) || $this->isValidType($value, $class)) {
            return null;
        }
        return $class;
    }

    /**
     * @template T of object
     * @param class-string<T> $type
     * @param mixed $value
     * @return null|Values
     * @throws ReflectionException
     */
    protected function resolveDataParameterValues(string $type, mixed $value): ?Values
    {
        $reflectionClass = new ReflectionClass($type);
        $constructor = $reflectionClass->getConstructor();
        if ($constructor === null) {
            return null;
        }
        $parameters = $constructor->getParameters();
        if (empty($parameters)) {
            return null;
        }
        $parameter = $parameters[0];
        $field = $parameter->getName();
        return Values::createFrom([$field => $value]);
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
