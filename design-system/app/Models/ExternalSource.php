<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExternalSource extends Model
{
    protected $fillable = ['source_domain_id', 'url', 'title', 'publisher', 'license', 'fetched_at', 'content_hash', 'status'];

    protected function casts(): array
    {
        return ['fetched_at' => 'datetime'];
    }

    public function sourceDomain(): BelongsTo
    {
        return $this->belongsTo(SourceDomain::class);
    }

    public function sourceChunks(): HasMany
    {
        return $this->hasMany(SourceChunk::class);
    }
}
