<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Collection\Support;

use App\Domain\Collection\Support\TypedCollection;

final class TypedCollectionTestMock extends TypedCollection
{
    public function current(): TypedCollectionTestMockStub
    {
        return $this->datum();
    }

    protected function type(): string
    {
        return TypedCollectionTestMockStub::class;
    }
}
