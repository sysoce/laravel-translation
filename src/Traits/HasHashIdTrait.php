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
     * @param array $hashableAttributes
     */
    // protected $hashableAttributes = [];

    /**
     * Define model event callbacks.
     *
     * @return void
     */
    public static function bootHasHashIdTrait()
    {
        $string = '';
        static::creating(function ($model) {
            $string = $model->getHashableString($model->attributes);
            $hash = $model->hash($string);
            $model->attributes['hash_id'] = $hash;
        });
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function firstOrCreate(array $attributes, array $values = [])
    {
        $static = (new static);

        if(empty($attributes['hash_id'])) {
            $attributes['hash_id'] = $static->hash($static->getHashableString($attributes + $values));
        }

        if (! is_null($instance = $static->where('hash_id', $attributes['hash_id'])->first())) {
            return $instance;
        }
        $model = $static->newModelInstance($attributes + $values);
        $model->save();
        return $model;
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function updateOrCreate(array $attributes, array $values = [])
    {
        $static = (new static);
        if(empty($attributes['hash_id'])) {
            $attributes['hash_id'] = $static->hash($static->getHashableString($attributes + $values));
        }
        if (! is_null($instance = $static->where('hash_id', $attributes['hash_id'])->first())) {
            $instance->fill($values)->save();
            return $instance;
        } else {
            $instance = $static->newModelInstance($attributes + $values);
            $instance->save();
        }
        return $instance;
    }

    /**
     * Get a hashable string from a set of attributes.
     * TODO: throw Exception if no hashableAttributes or empty
     *
     * @param  string  $string
     * @return string
     */
    public function getHashableString($attributes)
    {
        $string = null;
        foreach ($this->hashableAttributes as $value) {
            $string .= $attributes[$value];
        }
        return $string;
    }

    /**
     * Generate a hash from a string.
     *
     * @param  string  $string
     * @return string
     */
    public static function hash($string)
    {
        // The hash is a string of hex digits (with a length of 32 bytes for md5).
        return md5($string);
    }

}