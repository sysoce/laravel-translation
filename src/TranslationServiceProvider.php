<?php

/*
 * This file is part of the sysoce/laravel-translation package.
 *
 * (c) Sysoce <sysoce@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/sysoce/laravel-translation
 */

namespace Sysoce\Translation;

use Illuminate\Support\ServiceProvider;
use Sysoce\Translation\Contracts\Client as ClientContract;
use Sysoce\Translation\Contracts\Translation as TranslationContract;
use Sysoce\Translation\Translation;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/translation.php' => config_path('translation.php'),
            ], 'config');

            /*
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'translation');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/translation'),
            ], 'views');
            */

            $this->loadMigrationsFrom(__DIR__.'/database/migrations/2018_07_27_000000_create_translations_table.php');

            if (! class_exists('CreateTranslationsTable')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../database/migrations/2018_07_27_000000_create_translations_table.php' => database_path('migrations/'.$timestamp.'_create_translations_table.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/translation.php', 'translation');

        $this->app->bind(ClientContract::class, $this->app->config['translation.clients.client']);

        // $this->app->singleton(ClientContract::class, function ($app) {
        //     return new $this->app->config['translation.clients.client']();
        // });

        $this->app->bind(TranslationContract::class, $this->app->config['translation.models.translation']);

        $this->app->singleton(Translation::class, function ($app) {
            return new Translation(new $this->app->config['translation.clients.client']($app));
        });
    }
}