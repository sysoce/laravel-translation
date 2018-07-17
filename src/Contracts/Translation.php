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

interface Translation
{
    /**
     * Returns the translation for the current locale.
     *
     * @param string $text
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function translate($text);
}