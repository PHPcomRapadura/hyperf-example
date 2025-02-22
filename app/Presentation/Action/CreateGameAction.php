<?php

declare(strict_types=1);

namespace App\Presentation\Action;

use App\Domain\Entity\Game;
use App\Domain\Repository\GameRepository;
use App\Presentation\Input\CreateGameInput;
use App\Presentation\Support\Mapper;

readonly class CreateGameAction
{
    public function __construct(
        private Mapper $mapper,
        private GameRepository $gameQueryRepository,
    ) {
    }

    public function __invoke(CreateGameInput $input): void
    {
        $game = $this->mapper->map(Game::class, $input->values());
        $this->gameQueryRepository->persist($game);
    }
}
