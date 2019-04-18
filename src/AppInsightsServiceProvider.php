<?php

namespace Whchi\LaravelApplicationInsights;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Whchi\LaravelApplicationInsights\AppInsights;

class AppInsightsServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configSrc = realpath($raw = __DIR__ . '/../config/appinsights.php') ?: $raw;
        $eventSrc = realpath($raw = __DIR__ . '/Events') ?: $raw;
        $listenerSrc = realpath($raw = __DIR__ . '/Listeners') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$configSrc => config_path('appinsights.php')], 'config');
            $this->publishes([$eventSrc => app_path('/Events')], 'event');
            $this->publishes([$listenerSrc => app_path('/Listeners')], 'event');
        }

        $this->mergeConfigFrom($configSrc, 'appinsights');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'AppInsights', function ($app) {
                $config = $app['config']->get('appinsights');
                return new AppInsights($config['instrumentationKey'], $config['initWithGuzzleHttpClient']);
            }
        );
    }

    public function provides()
    {
        return [
            'AppInsights',
        ];
    }
}
