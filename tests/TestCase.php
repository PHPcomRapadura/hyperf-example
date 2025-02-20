<?php

declare(strict_types=1);

namespace Tests;

use BackedEnum;
use FastRoute\Dispatcher;
use Hyperf\Context\Context;
use Hyperf\Di\Exception\NotFoundException;
use Hyperf\HttpMessage\Server\Request;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\HttpServer\Router\Handler;
use PHPUnit\Framework\TestCase as PHPUnit;
use Psr\Http\Message\ServerRequestInterface;

use function Hyperf\Support\make;

/**
 * @internal
 * @coversNothing
 */
class TestCase extends PHPUnit
{
    protected function tearDown(): void
    {
        parent::tearDown();
        gc_collect_cycles();
    }

    protected function assertEnumValue(BackedEnum $enum, string $value): void
    {
        $this->assertEquals($enum->value, $value);
    }

    /**
     * @template T of mixed
     * @param class-string<T> $class
     * @param array<string, mixed> $args
     *
     * @return T
     */
    protected function make(string $class, array $args = []): mixed
    {
        try {
            return make($class, $args);
        } catch (NotFoundException) {
            return $this->createMock($class);
        }
    }

    protected function contextWithParams(array $params, string $uri = '/', string $method = 'GET'): void
    {
        $request = Context::get(ServerRequestInterface::class);
        if (! $request instanceof ServerRequestInterface) {
            $request = new Request($method, $uri);
        }

        $array = [
            Dispatcher::FOUND,
            new Handler(fn () => null, $uri),
            $params,
        ];
        $value = $request->withAttribute(Dispatched::class, new Dispatched($array));
        Context::set(ServerRequestInterface::class, $value);
    }
}
