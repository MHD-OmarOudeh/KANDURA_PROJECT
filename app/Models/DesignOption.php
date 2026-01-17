<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ Added
use Spatie\Translatable\HasTranslations;

class DesignOption extends Model
{
    use HasTranslations, SoftDeletes; // ✅ Added SoftDeletes

    protected $fillable = [
        'name',
        'type',
        'color_code',
        'image',
    ];

    public $translatable = ['name'];

    public function designs()
    {
        return $this->belongsToMany(Design::class, 'design_design_option')
                    ->withTimestamps();
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // ✅ Helper for type display
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'color' => 'Color',
            'dome_type' => 'Dome Type',
            'fabric_type' => 'Fabric Type',
            'sleeve_type' => 'Sleeve Type',
            default => $this->type,
        };
    }
}

