<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Support;

use App\Domain\Support\Outputable;
use Tests\TestCase;

class OutputableTest extends TestCase
{
    public function testJsonSerializeReturnsObjectVars(): void
    {
        $entity = new class extends Outputable {
            public string $property1 = 'value1';

            public int $property2 = 123;
        };

        $expected = [
            'property1' => 'value1',
            'property2' => 123,
        ];

        $this->assertEquals($expected, $entity->jsonSerialize());
    }

    public function testJsonSerializeWithEmptyOutputable(): void
    {
        $entity = new class extends Outputable {
        };

        $this->assertEquals([], $entity->jsonSerialize());
    }

    public function testJsonSerializeWithNullProperties(): void
    {
        $entity = new class extends Outputable {
            public ?string $property1 = null;

            public ?int $property2 = null;
        };

        $expected = [];

        $this->assertEquals($expected, $entity->jsonSerialize());
    }

    public function testToStringReturnsJsonString(): void
    {
        $entity = new class extends Outputable {
            public string $property1 = 'value1';

            public int $property2 = 123;
        };

        $expected = '{"property1":"value1","property2":123}';

        $this->assertEquals($expected, (string) $entity);
    }

    public function testToStringHandlesJsonException(): void
    {
        $entity = new class extends Outputable {
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
