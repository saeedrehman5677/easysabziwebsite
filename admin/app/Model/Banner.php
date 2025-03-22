<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    protected $casts = [
        'product_id'  => 'integer',
        'status'      => 'integer',
        'category_id' => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function getImageFullPathAttribute(): string
    {
        $image = $this->image ?? null;
        $path = asset('public/assets/admin/img/900x400/img1.jpg');

        if (!is_null($image) && Storage::disk('public')->exists('banner/' . $image)) {
            $path = asset('storage/app/public/banner/' . $image);
        }
        return $path;
    }
}
