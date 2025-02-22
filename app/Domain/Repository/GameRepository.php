<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Collection\GameCollection;
use App\Domain\Entity\Game;

interface GameRepository
{
    public function persist(Game $game): void;

    public function getGames(): GameCollection;
}
