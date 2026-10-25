<?php

namespace App\Models;

use App\Casts\Vector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chunk extends Model
{
    protected $fillable = ['source_file_id', 'topic_id', 'content', 'page_number', 'embedding'];

    protected function casts(): array
    {
        return ['embedding' => Vector::class];
    }

    public function sourceFile(): BelongsTo
    {
        return $this->belongsTo(SourceFile::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function citations(): HasMany
    {
        return $this->hasMany(Citation::class);
    }
}
