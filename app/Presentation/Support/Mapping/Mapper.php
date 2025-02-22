<?php

declare(strict_types=1);

namespace App\Presentation\Support\Mapping;

use App\Domain\Exception\MappingException;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
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
            $reflectionClass = new ReflectionClass($class);
            $constructor = $reflectionClass->getConstructor();

            if ($constructor === null) {
                return new $class();
            }

            $parameters = $constructor->getParameters() ?? [];
            $data = $this->resolveData($parameters, $values);
            $args = $this->resolveArgs($parameters, $data);
            return $reflectionClass->newInstanceArgs($args);
        } catch (MappingException $e) {
            throw $e;
        } catch (Throwable $e) {
            $errors = [
                new MapperError(kind: 'panic', value: $class, message: $e->getMessage()),
            ];
            throw new MappingException($values, $errors);
        }
    }

    /**
     * @param array<ReflectionParameter> $parameters
     * @param array<string, mixed> $values
     * @return array<string, mixed>
     * @throws ReflectionException
     */
    private function resolveData(array $parameters, array $values): array
    {
        foreach ($parameters as $parameter) {
            $field = $parameter->getName();
            if (! array_key_exists($field, $values)) {
                continue;
            }

            $value = $values[$field] ?? null;
            $parameterClass = $this->resolveDataParameterClass($parameter, $value);
            if ($parameterClass === null) {
                continue;
            }

            $parameterValues = $this->resolveDataParameterValues($parameterClass, $value);
            if ($parameterValues === null) {
                continue;
            }
            $values[$field] = $this->map($parameterClass, $parameterValues);
        }

        return $values;
    }

    /**
     * @param array<ReflectionParameter> $parameters
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     * @throws ReflectionException
     */
    private function resolveArgs(array $parameters, array $data): array
    {
        $errors = [];
        $args = [];
        foreach ($parameters as $parameter) {
            try {
                $args[] = $this->resolveArgsParameter($parameter, $data);
            } catch (InvalidArgumentException $e) {
                $errors[] = new MapperError(
                    $e->getMessage(),
                    $data[$parameter->getName()] ?? null,
                    $parameter->getName(),
                );
            }
        }
        if (empty($errors)) {
            return $args;
        }
        throw new MappingException($data, $errors);
    }
}
