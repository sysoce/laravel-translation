<?php

namespace Sysoce\Translation\Test;

use Illuminate\Database\Eloquent\Model;
use Sysoce\Translation\Traits\TranslationTrait;
use Sysoce\Translation\Traits\HasHashIdTrait;

class TestModel extends Model
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

    /**
     * The hashable attributes to use to create a unique hash id.
     *
     * @var array
     */
    public $hashableAttributes = ['locale', 'text'];

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
        return $this->HasMany(Translation::class);
    }
}