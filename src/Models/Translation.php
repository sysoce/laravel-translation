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

class Translation extends Model
{
    use TranslationTrait, HasHashIdTrait;

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


}
