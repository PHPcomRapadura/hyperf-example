<?php

declare(strict_types=1);

namespace App\Presentation\Action;

use App\Infrastructure\Support\Input;

readonly class HomeAction
{
    public function __invoke(Input $request): array
    {
        return [
            'method' => $request->getMethod(),
            'message' => $request->input('message', 'Kicking ass and taking names!'),
        ];
    }
}
