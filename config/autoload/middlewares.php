<?php

declare(strict_types=1);

use App\Infrastructure\Http\Middleware\FormatResponseMiddleware;
use Hyperf\Validation\Middleware\ValidationMiddleware;

return [
    'http' => [
        ValidationMiddleware::class,
        FormatResponseMiddleware::class,
    ],
];
