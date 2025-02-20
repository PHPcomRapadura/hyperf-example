<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\Support\Entity;

readonly class Game extends Entity
{
    public function __construct(
        public string $name,
        public string $slug,
        public array $data = [],
    ) {
    }
}
