<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entity\Support;

use App\Domain\Entity\Support\Entity;
use Tests\TestCase;

class EntityTest extends TestCase
{
    public function testJsonSerializeReturnsObjectVars(): void
    {
        $entity = new readonly class extends Entity {
            public string $property1;
            public int $property2;

            public function __construct()
            {
                $this->property1 = 'value1';
                $this->property2 = 123;
            }
        };

        $expected = [
            'property1' => 'value1',
            'property2' => 123,
        ];

        $this->assertEquals($expected, $entity->jsonSerialize());
    }

    public function testJsonSerializeWithEmptyEntity(): void
    {
        $entity = new readonly class extends Entity {
        };

        $this->assertEquals([], $entity->jsonSerialize());
    }

    public function testJsonSerializeWithNullProperties(): void
    {
        $entity = new readonly class extends Entity {
            public ?string $property1;
            public ?int $property2;
        };

        $expected = [];

        $this->assertEquals($expected, $entity->jsonSerialize());
    }

    public function testToStringReturnsJsonString(): void
    {
        $entity = new readonly class extends Entity {
            public string $property1;
            public int $property2;

            public function __construct()
            {
                $this->property1 = 'value1';
                $this->property2 = 123;
            }
        };

        $expected = '{"property1":"value1","property2":123}';

        $this->assertEquals($expected, (string)$entity);
    }

    public function testToStringHandlesJsonException(): void
    {
        $entity = new readonly class extends Entity {
            public mixed $property;

            public function __construct()
            {
                $this->property = fopen('php://memory', 'rb');
            }
        };

        $result = (string) $entity;

        $this->assertStringContainsString('{"error":', $result);
    }
}
