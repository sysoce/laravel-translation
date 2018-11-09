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
     * Define which model attributes are hashable in the model Class.
     * If empty all model attributes will be hashed.
     *
     * @param array $hashableAttributes
     */
    // protected $hashableAttributes = [];

    /**
     * Define model event callbacks.
     * TODO: check if hashable attributes are set and either update the has or prevent update
     *
     * @return void
     */
    public static function bootHasHashIdTrait()
    {
        static::creating(function ($model) {
            $string = $model->getHashableString($model->attributes);
            $model->attributes['hash_id'] = $model->hash($string);
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
        return $static->callbackOrCreate($attributes, $values);
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
        return $static->callbackOrCreate($attributes, $values, function (& $instance) use ($values) {
            $instance->fill($values)->save();
        });
    }

    /**
     * Get the first record matching the attributes and callback or create it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function callbackOrCreate(array $attributes, array $values = [], $callback = null)
    {
        $static = (new static);
        $hashed_attr = $static->getHashedAttributes($attributes);
        $instance = $static->where($hashed_attr)->first();
        if(!is_null($instance)) {
            if(is_callable($callback)) $callback($instance);
        } else {
            $instance = $static->newModelInstance($attributes + $hashed_attr + $values);
            $instance->save();
        }
        return $instance;
    }

    /**
     * If the attributes are hashed, returns the hashed attributes
     *
     * @param  array  $attributes
     * @return string
     */
    public function getHashedAttributes($attributes)
    {
        $attributes['hash_id'] = $this->hash($this->getHashableString($attributes));
        foreach ($this->hashableAttributes as $value) {
            unset($attributes[$value]);
        }
        return $attributes;
    }

    /**
     * Get a hashable string from a set of attributes.
     * TODO: throw Exception if no hashableAttributes or empty
     *
     * @param  array  $attributes
     * @return string
     */
    public function getHashableString($attributes)
    {
        $string = null;
        foreach ($this->hashableAttributes as $value) {
            if(isset($attributes[$value])) $string .= $attributes[$value];
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