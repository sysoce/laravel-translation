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

/**
 * The Client provides the ability to dynamically translate
 * text between language pairs and lets websites and programs
 * integrate with a translation service programmatically.
 *
 */
interface Client
{
    /**
     * Set source language.
     *
     * @param string $source Language code
     *
     * @return mixed
     */
    public function setSource($source = null);

    /**
     * Set target language.
     *
     * @param string $target Language code
     *
     * @return mixed
     */
    public function setTarget($target);

    /**
     * Translate the text.
     *
     * @param string $text
     *
     * @return mixed
     */
    public function translate($text);
}