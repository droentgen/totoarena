<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Indicator extends Model
{
    protected $fillable = ['topic_id', 'code', 'description'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
