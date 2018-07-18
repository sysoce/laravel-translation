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
            'project_id' => env('TRANSLATE_PROJECT_ID'),

            /*
             * If the client requires an API key, enter below.
             */
            'api_key' => env('TRANSLATE_API_KEY'),
        ],
    ],

];