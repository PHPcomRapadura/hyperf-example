<?php

declare(strict_types=1);

namespace Tests\Integration\Presentation\Action;

use App\Domain\Exception\GameNotFoundException;
use App\Presentation\Action\GetGameBySlugAction;
use App\Presentation\Input\GetGameBySlugInput;
use Tests\TestCase;

class GetGameBySlugActionTest extends TestCase
{
    public function testGetGameBySlugReturnsGameWhenSlugMatches(): void
    {
        $slug = 'cool-game-1';
        $expected = 'Cool game 1';

        $input = $this->input(class: GetGameBySlugInput::class, params: ['slug' => $slug]);

        $action = $this->make(GetGameBySlugAction::class);
        $actual = $action($input);

        $this->assertSame($expected, $actual->name);
    }

    public function testGetGameBySlugThrowsExceptionWhenSlugNotFound(): void
    {
        $this->expectException(GameNotFoundException::class);

        $slug = 'cool';
        $input = $this->input(class: GetGameBySlugInput::class, params: ['slug' => $slug]);

        $action = $this->make(GetGameBySlugAction::class);
        $action($input);
    }
}
