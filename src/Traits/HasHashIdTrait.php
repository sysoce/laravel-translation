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

namespace Sysoce\Translation\Traits;

trait HasHashIdTrait
{
    /**
     * Define which model attributes are hashable.
     * If empty all model attributes will be hashed.
     *
     * @param array
     */
    $hashableAttributes = [];

    /**
     * Define model event callbacks.
     *
     * @return void
     */
    public static function bootHasHashIdTrait()
    {
        $string = '';
        static::creating(function ($model) {
            if(empty($this->hashableAttributes)) {
                $string = implode($model->attributes);
            } else {
                foreach ($this->hashableAttributes as $value) {
                    $string .= $model->attributes[$value];
                }
            }
            $model->attributes['hash_id'] = self::hash($string);
        });
    }

    /**
     * Generate a hash from a string.
     *
     * @param  string  $string
     * @return string
     */
    public static function hash($string)
    {
        // The hash is raw binary format (with a length of 16 bytes for md5).
        return md5($string, true);
    }

}