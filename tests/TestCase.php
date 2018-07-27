<?php

namespace Sysoce\Translation\Test;

use File;
use Orchestra\Testbench\TestCase as Orchestra;
use Sysoce\Translation\TranslationServiceProvider;
use Faker\Factory;

abstract class TestCase extends Orchestra
{
    /** @var \Faker\Factory */
    protected $faker;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->faker = \Faker\Factory::create();
    }

    protected function getPackageProviders($app)
    {
        return [TranslationServiceProvider::class];
    }

    /**
     * Returns the package aliases.
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        // return ['Translation' => \Sysoce\Translation\Facades\Translation::class];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->initializeDirectory($this->getTempDirectory());
        file_put_contents($this->getTempDirectory().'/.gitignore', '*'.PHP_EOL.'!.gitignore');

        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'testbench'),
            'username' => env('DB_USERNAME', 'testbench'),
            'password' => env('DB_PASSWORD', 'testbench'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        // $app['config']->set('translation.clients.config', []);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        // file_put_contents($this->getTempDirectory().'/database.sqlite', null);

        // call migrations specific to our tests, e.g. to seed the db
        // the path option should be relative to the 'path.database'
        // path unless `--path` option is available.
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__.'/../database/migrations'),
        ]);

        $this->artisan('migrate', ['--database' => 'testbench']);
    }

    protected function initializeDirectory($directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
        File::makeDirectory($directory);
    }

    public function getTempDirectory() : string
    {
        return __DIR__.'/temp';
    }
}