<?php

declare(strict_types=1);

namespace App\Presentation\Input;

use App\Infrastructure\Support\ActionInput;

class HomeActionInput extends ActionInput
{
    public function rules(): array
    {
        return [
            'message' => 'sometimes|string',
        ];
    }
}
