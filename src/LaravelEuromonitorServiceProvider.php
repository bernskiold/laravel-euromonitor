<?php

namespace Bernskiold\LaravelEuromonitor;

use Bernskiold\LaravelEuromonitor\Api\Euromonitor;
use Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient;
use Bernskiold\LaravelEuromonitor\Commands\PurgeDownloadedFilesCommand;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

use function config;

class LaravelEuromonitorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        AboutCommand::add('Laravel Euromonitor', fn () => ['Version' => '1.0.0']);

        RateLimiter::for('euromonitor-download', function() {
            return Limit::perMinutes(5, 20);
        });

        RateLimiter::for('euromonitor-extraction', function() {
            return Limit::perHour(20);
        });

        RateLimiter::for('euromonitor-data', function() {
            return Limit::perMinutes(15, 240);
        });

        $this->publishes([
            __DIR__.'/../config/euromonitor.php' => config_path('euromonitor.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                PurgeDownloadedFilesCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/euromonitor.php', 'euromonitor'
        );

        $this->app->bind(EuromonitorApiClient::class, function () {
            return EuromonitorApiClient::fromConfig(config('euromonitor.api'));
        });

        $this->app->alias(Euromonitor::class, 'laravel-euromonitor');
    }
}
