<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
      protected $fillable = ['size'];

    public function designs()
    {
        return $this->belongsToMany(Design::class)
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
