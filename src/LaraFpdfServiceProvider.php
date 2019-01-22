<?php

namespace TJGazel\LaraFpdf;

use Illuminate\Support\ServiceProvider;

class LaraFpdfServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('lara-fpdf.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'lara-fpdf');
        $this->app->call([$this, 'registerLaraFpdf']);
    }

    public function registerLaraFpdf()
    {
        $this->app->singleton(LaraFpdf::class, function () {
            return new LaraFpdf('lara-fpdf.orientation', 'lara-fpdf.unit', 'lara-fpdf.size');
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [LaraFpdf::class];
    }
}
