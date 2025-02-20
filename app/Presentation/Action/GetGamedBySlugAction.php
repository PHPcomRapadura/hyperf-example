<?php

declare(strict_types=1);

namespace App\Presentation\Action;

use App\Application\Service\RecoverGameService;
use App\Domain\Entity\Game;
use App\Domain\Exception\GameNotFoundException;
use App\Presentation\Input\GetGameBySlugInput;

use function Util\Type\Cast\toString;

readonly class GetGamedBySlugAction
{
    public function __construct(private RecoverGameService $recoverGameService)
    {
    }

    /**
     * @throws GameNotFoundException
     */
    public function __invoke(GetGameBySlugInput $getGameBySlugInput): Game
    {
        $sms = toString($getGameBySlugInput->route('slug'));
        return $this->recoverGameService->getGameBySlug($sms);
    }
}
