<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Json;

use App\Domain\Collection\GameCollection;
use App\Domain\Contract\Serializer;
use App\Domain\Entity\Game;
use App\Domain\Repository\GameRepository;
use App\Infrastructure\Support\Persistence\SleekDBDatabaseFactory;
use App\Infrastructure\Support\Persistence\SleekDBSerializerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use SleekDB\Store;

class JsonGameRepository implements GameRepository
{
    private Store $database;

    private Serializer $serializer;

    /**
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        SleekDBDatabaseFactory $databaseFactory,
        SleekDBSerializerFactory $serializableFactory,
    ) {
        $this->database = $databaseFactory->createFrom('games');
        $this->serializer = $serializableFactory->createFrom(Game::class);
    }

    /**
     * @throws IOException
     * @throws JsonException
     * @throws IdNotAllowedException
     * @throws InvalidArgumentException
     */
    public function persist(Game $game): void
    {
        $this->database->insert($this->serializer->out($game));
    }

    /**
     * @throws IOException
     * @throws InvalidArgumentException
     */
    public function getGames(): GameCollection
    {
        return GameCollection::createFrom($this->database->findAll(), $this->serializer);
    }
}
