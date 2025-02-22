<?php

declare(strict_types=1);

use App\Domain\Repository\GameRepository;
use App\Infrastructure\Repository\Memory\JsonGameRepository;
use App\Infrastructure\Support\Logging\EnvironmentLoggerFactory;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function Hyperf\Support\env;
use function Util\Type\Cast\toString;

return [
    LoggerInterface::class => static function (ContainerInterface $container) {
        return $container->get(EnvironmentLoggerFactory::class)->make(toString(env('APP_ENV')));
    },
    GameRepository::class => JsonGameRepository::class,
];
