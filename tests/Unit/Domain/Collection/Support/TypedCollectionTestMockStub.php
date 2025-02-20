<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Collection\Support;

use JsonSerializable;

class TypedCollectionTestMockStub implements JsonSerializable
{
    public function jsonSerialize(): array
    {
        return [];
    }
}
