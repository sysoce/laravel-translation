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
     * The translator to be used for translations
     * The model used have to implement Sysoce\Translation\Contracts\Translator
     */
    'translator' => \Google\Cloud\Translate\TranslateClient::class,

    /*
     * The translate client to be used for translations
     */
    'translator_arguments' => [
        /*
         * Google Cloud Project ID
         */
        'project_id' => '',

        /*
         * Google Cloud Translation API Key
         */
        'api_key' => ''
    ],

    /*
     * The translation model to be used by the package.
     * The model used have to implement Sysoce\Translation\Contracts\Translation
     */
    'translator' => \\Cloud\Translate\TranslateClient::class,

];
