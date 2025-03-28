<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class Translation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'translationable_type',
        'translationable_id',
        'locale',
        'key',
        'value',
    ];

    public function translationable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
