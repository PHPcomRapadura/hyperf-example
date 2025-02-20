<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Support;

use App\Infrastructure\Support\Input;
use Tests\TestCase;

class InputTest extends TestCase
{
    public function testShouldAuthorize(): void
    {
        $input = $this->make(Input::class);

        $this->assertTrue($input->authorize());
    }
}
