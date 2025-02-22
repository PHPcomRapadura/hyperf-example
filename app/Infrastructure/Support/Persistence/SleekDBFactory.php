<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Persistence;

use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Store;

class SleekDBFactory
{
    /**
     * @throws InvalidConfigurationException
     * @throws InvalidArgumentException
     * @throws IOException
     */
    public static function createFrom(string $resource): Store
    {
        $path = dirname(__DIR__, 4) . '/storage/.sleekdb';
        return new Store($resource, $path);
    }
}
