# laravel-translation
Provides a translation dictionary layer between a translation service and your Laravel application.

THIS PACKAGE IS IN EARLY DEVELOPMENT

<!--
[![Latest Version on Packagist](https://img.shields.io/packagist/v/sysoce/laravel-translation.svg?style=flat-square)](https://packagist.org/packages/sysoce/laravel-translation)
[![Build Status](https://img.shields.io/travis/sysoce/laravel-translation/master.svg?style=flat-square)](https://travis-ci.org/sysoce/laravel-translation)
[![StyleCI](https://styleci.io/repos/42480275/shield)](https://styleci.io/repos/42480275)
[![Total Downloads](https://img.shields.io/packagist/dt/sysoce/laravel-translation.svg?style=flat-square)](https://packagist.org/packages/sysoce/laravel-translation) -->

* [Installation](#installation)
* [Usage](#usage)
  * [Using the translation dictionary layer](#using-the-translation-dictionary-layer)
* [Extending](#extending)

This package provides a translation dictionary layer between a translation service and your Laravel application.

Once installed you can do stuff like this:

```php
// Set source language for translation
app(Translation::class)->setSource('en');
// Set target language for translation
app(Translation::class)->setTarget('ja');

// Translate text
$translation = app(Translation::class)->translate('hello');

// Print translation (outputs 'こんにちは')
echo $translation->text
```

## Installation

### Laravel

This package can be used in Laravel 5.4 or higher.

<!-- You can install the package via composer:

``` bash
composer require sysoce/laravel-translation
``` -->

In Laravel 5.5 the service provider will automatically get registered. In older versions of the framework just add the service provider in `config/app.php` file:

```php
'providers' => [
    // ...
    Sysoce\Translation\TranslationServiceProvider::class,
];
```

You can publish [the migration](https://github.com/sysoce/laravel-translation/blob/master/database/migrations/0000_00_00_000000_create_translations_table.php) with:

```bash
php artisan vendor:publish --provider="Sysoce\Translation\TranslationServiceProvider" --tag="migrations"
```

After the migration has been published you can create the translations tables by running the migrations:

```bash
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Sysoce\Translation\TranslationServiceProvider" --tag="config"
```

When published, [the `config/translation.php` config file](https://github.com/sysoce/laravel-translation/blob/master/config/translation.php) contains:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    |  The models to use for storing locales, and translations.
    |
    */

    'models' => [

        /*
        |--------------------------------------------------------------------------
        | Translation Model
        |--------------------------------------------------------------------------
        |
        |  The translation model is used for storing translations.
        |
        */

        'translation' => Sysoce\Translation\Models\Translation::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Clients
    |--------------------------------------------------------------------------
    |
    |  The services providing translation.
    */

    'clients' => [

        /*
        |--------------------------------------------------------------------------
        | Translation client
        |--------------------------------------------------------------------------
        |
        |  The translation client providing translation service, must implement
        |  Sysoce\Translation\Contracts\Client.
        |
        */

        'client' => Sysoce\Translation\Clients\GoogleCloudTranslate::class,

        /*
        |--------------------------------------------------------------------------
        | Client Arguments
        |--------------------------------------------------------------------------
        |
        |  If the client requires any configuration values, modify the array below.
        |
        */

        'config' => [

            /*
             * If the client requires a project id, enter below.
             */
            // 'project_id' => env('TRANSLATE_PROJECT_ID', ''),

            /*
             * If the client requires an API key, enter below.
             */
            // 'api_key' => env('TRANSLATE_API_KEY', ''),
        ],
    ],

];
```

## Usage

### Using the translation dictionary layer

To use the translation dictionary layer
```php
// Set source language for translation
app(Translation::class)->setSource('en');
// Set target language for translation
app(Translation::class)->setTarget('ja');

// Translate text
$translation = app(Translation::class)->translate('hello');

// Print translation (outputs 'こんにちは')
echo $translation->text
```


## Extending

If you need to EXTEND the existing `Translation` model note that:

- Your `Translation` model needs to extend the `Sysoce\Translation\Models\Translation` model

If you need to REPLACE the existing `Translation` model you need to keep the
following things in mind:

- Your `Translation` model needs to implement the `Sysoce\Translation\Traits\HasHashIdTrait` and `Sysoce\Translation\Traits\TranslationTrait` traits

Whether extending or replacing, you will need to specify your new model in the configuration. To do this you must update the `models.translation` value in the configuration file after publishing the configuration with this command:

```bash
php artisan vendor:publish --provider="Sysoce\Translation\TranslationServiceProvider" --tag="config"
```

### Testing

``` bash
./vendor/bin/phpunit
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security-related issues, please email [sysoce@gmail.com](mailto:sysoce@gmail.com) instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.