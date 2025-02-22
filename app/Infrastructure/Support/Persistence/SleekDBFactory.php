<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Persistence;

use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Query;
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
        $configuration = [
            'auto_cache' => true,
            'cache_lifetime' => null,
            'timeout' => null,
            'primary_key' => '_id',
            'search' => [
                'min_length' => 2,
                'mode' => 'or',
                'score_key' => 'scoreKey',
                'algorithm' => Query::SEARCH_ALGORITHM['hits'],
            ],
            'folder_permissions' => 0777,
        ];
        return new Store($resource, $path, $configuration);
    }
}
