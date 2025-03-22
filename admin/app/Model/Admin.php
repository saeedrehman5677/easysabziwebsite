<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Admin extends Authenticatable
{
    use Notifiable;

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AdminRole::class,'admin_role_id');
    }

    public function getImageFullPathAttribute(): string
    {
        $image = $this->image ?? null;
        $path = asset('public/assets/admin/img/160x160/2.png');

        if (!is_null($image) && Storage::disk('public')->exists('admin/' . $image)) {
            $path = asset('storage/app/public/admin/' . $image);
        }
        return $path;
    }

    public function getIdentityImageFullPathAttribute()
    {
        $value = $this->identity_image ?? [];
        $imageUrlArray = is_array($value) ? $value : json_decode($value, true);
        if (is_array($imageUrlArray)) {
            foreach ($imageUrlArray as $key => $item) {
                if (Storage::disk('public')->exists('admin/' . $item)) {
                    $imageUrlArray[$key] = asset('storage/app/public/admin/'. $item) ;
                } else {
                    $imageUrlArray[$key] = asset('public/assets/admin/img/160x160/2.png');
                }
            }
        }
        return $imageUrlArray;
    }
}
