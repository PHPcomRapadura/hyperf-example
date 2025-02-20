<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Service;

use App\Application\Service\RecoverGameService;
use App\Domain\Entity\Game;
use App\Domain\Exception\GameNotFoundException;
use Tests\TestCase;

class RecoverGameServiceTest extends TestCase
{
    public function testGameIsReturnedWhenSlugMatches(): void
    {
        $lead = new Game(name: 'Cool game 1', slug: 'cool-game-1');
        $service = $this->make(RecoverGameService::class);
        $result = $service->getGameBySlug('cool-game-1');

        $this->assertSame($lead->name, $result->name);
    }

    public function testExceptionIsThrownWhenNoSlugMatches(): void
    {

        $this->expectException(GameNotFoundException::class);
        $service = $this->make(RecoverGameService::class);
        $result = $service->getGameBySlug('cool-game');

        $this->assertNull($result);
    }

    public function testMultipleGamesWithSameSlug(): void
    {
        $lead = new Game(name: 'Cool game 2', slug: 'cool-game-2');
        $service = $this->make(RecoverGameService::class);
        $result = $service->getGameBySlug('cool-game-2');

        $this->assertSame($lead->name, $result->name);
    }
}
