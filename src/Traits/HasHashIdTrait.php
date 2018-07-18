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
            $model->attributes['hash_id'] = $model->hash($model->getHashableString($model->attributes));
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
            $model = $static->where($attributes['hash_id'])->first();
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
     * Get a hashable string from a set of attributes.
     *
     * @param  string  $string
     * @return string
     */
    public static function getHashableString($attributes)
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
        // The hash is raw binary format (with a length of 16 bytes for md5).
        return md5($string, true);
    }

}