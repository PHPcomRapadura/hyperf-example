<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Adapter\Mapping;

use App\Domain\Support\Values;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;

use function array_key_exists;
use function array_map;
use function class_exists;
use function gettype;
use function is_array;
use function is_string;

abstract class MapperEngine
{
    /**
     * @param ReflectionParameter $parameter
     * @param mixed $value
     * @return ?MapperContext
     * @throws ReflectionException
     */
    protected function resolveDataParameterValue(ReflectionParameter $parameter, mixed $value): ?MapperContext
    {
        $parameterClass = $this->resolveDataParameterClass($parameter);
        if ($parameterClass === null) {
            return null;
        }

        $parameterValues = $this->resolveDataParameterValues($parameterClass, $value);
        if ($parameterValues === null) {
            return null;
        }
        return new MapperContext($parameterClass, $parameterValues);
    }

    /**
     * @param ReflectionParameter $parameter
     * @return null|class-string<object>
     */
    protected function resolveDataParameterClass(ReflectionParameter $parameter): ?string
    {
        $type = $parameter->getType();
        $classes = $this->extractTypes($type);
        foreach ($classes as $class) {
            if (is_string($class) && class_exists($class)) {
                return $class;
            }
        }
        return null;
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
        $input = is_array($value) ? $value : [];
        $values = [];
        foreach ($parameters as $index => $parameter) {
            $name = $parameter->getName();
            if (array_key_exists($name, $input) || array_key_exists($index, $input)) {
                $values[$name] = $input[$name] ?? $input[$index] ?? null;
            }
        }
        return Values::createFrom($values);
    }

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
        if ($type === null) {
            return;
        }
        $types = $this->extractTypes($type);
        foreach ($types as $type) {
            if ($this->isValidType($value, $type)) {
                return;
            }
        }
        throw new InvalidArgumentException('invalid');
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

    /**
     * @param ?ReflectionType $type
     * @return array<class-string<object>|string>
     */
    private function extractTypes(?ReflectionType $type): array
    {
        if ($type instanceof ReflectionNamedType) {
            return [$type->getName()];
        }
        if ($type instanceof ReflectionIntersectionType || $type instanceof ReflectionUnionType) {
            /** @var array<ReflectionNamedType> $reflectionNamedTypes */
            $reflectionNamedTypes = $type->getTypes();
            return array_map(
                fn (ReflectionNamedType|ReflectionIntersectionType $type) => $type->getName(),
                $reflectionNamedTypes
            );
        }
        return [];
    }
}
