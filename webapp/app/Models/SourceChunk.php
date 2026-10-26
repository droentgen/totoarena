<?php

namespace App\Models;

use App\Casts\Vector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SourceChunk extends Model
{
    protected $fillable = ['external_source_id', 'topic_id', 'indicator_id', 'content', 'embedding'];

    protected function casts(): array
    {
        return ['embedding' => Vector::class];
    }

    public function externalSource(): BelongsTo
    {
        return $this->belongsTo(ExternalSource::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    public function citations(): HasMany
    {
        return $this->hasMany(Citation::class);
    }
}
