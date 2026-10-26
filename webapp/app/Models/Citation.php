<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Citation extends Model
{
    protected $fillable = ['study_material_id', 'sentence_ref', 'chunk_id', 'source_chunk_id', 'quote_excerpt'];

    public function studyMaterial(): BelongsTo
    {
        return $this->belongsTo(StudyMaterial::class);
    }

    public function chunk(): BelongsTo
    {
        return $this->belongsTo(Chunk::class);
    }

    public function sourceChunk(): BelongsTo
    {
        return $this->belongsTo(SourceChunk::class);
    }

    /** Whichever of chunk()/sourceChunk() is actually set — exactly one, enforced by a DB CHECK constraint */
    public function source(): Chunk|SourceChunk|null
    {
        return $this->chunk ?? $this->sourceChunk;
    }
}
