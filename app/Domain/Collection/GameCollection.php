<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Collection\Support\TypedCollection;
use App\Domain\Entity\Game;

class GameCollection extends TypedCollection
{
    public function current(): Game
    {
        return $this->enforce($this->datum());
    }

    protected function enforce(mixed $datum): Game
    {
        return ($datum instanceof Game) ? $datum : throw $this->fail(Game::class, $datum);
    }
}
