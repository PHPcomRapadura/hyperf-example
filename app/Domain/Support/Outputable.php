<?php

declare(strict_types=1);

namespace App\Domain\Support;

use App\Domain\Contract\Result;
use Hyperf\Contract\Jsonable;
use JsonException;
use JsonSerializable;

abstract class Outputable implements Result, JsonSerializable, Jsonable
{
    public function properties(): Values
    {
        return Values::createFrom([]);
    }

    final public function content(): ?Values
    {
        $values = get_object_vars($this);
        if (empty($values)) {
            return null;
        }
        return Values::createFrom($values);
    }

    final public function __toString(): string
    {
        try {
            return json_encode($this, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return sprintf('{"error": "%s"}', $e->getMessage());
        }
    }

    final public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
