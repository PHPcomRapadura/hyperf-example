<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation\Support;

use App\Domain\Entity\Support\Entity;
use DateTime;

class MapperTestStubWithConstructor extends Entity
{
    public function __construct(
        public readonly int $id,
        public readonly float $price,
        public readonly string $name,
        public readonly bool $isActive,
        public readonly MapperTestStubWithNoConstructor $nested,
        public readonly ?string $foo,
        public readonly array $tags = [],
        public readonly ?DateTime $createdAt = null,
    ) {
    }
}
