<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Repository\Json;

use App\Infrastructure\Repository\Json\JsonGameRepository;
use Tests\Integration\IntegrationTestCase;

use function Hyperf\Collection\collect;

class JsonGameRepositoryTest extends IntegrationTestCase
{
    protected array $truncate = ['games' => 'sleek'];

    public function testGetGamesReturnsGameCollection(): void
    {
        $this->sleek->seed('games', [
            'name' => 'cool-game-1',
            'slug' => 'Cool game 1',
            'data' => [],
        ]);
        $this->sleek->seed('games', [
            'name' => 'cool-game-2',
            'slug' => 'Cool game 2',
            'data' => [],
        ]);

        $repository = $this->make(JsonGameRepository::class);
        $games = $repository->getGames();

        $this->assertCount(2, $games);
    }

    public function testGetGamesContainsExpectedGames(): void
    {
        $this->sleek->seed('games', [
            'name' => 'cool-game-3',
            'slug' => 'Cool game 3',
            'data' => [],
        ]);

        $repository = $this->make(JsonGameRepository::class);
        $count = collect($repository->getGames()->all())
            ->filter(fn ($game) => $game->name === 'cool-game-3')
            ->count();
        $this->assertEquals(1, $count);
    }
}
