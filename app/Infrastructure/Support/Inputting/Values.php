<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Inputting;

use InvalidArgumentException;

final readonly class Values
{
    /**
     * @var array<string, mixed>
     */
    private array $data;

    /**
     * @param array<string, mixed> $values
     */
    public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
            if (! is_string($key)) {
                throw new InvalidArgumentException('All keys must be strings.');
            }
        }
        $this->data = $values;
    }

    public function get(string $field, mixed $default = null): mixed
    {
        return $this->data[$field] ?? $default;
    }

    public function with(string $field, mixed $value): self
    {
        return new self(array_merge($this->data, [$field => $value]));
    }

    public function has(string $field): bool
    {
        return array_key_exists($field, $this->data);
    }
}
