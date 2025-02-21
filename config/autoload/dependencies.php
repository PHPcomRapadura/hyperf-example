<?php

declare(strict_types=1);

use App\Domain\Repository\GameQueryRepository;
use App\Infrastructure\Logging\EnvironmentLoggerFactory;
use App\Infrastructure\Repository\Memory\StaticGameQueryRepository;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function Hyperf\Support\env;

return [
    LoggerInterface::class => static function (ContainerInterface $container) {
        return $container->get(EnvironmentLoggerFactory::class)->make(env('APP_ENV'));
    },
    GameQueryRepository::class => StaticGameQueryRepository::class,
];
