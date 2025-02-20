<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Collection\GameCollection;

interface GameQueryRepository
{
    public function getGames(): GameCollection;
}
