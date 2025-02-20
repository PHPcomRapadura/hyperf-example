<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Service;

use App\Application\Service\RecoverGameService;
use App\Domain\Collection\GameCollection;
use App\Domain\Entity\Game;
use App\Domain\Exception\GameNotFoundException;
use App\Domain\Repository\GameQueryRepository;
use Tests\TestCase;

class RecoverGameServiceTest extends TestCase
{
    public function testNullIsReturnedWhenNoGames(): void
    {
        $this->expectException(GameNotFoundException::class);

        $leadQueryRepository = $this->createMock(GameQueryRepository::class);
        $leadQueryRepository->expects($this->once())
            ->method('getGames')
            ->willReturn(GameCollection::createFrom([]));

        $service = new RecoverGameService($leadQueryRepository);
        $service->getGameBySlug('cool-game');
    }

    public function testGameIsReturnedWhenSlugMatches(): void
    {
        $lead = new Game(name: 'Cool game', slug: 'cool-game');

        $leadQueryRepository = $this->createMock(GameQueryRepository::class);
        $leadQueryRepository->expects($this->once())
            ->method('getGames')
            ->willReturn(GameCollection::createFrom([$lead]));

        $service = new RecoverGameService($leadQueryRepository);
        $result = $service->getGameBySlug('cool-game');

        $this->assertSame($lead, $result);
    }

    public function testNullIsReturnedWhenNoSlugMatches(): void
    {
        $this->expectException(GameNotFoundException::class);

        $lead = new Game(name: 'Cool game', slug: 'nope');

        $leadQueryRepository = $this->createMock(GameQueryRepository::class);
        $leadQueryRepository->expects($this->once())
            ->method('getGames')
            ->willReturn(GameCollection::createFrom([$lead]));

        $service = new RecoverGameService($leadQueryRepository);
        $service->getGameBySlug('cool-game');
    }
}
