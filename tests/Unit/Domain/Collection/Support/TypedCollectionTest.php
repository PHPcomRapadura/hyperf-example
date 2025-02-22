<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Collection\Support;

use DomainException;
use JsonSerializable;
use Tests\TestCase;

class TypedCollectionTest extends TestCase
{
    final public function testShouldCreateFromArray(): void
    {
        $collection = TypedCollectionTestMock::createFrom(
            [new TypedCollectionTestMockStub(), new TypedCollectionTestMockStub()]
        );

        $this->assertCount(2, $collection);
    }

    final public function testShouldJsonSerialize(): void
    {
        $collection = TypedCollectionTestMock::createFrom(
            [new TypedCollectionTestMockStub(), new TypedCollectionTestMockStub()]
        );

        $this->assertEquals(
            ['data' => [new TypedCollectionTestMockStub(), new TypedCollectionTestMockStub()]],
            $collection->jsonSerialize()
        );
    }

    final public function testShouldFailOnCurrentInvalidType(): void
    {
        $this->expectException(DomainException::class);

        $invalid = new class implements JsonSerializable {
            public function jsonSerialize(): array
            {
                return [];
            }
        };
        $collection = TypedCollectionTestMock::createFrom([$invalid]);
        $collection->current();
    }
}
