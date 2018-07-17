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

trait TranslationTrait
{
    /**
     * The belongsTo translation relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    abstract public function source();

    /**
     * The hasMany translations relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    abstract public function translations();

    /**
     * Returns true/false if the current translation
     * record is a source translation.
     *
     * @return bool
     */
    public function isSource()
    {
        if (!$this->getAttribute($this->getForeignKey())) {
            return true;
        }

        return false;
    }
}