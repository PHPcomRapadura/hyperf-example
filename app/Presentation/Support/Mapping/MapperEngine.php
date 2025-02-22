<?php

declare(strict_types=1);

namespace App\Presentation\Support\Mapping;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

use function gettype;

abstract class MapperEngine
{
    protected function isValidType(mixed $value, string $expected): bool
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

    /**
     * @throws ReflectionException
     */
    protected function resolveArgsParameter(ReflectionParameter $parameter, array $values): mixed
    {
        $name = $parameter->getName();

        if (! array_key_exists($name, $values)) {
            return $this->handleArgsMissingParameter($parameter);
        }

        $value = $values[$name];
        $this->validateArgsParameterType($parameter, $value);

        return $value;
    }

    /**
     * @throws ReflectionException
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

    protected function validateArgsParameterType(ReflectionParameter $parameter, mixed $value): void
    {
        $type = $parameter->getType();
        if ($type === null || $this->isValidType($value, $type->getName())) {
            return;
        }
        throw new InvalidArgumentException('invalid');
    }

    protected function resolveDataParameterClass(ReflectionParameter $parameter, mixed $value): ?string
    {
        $type = $parameter->getType();
        if ($type === null || ! class_exists($type->getName())) {
            return null;
        }
        $class = $type->getName();
        if ($this->isValidType($value, $class)) {
            return null;
        }
        return $class;
    }

    /**
     * @throws ReflectionException
     */
    protected function resolveDataParameterValues(string $type, mixed $value): ?array
    {
        $reflectionClass = new ReflectionClass($type);
        $constructor = $reflectionClass->getConstructor();
        if ($constructor === null) {
            return null;
        }
        $parameters = $constructor->getParameters() ?? [];
        if (empty($parameters)) {
            return null;
        }
        $parameter = $parameters[0];
        $field = $parameter->getName();
        return [$field => $value];
    }
}
