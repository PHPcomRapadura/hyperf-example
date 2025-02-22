<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Persistence;

use App\Infrastructure\Support\Adapter\Mapping\Mapper;
use App\Infrastructure\Support\Adapter\Serializer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SleekDBSerializerFactory
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return Serializer
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createFrom(string $class): Serializer
    {
        return new Serializer($this->container->get(Mapper::class), $class);
    }
}
