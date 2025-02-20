<?php

declare(strict_types=1);

namespace Util\Type\Cast;

if (! function_exists('toArray')) {
    /**
     * @template T
     * @template U
     * @param mixed $value
     * @param array<T, U> $default
     * @return array<T, U>
     */
    function toArray(mixed $value, array $default = []): array
    {
        return is_array($value) ? $value : $default;
    }
}

if (! function_exists('toString')) {
    function toString(mixed $value, string $default = ''): string
    {
        return is_string($value) ? $value : $default;
    }
}

if (! function_exists('toInt')) {
    function toInt(mixed $value, int $default = 0): int
    {
        return is_int($value) ? $value : $default;
    }
}

if (! function_exists('toBool')) {
    function toBool(mixed $value, bool $default = false): bool
    {
        return is_bool($value) ? $value : $default;
    }
}

namespace Util\Type\Array;

if (! function_exists('extractArray')) {
    /**
     * @template T
     * @template U
     * @param array<string, mixed> $array
     * @param string $property
     * @param array<T, U> $default
     * @return array<T, U>
     */
    function extractArray(array $array, string $property, array $default = []): array
    {
        $details = $array[$property] ?? null;
        if (! is_array($details)) {
            return $default;
        }
        return $details;
    }
}

if (! function_exists('extractString')) {
    /**
     * @param array<string, mixed> $array
     * @param string $property
     * @param string $default
     * @return string
     */
    function extractString(array $array, string $property, string $default = ''): string
    {
        $string = $array[$property] ?? $default;
        return is_string($string) ? $string : $default;
    }
}

if (! function_exists('extractInt')) {
    /**
     * @param array<string, mixed> $array
     * @param string $property
     * @param int $default
     * @return int
     */
    function extractInt(array $array, string $property, int $default = 0): int
    {
        $int = $array[$property] ?? $default;
        return is_int($int) ? $int : $default;
    }
}

if (! function_exists('extractBool')) {
    /**
     * @param array<string, mixed> $array
     * @param string $property
     * @param bool $default
     * @return bool
     */
    function extractBool(array $array, string $property, bool $default = false): bool
    {
        $bool = $array[$property] ?? $default;
        return is_bool($bool) ? $bool : $default;
    }
}
