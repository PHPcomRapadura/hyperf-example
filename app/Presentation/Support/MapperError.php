<?php

declare(strict_types=1);

namespace App\Presentation\Support;

class MapperError
{
    public readonly string $message;

    public function __construct(
        public readonly string $kind,
        public readonly mixed $value = null,
        public readonly string $field = '',
    ) {
        $this->message = match ($kind) {
            'required' => sprintf("The value for '%s' is required and was not provided.", $field),
            'invalid' => sprintf("The value for '%s' is not of the expected type.", $field),
            default => $kind,
        };
    }
}
