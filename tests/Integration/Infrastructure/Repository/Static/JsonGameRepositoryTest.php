<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Repository\Static;

use App\Domain\Entity\Game;
use App\Infrastructure\Repository\Memory\JsonGameRepository;
use Tests\TestCase;

class JsonGameRepositoryTest extends TestCase
{
    public function testGetGamesReturnsGameCollection(): void
    {
        $repository = $this->make(JsonGameRepository::class);
        $leads = $repository->getGames();

        $this->assertCount(2, $leads);
    }

    public function testGetGamesContainsExpectedGames(): void
    {
        $repository = $this->make(JsonGameRepository::class);
        $leads = $repository->getGames()->all();

        $lead1 = new Game(name: 'Cool game 1', slug: 'cool-game-1');
        $lead2 = new Game(name: 'Cool game 2', slug: 'cool-game-2');

        $this->assertEquals($lead1, $leads[0]);
        $this->assertEquals($lead2, $leads[1]);
    }
}
