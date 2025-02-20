<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Collection\Support;

use App\Domain\Collection\Support\TypedCollection;

class TypedCollectionTestMock extends TypedCollection
{
    public function current(): TypedCollectionTestMockStub
    {
        return $this->enforce($this->datum());
    }

    protected function enforce(mixed $datum): TypedCollectionTestMockStub
    {
        return ($datum instanceof TypedCollectionTestMockStub) ? $datum : throw $this->fail(TypedCollectionTestMockStub::class, $datum);
    }
}
