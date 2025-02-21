<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Memory;

use App\Domain\Collection\GameCollection;
use App\Domain\Entity\Game;
use App\Domain\Repository\GameQueryRepository;

class StaticGameQueryRepository implements GameQueryRepository
{
    public function getGames(): GameCollection
    {
        $lead1 = new Game(name: 'Cool game 1', slug: 'cool-game-1');
        $lead2 = new Game(name: 'Cool game 2', slug: 'cool-game-2');
        return GameCollection::createFrom([
            $lead1,
            $lead2,
        ]);
    }
}
