<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\Support\Entity;

class Game extends Entity
{
    public function __construct(
        public readonly string $name,
        public readonly string $slug,
        public readonly array $data = [],
    ) {
    }
}
