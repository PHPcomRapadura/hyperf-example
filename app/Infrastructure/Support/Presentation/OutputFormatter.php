<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Presentation;

use JsonException;

trait OutputFormatter
{
    public function toPayload(int $statusCode, array $body): string
    {
        $parsed = $this->parse($statusCode, $body);
        try {
            return json_encode($parsed, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return sprintf('{"status": "error", "message": "%s", "code": %d}', $e->getMessage(), $statusCode);
        }
    }

    private function parse(int $statusCode, array $body): array
    {
        if ($this->isSuccess($statusCode)) {
            return [
                'status' => 'success',
                'data' => $body,
            ];
        }
        if ($this->isFail($statusCode)) {
            return [
                'status' => 'fail',
                'data' => $body,
            ];
        }
        return [
            'status' => 'error',
            'message' => $body,
            'code' => $statusCode,
        ];
    }

    private function isSuccess(int $statusCode): bool
    {
        return $statusCode >= 200 && $statusCode < 300;
    }

    private function isFail(int $statusCode): bool
    {
        return $statusCode >= 400 && $statusCode < 500;
    }
}
