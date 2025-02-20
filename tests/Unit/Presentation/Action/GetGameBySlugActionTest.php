<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation\Action;

use App\Application\Service\RecoverGameService;
use App\Domain\Entity\Game;
use App\Domain\Exception\GameNotFoundException;
use App\Presentation\Action\GetGamedBySlugAction;
use App\Presentation\Input\GetGameBySlugInput;
use Tests\TestCase;

class GetGameBySlugActionTest extends TestCase
{
    public function testGetGameBySlugReturnsGameWhenSlugMatches(): void
    {
        $slug = 'xpto';
        $lead = new Game(name: 'Cool game', slug: $slug);

        $input = $this->createMock(GetGameBySlugInput::class);
        $input->method('route')
            ->with('slug')
            ->willReturn($slug);

        $service = $this->createMock(RecoverGameService::class);
        $service->method('getGameBySlug')
            ->with($slug)
            ->willReturn($lead);

        $action = new GetGamedBySlugAction($service);
        $result = $action($input);

        $this->assertSame($lead, $result);
    }

    public function testGetGameBySlugThrowsExceptionWhenSlugNotFound(): void
    {
        $this->expectException(GameNotFoundException::class);

        $sms = 'xpto';

        $input = $this->createMock(GetGameBySlugInput::class);
        $input->method('route')
            ->with('slug')
            ->willReturn($sms);

        $service = $this->createMock(RecoverGameService::class);
        $service->method('getGameBySlug')
            ->with($sms)
            ->willThrowException(new GameNotFoundException());

        $action = new GetGamedBySlugAction($service);
        $action($input);
    }
}
