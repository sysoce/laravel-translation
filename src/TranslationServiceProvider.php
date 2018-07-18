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
use App\Contracts\Translator as TranslatorContract;
use App\Contracts\Translation as TranslationContract;

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

            $this->loadMigrationsFrom(__DIR__.'/database/migrations/create_translations_table.php');

            if (! class_exists('CreateTranslationsTable')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../database/migrations/create_translations_table.php' => database_path('migrations/'.$timestamp.'_create_translations_table.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'translation');

         $this->app->bind(TranslatorContract::class, $this->app->config['translation.clients.client']);

        $this->app->singleton(TranslatorContract::class, function ($app) {
            return new new $this->app->config['translation.clients.client']();
        });

        $this->app->bind(TranslatorContract::class, $this->app->config['translation.clients.client']);

        $this->app->bind(TranslationContract::class, $this->app->config['translation.translation']);
    }
}