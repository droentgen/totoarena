<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamTemplate extends Model
{
    protected $fillable = [
        'family_id', 'student_id', 'created_by', 'topic_ids', 'difficulty',
        'item_count', 'duration_minutes', 'mcq_percent', 'due_at', 'bonus_toton',
        'build_mode', 'assigned_at',
    ];

    protected function casts(): array
    {
        return [
            'topic_ids' => 'array',
            'due_at' => 'datetime',
            'assigned_at' => 'datetime',
        ];
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'exam_template_items')->withPivot('order')->orderByPivot('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function isAssigned(): bool
    {
        return $this->assigned_at !== null;
    }
}
