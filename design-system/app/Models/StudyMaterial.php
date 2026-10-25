<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudyMaterial extends Model
{
    protected $fillable = ['topic_id', 'type', 'origin', 'content', 'version', 'published_at'];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function citations(): HasMany
    {
        return $this->hasMany(Citation::class);
    }

    public function reviewItem(): HasOne
    {
        return $this->hasOne(ReviewItem::class);
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }
}
