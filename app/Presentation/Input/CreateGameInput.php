<?php

declare(strict_types=1);

namespace App\Presentation\Input;

use App\Infrastructure\Support\Inputting\Input;

class CreateGameInput extends Input
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'data' => ['sometimes', 'array'],
        ];
    }
}
