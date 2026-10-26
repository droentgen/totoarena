<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = ['topic_id', 'type', 'difficulty', 'bloom', 'stem', 'options', 'correct_key', 'explanation', 'rubric', 'source'];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'rubric' => 'array',
        ];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function examTemplates(): BelongsToMany
    {
        return $this->belongsToMany(ExamTemplate::class, 'exam_template_items')->withPivot('order');
    }
}
