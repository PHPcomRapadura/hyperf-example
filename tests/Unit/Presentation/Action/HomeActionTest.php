<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation\Action;

use App\Infrastructure\Support\Input;
use App\Presentation\Action\HomeAction;
use Tests\TestCase;

class HomeActionTest extends TestCase
{
    public function testShouldBeSuccessful(): void
    {
        $input = $this->createMock(Input::class);
        $input->method('getMethod')
            ->willReturn('POST');
        $input->method('input')
            ->with('message', 'Kicking ass and taking names!')
            ->willReturn('Hello World');

        $action = new HomeAction();
        $result = $action($input);

        $this->assertEquals('POST', $result['method']);
        $this->assertEquals('Hello World', $result['message']);
    }
}
