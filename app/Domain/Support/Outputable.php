<?php

declare(strict_types=1);

namespace App\Domain\Support;

use Hyperf\Contract\Jsonable;
use JsonException;
use JsonSerializable;

abstract class Outputable implements JsonSerializable, Jsonable
{
    public function __toString(): string
    {
        try {
            return json_encode($this, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return sprintf('{"error": "%s"}', $e->getMessage());
        }
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
