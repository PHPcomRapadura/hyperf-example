<?php

declare(strict_types=1);

use App\Presentation\Action\HomeAction;
use App\Presentation\Action\GetGamedBySlugAction;
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', HomeAction::class);

Router::get('/favicon.ico', static fn () => '');

Router::post('/error', static fn () => throw new Exception('Error!'));

Router::get('/games/{slug}/example', GetGamedBySlugAction::class);
