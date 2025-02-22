<?php

declare(strict_types=1);

use App\Presentation\Action\CreateGameAction;
use App\Presentation\Action\GetGameBySlugAction;
use App\Presentation\Action\HomeAction;
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', HomeAction::class);

Router::get('/favicon.ico', static fn () => '');

Router::post('/error', static fn () => throw new Exception('Error!'));

Router::get('/games/{slug}/example', GetGameBySlugAction::class);

Router::post('/games', CreateGameAction::class);
