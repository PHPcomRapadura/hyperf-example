<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Support\Adapter;

use App\Domain\Support\Outputable;
use App\Domain\Support\Values;
use App\Infrastructure\Support\Adapter\Mapping\Mapper;
use App\Infrastructure\Support\Adapter\Serializer;
use Tests\TestCase;

class SerializerTest extends TestCase
{
    private Serializer $serializer;
    private Mapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = $this->createMock(Mapper::class);
        $this->serializer = new Serializer($this->mapper, Values::class);
    }

    public function testIn(): void
    {
        $datum = ['key' => 'value'];
        $values = Values::createFrom($datum);

        $this->mapper->expects($this->once())
            ->method('map')
            ->with(Values::class, $values)
            ->willReturn($values);

        $result = $this->serializer->in($datum);

        $this->assertEquals($values, $result);
    }

    public function testOutWithOutputable(): void
    {
        $outputable = $this->createMock(Outputable::class);
        $outputable->expects($this->once())
            ->method('jsonSerialize')
            ->willReturn(['key' => 'value']);

        $result = $this->serializer->out($outputable);

        $this->assertEquals(['key' => 'value'], $result);
    }

    public function testOutWithArray(): void
    {
        $mapped = ['key' => 'value'];

        $result = $this->serializer->out($mapped);

        $this->assertEquals($mapped, $result);
    }
}
