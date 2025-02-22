<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Json;

use App\Domain\Collection\GameCollection;
use App\Domain\Entity\Game;
use App\Domain\Repository\GameRepository;
use App\Infrastructure\Support\Persistence\SleekDBFactory;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use SleekDB\Store;

class JsonGameRepository implements GameRepository
{
    private Store $database;

    /**
     * @throws InvalidConfigurationException
     * @throws IOException
     * @throws InvalidArgumentException
     */
    public function __construct(
        SleekDBFactory $sleekDBFactory
    ) {
        $this->database = $sleekDBFactory->createFrom('games');
    }

    /**
     * @throws IOException
     * @throws JsonException
     * @throws IdNotAllowedException
     * @throws InvalidArgumentException
     */
    public function persist(Game $game): void
    {
        $this->database->insert([
            'name' => $game->name,
            'slug' => $game->slug,
            'data' => $game->data,
        ]);
    }

    /**
     * @throws IOException
     * @throws InvalidArgumentException
     */
    public function getGames(): GameCollection
    {
        return GameCollection::createFrom($this->database->findAll());
    }
}
