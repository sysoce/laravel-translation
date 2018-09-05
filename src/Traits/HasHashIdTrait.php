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
        try {
            $static = (new static);
            if(empty($attributes['hash_id']) && !empty($attributes['locale']) && !empty($attributes['text'])) {
                $attributes['hash_id'] = $static->hash($static->getHashableString($attributes));
            }
            $model = $static->where('hash_id', $attributes['hash_id'])->first();
            if($model) return $model;
            return $static->create($attributes + $values);
        } catch (QueryException $e){
            if($e->errorInfo[0] === "23000" && $e->errorInfo[1] === 1062) {
                // handle race condition
                return $static->where($attributes)->first();
            } else {
                throw $e;
            }
        }
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
        if(empty($attributes['hash_id']) && !empty($attributes['locale']) && !empty($attributes['text'])) {
            $attributes['hash_id'] = $static->hash($static->getHashableString($attributes));
        }
        $model = $static->where('hash_id', $attributes['hash_id'])->first();
        if($model) {
            $model->fill($values);
            return $model;
        }
        return $static->create($attributes + $values);

    }

    /**
     * Get a hashable string from a set of attributes.
     *
     * @param  string  $string
     * @return string
     */
    public function getHashableString($attributes)
    {
        $string = null;
        if(!empty($this->hashableAttributes)) {
            foreach ($this->hashableAttributes as $value) {
                $string .= $attributes[$value];
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