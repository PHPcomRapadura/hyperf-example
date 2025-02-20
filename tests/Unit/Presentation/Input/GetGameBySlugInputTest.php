<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation\Input;

use App\Presentation\Input\GetGameBySlugInput;
use FastRoute\Dispatcher;
use Hyperf\Context\Context;
use Hyperf\HttpMessage\Server\Request;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\HttpServer\Router\Handler;
use Psr\Http\Message\ServerRequestInterface;
use Tests\TestCase;

class GetGameBySlugInputTest extends TestCase
{
    public function testRules(): void
    {
        $input = $this->make(GetGameBySlugInput::class);
        $rules = $input->rules();

        $this->assertArrayHasKey('slug', $rules);
        $this->assertEquals('required|string', $rules['slug']);
    }

    public function testValidationData(): void
    {
        $params = ['slug' => 'xpto'];
        $uri = '/';

        $this->contextWithParams($params, $uri);

        $input = $this->make(GetGameBySlugInput::class);
        $validated = $input->validated();

        $this->assertArrayHasKey('slug', $validated);
    }
}
