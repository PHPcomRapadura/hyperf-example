<?php

declare(strict_types=1);

namespace App\Presentation\Action;

use App\Presentation\Input\HomeActionInput;

readonly class HomeAction
{
    public function __invoke(HomeActionInput $input): array
    {
        return [
            'method' => $input->getMethod(),
            'message' => $input->value('message', 'Kicking ass and taking names!'),
        ];
    }
}
