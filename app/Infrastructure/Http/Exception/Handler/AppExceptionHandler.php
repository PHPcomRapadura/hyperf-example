<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Exception\Handler;

use App\Infrastructure\Support\OutputFormatter;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    use OutputFormatter;

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function handle(Throwable $throwable, ResponseInterface $response): MessageInterface|ResponseInterface
    {
        $message = sprintf(
            '[app.error] "%s" in `%s` at `%s`',
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine()
        );
        $context = [
            'message' => $throwable->getMessage(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'code' => $throwable->getCode(),
            'kind' => $throwable::class,
            'trace' => $throwable->getTraceAsString(),
        ];

        $this->logger->error($message, $context);

        $statusCode = $this->extractCode($throwable);

        return $response->withStatus($statusCode)
            ->withBody(new SwooleStream($this->toPayload($statusCode, $context)));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }

    private function extractCode(Throwable $throwable): int
    {
        $code = $throwable->getCode();
        if ($code >= 400 && $code < 500) {
            return $code;
        }
        return 500;
    }
}
