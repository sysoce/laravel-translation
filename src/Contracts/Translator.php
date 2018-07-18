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

namespace Sysoce\Translation\Contracts;

interface Translator extends Client
{
    /**
     * Constructor.
     *
     * @param Application $app
     */
    public function __construct(Client $client);
}