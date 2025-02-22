<?php

declare(strict_types=1);

namespace App\Presentation\Action;

use App\Application\Service\RecoverGameService;
use App\Domain\Entity\Game;
use App\Domain\Exception\GameNotFoundException;
use App\Presentation\Input\GetGameBySlugInput;

readonly class GetGameBySlugAction
{
    public function __construct(private RecoverGameService $recoverGameService)
    {
    }

    /**
     * @throws GameNotFoundException
     */
    public function __invoke(GetGameBySlugInput $input): Game
    {
        $slug = $input->value('slug', '');
        return $this->recoverGameService->getGameBySlug($slug);
    }
}
