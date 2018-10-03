# Laravel Translation
This package provides a translation dictionary layer between a translation service and your Laravel application. The dictionary layer can be used to avoid cost and speed up reoccurring translations. When you translate text through a translation service the translation is saved to your database and will be used instead of the translation service next time you translate the same text.

<!--
[![Latest Version on Packagist](https://img.shields.io/packagist/v/sysoce/laravel-translation.svg?style=flat-square)](https://packagist.org/packages/sysoce/laravel-translation)
[![Build Status](https://img.shields.io/travis/sysoce/laravel-translation/master.svg?style=flat-square)](https://travis-ci.org/sysoce/laravel-translation)
[![StyleCI](https://styleci.io/repos/42480275/shield)](https://styleci.io/repos/42480275)
[![Total Downloads](https://img.shields.io/packagist/dt/sysoce/laravel-translation.svg?style=flat-square)](https://packagist.org/packages/sysoce/laravel-translation) -->

* [Installation](#installation)
* [Usage](#usage)
  * [The Client](#the-client)
  * [Using the translation dictionary layer](#using-the-translation-dictionary-layer)
* [Extending](#extending)
* [Testing](#testing)
* [Contributing](#contributing)
* [License](#license)


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

## Usage

### The Client
To use this package you need to connect to a translation service. This package provides a wrapper for Google Cloud Translate out of the box. To use this translation client you need to install the Google Cloud Translate package:
``` bash
composer require google/cloud-translate
```
Enter the path to your JSON key file obtained from the [Google Cloud Console](https://console.cloud.google.com/) in the published [`config/translation.php` config file](https://github.com/sysoce/laravel-translation/blob/master/config/translation.php) or simply specify the path in your .env file:
``` php
/*
 * If the client requires a key file, enter its path below.
 */
'keyFilePath' => env('GOOGLE_APPLICATION_CREDENTIALS', ''),
```


You can create a wrapper for other Translation services by implementing the [´Sysoce\Translation\Contracts\Client´](https://github.com/sysoce/laravel-translation/blob/master/src/Contracts/Client.php) contract.


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

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
