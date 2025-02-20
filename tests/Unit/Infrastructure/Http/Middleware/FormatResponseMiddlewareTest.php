<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Http\Middleware;

use App\Infrastructure\Http\Middleware\FormatResponseMiddleware;
use Hyperf\HttpMessage\Server\Request;
use Hyperf\HttpMessage\Server\Response;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\TestCase;

class FormatResponseMiddlewareTest extends TestCase
{
    public function testShouldFormatResponse(): void
    {
        $middleware = new FormatResponseMiddleware();
        $request = new Request('GET', '/');

        $response = (new Response())
            ->withStatus(200)
            ->withBody(new SwooleStream(json_encode(['key' => 'value'], JSON_THROW_ON_ERROR)));

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')
            ->willReturn($response);

        $result = $middleware->process($request, $handler);

        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => 'success', 'data' => ['key' => 'value']], JSON_THROW_ON_ERROR),
            (string) $result->getBody()
        );
    }

    public function testShouldReturnOriginalResponseOnNonArrayBody(): void
    {
        $middleware = new FormatResponseMiddleware();
        $request = new Request('GET', '/');

        $response = (new Response())
            ->withStatus(200)
            ->withBody(new SwooleStream(json_encode('Not an array', JSON_THROW_ON_ERROR)));

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')
            ->willReturn($response);

        $result = $middleware->process($request, $handler);

        $this->assertEquals('"Not an array"', (string) $result->getBody());
    }

    public function testShouldReturnOriginalResponseOnJsonDecodeFailure(): void
    {
        $middleware = new FormatResponseMiddleware();
        $request = new Request('GET', '/');

        $response = (new Response())
            ->withStatus(200)
            ->withBody(new SwooleStream('Invalid JSON'));

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')
            ->willReturn($response);

        $result = $middleware->process($request, $handler);

        $this->assertEquals('Invalid JSON', (string) $result->getBody());
    }
}
