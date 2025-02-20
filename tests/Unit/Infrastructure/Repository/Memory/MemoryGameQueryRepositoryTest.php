<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repository\Memory;

use App\Domain\Entity\Game;
use App\Infrastructure\Repository\Memory\MemoryGameQueryRepository;
use Tests\TestCase;

class MemoryGameQueryRepositoryTest extends TestCase
{
    public function testGetGamesReturnsGameCollection(): void
    {
        $repository = new MemoryGameQueryRepository();
        $leads = $repository->getGames();

        $this->assertCount(2, $leads);
    }

    public function testGetGamesContainsExpectedGames(): void
    {
        $repository = new MemoryGameQueryRepository();
        $leads = $repository->getGames()->all();

        $lead1 = new Game(name: 'Cool game 1', slug: 'cool-game-1');
        $lead2 = new Game(name: 'Cool game 2', slug: 'cool-game-2');

        $this->assertEquals($lead1, $leads[0]);
        $this->assertEquals($lead2, $leads[1]);
    }
}
