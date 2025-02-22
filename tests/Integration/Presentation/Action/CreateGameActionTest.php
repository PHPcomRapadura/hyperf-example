<?php

declare(strict_types=1);

namespace Tests\Integration\Presentation\Action;

use App\Presentation\Action\CreateGameAction;
use App\Presentation\Input\CreateGameInput;
use Tests\Integration\IntegrationTestCase;

class CreateGameActionTest extends IntegrationTestCase
{
    protected array $truncate = ['games' => 'sleek'];

    final public function testCreateGameSuccessfully(): void
    {
        $data = [
            'name' => 'Cool game 1',
            'slug' => 'cool-game-1',
            'data' => ['key' => 'value'],
        ];
        $input = $this->input(CreateGameInput::class, $data);
        $action = $this->make(CreateGameAction::class);
        $action($input);

        $this->sleek->has('games', [
            ['name', '=', 'Cool game 1'],
            ['slug', '=', 'cool-game-1'],
        ]);
    }
}
