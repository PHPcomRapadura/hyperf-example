<?php

declare(strict_types=1);

namespace App\Domain\Contract;

interface Serializer
{
    public function in(mixed $datum): mixed;

    public function out(mixed $mapped): array;
}
