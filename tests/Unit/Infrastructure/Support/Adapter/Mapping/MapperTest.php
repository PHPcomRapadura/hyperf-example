<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Support\Adapter\Mapping;

use App\Domain\Exception\MappingException;
use App\Domain\Exception\MappingExceptionItem;
use App\Domain\Support\Values;
use App\Infrastructure\Support\Adapter\Mapping\Mapper;
use DateTime;
use stdClass;
use Tests\TestCase;

class MapperTest extends TestCase
{
    private Mapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new Mapper();
    }

    final public function testMapWithValidValues(): void
    {
        $entityClass = MapperTestStubWithConstructor::class;
        $values = [
            'id' => 1,
            'price' => 19.99,
            'name' => 'Test',
            'isActive' => true,
            'tags' => ['tag1', 'tag2'],
            'more' => new MapperTestStubWithNoConstructor(),
        ];

        $entity = $this->mapper->map($entityClass, Values::createFrom($values));

        $this->assertInstanceOf($entityClass, $entity);
        $this->assertSame(1, $entity->id);
        $this->assertSame(19.99, $entity->price);
        $this->assertSame('Test', $entity->name);
        $this->assertTrue($entity->isActive);
        $this->assertSame(['tag1', 'tag2'], $entity->tags);
        $this->assertNull($entity->createdAt);
    }

    final public function testMapWithMissingOptionalValue(): void
    {
        $entityClass = MapperTestStubWithConstructor::class;
        $values = [
            'id' => 1,
            'price' => 19.99,
            'name' => 'Test',
            'isActive' => true,
            'more' => new MapperTestStubWithNoConstructor(),
            'createdAt' => '1981-08-13T00:00:00+00:00',
        ];

        $entity = $this->mapper->map($entityClass, Values::createFrom($values));

        $this->assertInstanceOf($entityClass, $entity);
        $this->assertSame(1, $entity->id);
        $this->assertSame(19.99, $entity->price);
        $this->assertSame('Test', $entity->name);
        $this->assertTrue($entity->isActive);
        $this->assertSame([], $entity->tags);
        $this->assertInstanceOf(DateTime::class, $entity->createdAt);
    }

    final public function testMapWithErrors(): void
    {
        $entityClass = MapperTestStubWithConstructor::class;
        $values = [
            'id' => 'invalid',
            'name' => 'Test',
            'isActive' => true,
            'tags' => ['tag1', 'tag2'],
            'more' => new DateTime(),
            'no' => 'invalid',
        ];

        try {
            $this->mapper->map($entityClass, Values::createFrom($values));
        } catch (MappingException $e) {
            $errors = $e->getErrors();
            $this->assertContainsOnlyInstancesOf(MappingExceptionItem::class, $errors);
            $messages = [
                "The value for 'id' is not of the expected type.",
                "The value for 'price' is required and was not provided.",
                "The value for 'more' is not of the expected type.",
            ];
            foreach ($messages as $message) {
                if ($this->hasErrorMessage($errors, $message)) {
                    continue;
                }
                $this->fail(sprintf('Error message "%s" not found', $message));
            }
        }
    }

    final public function testMapWithNoConstructor(): void
    {
        $entityClass = MapperTestStubWithNoConstructor::class;
        $values = [];

        $entity = $this->mapper->map($entityClass, Values::createFrom($values));

        $this->assertInstanceOf($entityClass, $entity);
    }

    final public function testMapWithReflectionError(): void
    {
        $entityClass = 'NonExistentClass';
        $values = [
            'id' => 1,
            'price' => 19.99,
            'name' => 'Test',
            'isActive' => true,
            'more' => new MapperTestStubWithNoConstructor(),
        ];

        try {
            $this->mapper->map($entityClass, Values::createFrom($values));
        } catch (MappingException $e) {
            $errors = $e->getErrors();
            $this->assertContainsOnlyInstancesOf(MappingExceptionItem::class, $errors);
            $this->assertTrue($this->hasErrorMessage($errors, 'Class "NonExistentClass" does not exist'));
        }
    }

    final public function testEdgeTypeCases(): void
    {
        $values = [
            'union' => 1,
            'intersection' => new MapperTestStubEdgeCaseIntersection(),
            'nested' => [
                'id' => 1,
                'price' => 19.99,
                'name' => 'Test',
                'isActive' => true,
                'more' => new MapperTestStubWithNoConstructor(),
                'tags' => ['tag1', 'tag2'],
            ],
            'whatever' => new stdClass(),
        ];

        $entity = $this->mapper->map(MapperTestStubEdgeCase::class, Values::createFrom($values));

        $this->assertInstanceOf(MapperTestStubEdgeCase::class, $entity);
        $this->assertSame(1, $entity->union);
        $this->assertInstanceOf(MapperTestStubEdgeCaseIntersection::class, $entity->intersection);
        $this->assertInstanceOf(MapperTestStubWithConstructor::class, $entity->nested);
        $this->assertInstanceOf(stdClass::class, $entity->getWhatever());
    }

    private function hasErrorMessage(array $errors, string $message): bool
    {
        foreach ($errors as $error) {
            if ($error->message === $message) {
                return true;
            }
        }
        return false;
    }
}
