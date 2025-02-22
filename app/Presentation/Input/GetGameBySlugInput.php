<?php

declare(strict_types=1);

namespace App\Presentation\Input;

use App\Infrastructure\Support\Adapter\Input;

class GetGameBySlugInput extends Input
{
    public function rules(): array
    {
        return [
            'slug' => 'required|string',
        ];
    }
}
