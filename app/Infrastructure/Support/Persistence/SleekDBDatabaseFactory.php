<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Persistence;

use Hyperf\Contract\ConfigInterface;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Store;

use function Util\Type\Cast\toArray;

class SleekDBDatabaseFactory
{
    public function __construct(private readonly ConfigInterface $config)
    {
    }

    /**
     * @throws InvalidConfigurationException
     * @throws InvalidArgumentException
     * @throws IOException
     */
    public function createFrom(string $resource): Store
    {
        $path = dirname(__DIR__, 4) . '/storage/.sleekdb';
        $configuration = toArray($this->config->get('databases.sleek'));
        return new Store($resource, $path, $configuration);
    }
}
