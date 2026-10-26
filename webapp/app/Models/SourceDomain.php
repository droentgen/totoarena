<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SourceDomain extends Model
{
    protected $fillable = ['domain', 'tier', 'enabled', 'added_by'];

    protected function casts(): array
    {
        return ['enabled' => 'boolean'];
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function externalSources(): HasMany
    {
        return $this->hasMany(ExternalSource::class);
    }
}
