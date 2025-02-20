<?php

declare(strict_types=1);

namespace App\Presentation\Input;

use App\Infrastructure\Support\Input;

class GetGameBySlugInput extends Input
{
    public function rules(): array
    {
        return [
            'slug' => 'required|string',
        ];
    }

    protected function validationData(): array
    {
        $extra = [
            'slug' => $this->route('slug'),
        ];
        return array_merge(parent::validationData(), $extra);
    }
}
