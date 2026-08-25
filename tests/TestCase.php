<?php

namespace Bernskiold\LaravelEuromonitor\Tests;

use Bernskiold\LaravelEuromonitor\LaravelEuromonitorServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // No test in this suite should ever reach the real Euromonitor API.
        Http::preventStrayRequests();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Bernskiold\\LaravelEuromonitor\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelEuromonitorServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
