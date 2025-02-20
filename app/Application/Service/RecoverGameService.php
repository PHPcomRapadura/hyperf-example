<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\Game;
use App\Domain\Exception\GameNotFoundException;
use App\Domain\Repository\GameQueryRepository;

class RecoverGameService
{
    public function __construct(private readonly GameQueryRepository $gameQueryRepository)
    {
    }

    /**
     * @throws GameNotFoundException
     */
    public function getGameBySlug(string $slug): Game
    {
        $games = $this->gameQueryRepository->getGames();
        foreach ($games as $game) {
            if ($game->slug === $slug) {
                return $game;
            }
        }
        return throw new GameNotFoundException();
    }
}
