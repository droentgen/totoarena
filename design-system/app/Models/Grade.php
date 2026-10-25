<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    protected $fillable = ['name', 'level_order'];

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}
