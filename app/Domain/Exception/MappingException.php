<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Infrastructure\Support\Inputting\Values;
use App\Presentation\Support\Mapping\MapperError;
use InvalidArgumentException;

class MappingException extends InvalidArgumentException
{
    /**
     * @param Values $values
     * @param array<MapperError> $errors
     */
    public function __construct(
        private readonly Values $values,
        private readonly array $errors,
    ) {
        parent::__construct(sprintf(
            'Mapping failed with %d error(s). The errors are: "%s"',
            count($errors),
            implode('", "', $this->merge($errors)),
        ));
    }

    public function getValues(): Values
    {
        return $this->values;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array<MapperError> $errors
     * @return array|string[]
     */
    private function merge(array $errors): array
    {
        return array_map(fn (MapperError $error) => $error->message, $errors);
    }
}
