<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Support\Http\Middleware;

use App\Infrastructure\Support\Http\Middleware\AppMiddleware;
use App\Infrastructure\Support\Presentation\Output\Output;
use FastRoute\Dispatcher;
use Hyperf\Context\ResponseContext;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\HttpServer\Router\Handler;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swow\Psr7\Message\ResponsePlusInterface;
use Swow\Psr7\Message\ServerRequestPlusInterface;
use Tests\TestCase;

class AppMiddlewareTest extends TestCase
{
    final public function testShouldRenderOutputResponse(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->willReturnCallback(fn (string $class) => match ($class) {
                default => $this->createMock($class),
            });
        $middleware = new AppMiddleware($container);

        $request = $this->createMock(ServerRequestPlusInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $response = $this->createMock(ResponsePlusInterface::class);

        ResponseContext::set($response);

        $output = new Output();

        $request->method('getAttribute')
            ->willReturn(new Dispatched([
                Dispatcher::FOUND,
                new Handler(fn () => $output, ''),
                [],
            ]));
        $middleware->process($request, $handler);
    }
}
