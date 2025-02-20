<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Collection\Support;

use DomainException;
use JsonSerializable;
use stdClass;
use Tests\TestCase;
use TypeError;

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
            [new TypedCollectionTestMockStub(), new TypedCollectionTestMockStub()],
            $collection->jsonSerialize()
        );
    }

    final public function testShouldFailOnNoJsonSerializableType(): void
    {
        $this->expectException(TypeError::class);

        TypedCollectionTestMock::createFrom([new stdClass()]);
    }

    final public function testShouldFailOnNoEnforcedType(): void
    {
        $this->expectException(DomainException::class);

        $invalid = new class implements JsonSerializable {
            public function jsonSerialize(): array
            {
                return [];
            }
        };
        TypedCollectionTestMock::createFrom([$invalid]);
    }
}
