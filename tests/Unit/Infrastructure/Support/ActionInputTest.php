<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Support;

use Tests\TestCase;

class ActionInputTest extends TestCase
{
    final public function testShouldAuthorize(): void
    {
        $input = $this->make(ActionInputTestStub::class);

        $this->assertTrue($input->authorize());
    }

    final public function testRules(): void
    {
        $input = $this->make(ActionInputTestStub::class);
        $rules = $input->rules();

        $this->assertArrayHasKey('test', $rules);
        $this->assertEquals('sometimes|string', $rules['test']);
    }

    final public function testShouldGetValueFromData(): void
    {
        $data = ['datum' => 'cool'];

        $input = $this->input(class: ActionInputTestStub::class, data: $data);

        $this->assertEquals('cool', $input->value('datum'));
    }

    final public function testShouldGetValueFromParams(): void
    {
        $params = ['param' => 'cool'];

        $input = $this->input(class: ActionInputTestStub::class, params: $params);

        $this->assertEquals('cool', $input->value('param'));
    }

    final public function testShouldCallValueBehindPost(): void
    {
        $input = $this->make(ActionInputTestStub::class, ['values' => ['test' => 'cool']]);
        $this->assertEquals('cool', $input->post('test'));
    }

    final public function testShouldCallValueBehindInput(): void
    {
        $input = $this->make(ActionInputTestStub::class, ['values' => ['test' => 'cool']]);
        $this->assertEquals('cool', $input->input('test'));
    }
}
