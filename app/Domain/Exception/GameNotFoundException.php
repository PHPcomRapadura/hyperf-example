<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class GameNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Game not found', 404);
    }
}
