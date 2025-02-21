<?php

declare(strict_types=1);

namespace App\Presentation\Input;

use App\Infrastructure\Support\ActionInput;

class GetGameBySlugActionInput extends ActionInput
{
    public function rules(): array
    {
        return [
            'slug' => 'required|string',
        ];
    }
}
