<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Http\Middleware;

use App\Infrastructure\Support\Presentation\OutputFormatter;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class FormatResponseMiddleware implements MiddlewareInterface
{
    use OutputFormatter;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        try {
            $data = (string) $response->getBody();
            $body = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            if (! is_array($body)) {
                return $response;
            }

            $statusCode = $response->getStatusCode();
            $payload = $this->toPayload($statusCode, $body);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withBody(new SwooleStream($payload));
        } catch (Throwable) {
            return $response;
        }
    }
}
