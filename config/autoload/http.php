<?php

use App\Infrastructure\Support\Presentation\Output\NoContent;

return [
    'result' => [
        NoContent::class => [
            'status' => 204,
        ],
    ],
];
