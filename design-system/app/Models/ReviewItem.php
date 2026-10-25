<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewItem extends Model
{
    protected $fillable = ['study_material_id', 'flags', 'decision', 'reviewer_id', 'decided_at'];

    protected function casts(): array
    {
        return [
            'flags' => 'array',
            'decided_at' => 'datetime',
        ];
    }

    public function studyMaterial(): BelongsTo
    {
        return $this->belongsTo(StudyMaterial::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
