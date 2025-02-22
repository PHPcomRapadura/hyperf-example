<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Infrastructure\Support\Persistence\SleekDBDatabaseFactory;
use Tests\Integration\Support\Database\Helper;
use Tests\Integration\Support\Database\Helpers\SleekDBHelper;
use Tests\TestCase;

class IntegrationTestCase extends TestCase
{
    protected Helper $sleek;

    protected array $truncate = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->sleek = new SleekDBHelper($this->make(SleekDBDatabaseFactory::class), $this);

        $this->truncate();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->truncate();
    }

    protected function truncate(): void
    {
        foreach ($this->truncate as $resource => $database) {
            match ($database) {
                'sleek' => $this->sleek->truncate($resource),
            };
        }
    }
}
