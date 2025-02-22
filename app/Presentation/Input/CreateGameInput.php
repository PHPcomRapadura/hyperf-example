<?php

declare(strict_types=1);

namespace App\Presentation\Input;

use App\Infrastructure\Support\Presentation\ActionInput;

class CreateGameInput extends ActionInput
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'data' => ['optional:[]', 'array'],
        ];
    }
}
