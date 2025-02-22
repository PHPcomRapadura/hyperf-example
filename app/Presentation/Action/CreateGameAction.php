<?php

declare(strict_types=1);

namespace App\Presentation\Action;

use App\Domain\Entity\Game;
use App\Domain\Repository\GameRepository;
use App\Infrastructure\Support\Adapter\Mapping\Mapper;
use App\Infrastructure\Support\Presentation\Output\NoContent;
use App\Presentation\Input\CreateGameInput;

readonly class CreateGameAction
{
    public function __construct(
        private Mapper $mapper,
        private GameRepository $gameQueryRepository,
    ) {
    }

    public function __invoke(CreateGameInput $input): NoContent
    {
        $game = $this->mapper->map(Game::class, $input->values());
        $this->gameQueryRepository->persist($game);
        return new NoContent();
    }
}
