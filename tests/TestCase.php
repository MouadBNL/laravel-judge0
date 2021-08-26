<?php

namespace Mouadbnl\Judge0\Tests;

use Mouadbnl\Judge0\Judge0ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function getPackageProviders($app): array
    {
        return [
            Judge0ServiceProvider::class
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        // $migration = include __DIR__.'/../database/migrations/create_judge0_tables.php.stub';
        // $migration->up();
    }
}
