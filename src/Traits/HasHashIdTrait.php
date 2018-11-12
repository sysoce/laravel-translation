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
     * TODO: check if hashable attributes are set and either update the hash or prevent update
     *
     * @return void
     */
    public static function bootHasHashIdTrait()
    {
        static::creating(function ($model) {
            $model->attributes['hash_id'] = $model->generateHashId($model->attributes);
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
        $hashed_attr = $static->replaceHashableAttributes($attributes);
        $instance = $static->where($hashed_attr)->first();
        if(is_null($instance)) {
            $instance = $static->newModelInstance($attributes + $hashed_attr + $values);
            $instance->save();
        }
        return $instance;
    }

    /**
     * Returns an array of attributes where the hashable attributes are replaced by the hash_id
     *
     * @param  array  $attributes
     * @return array
     */
    public function replaceHashableAttributes($attributes)
    {
        $hash_id = $this->generateHashId($attributes);
        if($hash_id) {
            $attributes['hash_id'] = $hash_id;
            foreach ($this->hashableAttributes as $value) {
                unset($attributes[$value]);
            }
        }
        return $attributes;
    }

    /**
     * Get a hash id string from the set of hashable attributes.
     *
     * @param  array  $attributes
     * @return string | null        returns null if attributes not hashable
     */
    public function generateHashId($attributes)
    {
        $hashable_string = $this->getHashableString($attributes);
        if($hashable_string) return $this->hash($hashable_string);
        return null;
    }

    /**
     * Get a hashable string from the hashable attributes.
     *
     * @param  array  $attributes
     * @return string | null        returns null if not hashableAttributes set or not all hashableAttributes provided
     */
    public function getHashableString($attributes)
    {
        $string = null;
        if(!empty($this->hashableAttributes)) { // check if set
            foreach ($this->hashableAttributes as $value) {
                if(isset($attributes[$value])) $string .= $attributes[$value];
                else return null;
            }
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