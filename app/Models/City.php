<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasTranslations;

    protected $fillable = ['name'];

    public $translatable = ['name'];
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
