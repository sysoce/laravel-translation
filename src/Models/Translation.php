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

namespace Sysoce\Translation\Models;

use Illuminate\Database\Eloquent\Model;
use Sysoce\Translation\Traits\TranslationTrait;
use Sysoce\Translation\Traits\HasHashIdTrait;

class Translation extends Model
{
    use TranslationTrait, HasHashIdTrait;

    /**
     * Define which model attributes are hashable.
     * If empty all model attributes will be hashed.
     *
     * @param array $hashableAttributes
     */
    protected $hashableAttributes = ['locale', 'text'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'hash_id'];

    /**
     * The fillable attributes.
     *
     * @var array
     */
    protected $fillable = ['source_id', 'locale', 'text'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['hash_id'];

    /**
     * Prints out the contents of the object when used as a string
     *
     * @return string
     */
    // public function __toString()
    // {

    // }

    /**
     * The belongsTo translation relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source() {
        return $this->belongsTo(Translation::class);
    }

    /**
     * The hasMany translations relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations() {
        return $this->HasMany(Translation::class, 'source_id');
    }

}
