<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignImage extends Model
{
    protected $fillable = [
        'design_id',
        'image_path',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
