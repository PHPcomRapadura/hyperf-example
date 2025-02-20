<?php

declare(strict_types=1);

namespace App\Domain\Entity\Support;

use Hyperf\Contract\Jsonable;
use JsonException;
use JsonSerializable;

readonly class Entity implements JsonSerializable, Jsonable
{
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function __toString(): string
    {
        try {
            return json_encode($this, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return sprintf('{"error": "%s"}', $e->getMessage());
        }
    }
}
