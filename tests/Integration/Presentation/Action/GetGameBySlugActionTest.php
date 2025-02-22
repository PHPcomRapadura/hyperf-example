<?php

declare(strict_types=1);

namespace Tests\Integration\Presentation\Action;

use App\Domain\Exception\GameNotFoundException;
use App\Presentation\Action\GetGameBySlugAction;
use App\Presentation\Input\GetGameBySlugInput;
use Tests\Integration\IntegrationTestCase;

class GetGameBySlugActionTest extends IntegrationTestCase
{
    protected array $truncate = ['games' => 'sleek'];

    final public function testGetGameBySlugReturnsGameWhenSlugMatches(): void
    {
        $slug = 'cool-game-1';
        $expected = 'Cool game 1';
        $data = [
            'name' => $expected,
            'slug' => 'cool-game-1',
            'data' => ['key' => 'value'],
        ];
        $this->sleek->seed('games', $data);

        $input = $this->input(class: GetGameBySlugInput::class, params: ['slug' => $slug]);

        $action = $this->make(GetGameBySlugAction::class);
        $actual = $action($input);

        $this->assertSame($expected, $actual->name);
    }

    final public function testGetGameBySlugThrowsExceptionWhenSlugNotFound(): void
    {
        $this->expectException(GameNotFoundException::class);

        $slug = 'cool';
        $input = $this->input(class: GetGameBySlugInput::class, params: ['slug' => $slug]);

        $action = $this->make(GetGameBySlugAction::class);
        $action($input);
    }
}
