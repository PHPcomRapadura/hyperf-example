<?php

declare(strict_types=1);

namespace App\Domain\Contract;

use JsonSerializable;

interface Mapper
{
    public function map(array $value): JsonSerializable;
}
