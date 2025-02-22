<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Collection\Support\TypedCollection;
use App\Domain\Entity\Game;

/**
 * @extends TypedCollection<Game>
 */
final class GameCollection extends TypedCollection
{
    public function current(): Game
    {
        return $this->validate(Game::class, $this->datum());
    }
}
