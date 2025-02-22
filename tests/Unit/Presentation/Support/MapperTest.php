<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation\Support;

use App\Domain\Exception\MappingException;
use App\Presentation\Support\Mapping\Mapper;
use App\Presentation\Support\Mapping\MapperError;
use DateTime;
use Google\Type\Date;
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
            'nested' => new MapperTestStubWithNoConstructor(),
        ];

        $entity = $this->mapper->map($entityClass, $values);

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
            'nested' => new MapperTestStubWithNoConstructor(),
            'createdAt' => '1981-08-13T00:00:00+00:00',
        ];

        $entity = $this->mapper->map($entityClass, $values);

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
            'nested' => new DateTime(),
            'no' => 'invalid',
        ];

        try {
            $this->mapper->map($entityClass, $values);
        } catch (MappingException $e) {
            $errors = $e->getErrors();
            $this->assertContainsOnlyInstancesOf(MapperError::class, $errors);
            $this->assertTrue($this->hasErrorMessage($errors, "The value for 'id' is not of the expected type."));
            $this->assertTrue($this->hasErrorMessage($errors, "The value for 'price' is required and was not provided."));
            $this->assertTrue($this->hasErrorMessage($errors, "The value for 'nested' is not of the expected type."));
        }
    }

    final public function testMapWithNoConstructor(): void
    {
        $entityClass = MapperTestStubWithNoConstructor::class;
        $values = [];

        $entity = $this->mapper->map($entityClass, $values);

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
            'nested' => new MapperTestStubWithNoConstructor(),
        ];

        try {
            $this->mapper->map($entityClass, $values);
        } catch (MappingException $e) {
            $errors = $e->getErrors();
            $this->assertContainsOnlyInstancesOf(MapperError::class, $errors);
            $this->assertTrue($this->hasErrorMessage($errors, 'Class "NonExistentClass" does not exist'));
        }
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
