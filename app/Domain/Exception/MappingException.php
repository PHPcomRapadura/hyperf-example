<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use InvalidArgumentException;

class MappingException extends InvalidArgumentException
{
    public function __construct(
        private readonly array $values,
        private readonly array $errors,
    ) {
        parent::__construct('Failed mapping values');
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
