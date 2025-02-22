<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Support;

use App\Infrastructure\Support\Inputting\Input;

class InputTestStub extends Input
{
    public function rules(): array
    {
        return [
            'test' => 'sometimes|string',
            'datum' => 'sometimes|string',
            'param' => 'sometimes|string',
        ];
    }
}
