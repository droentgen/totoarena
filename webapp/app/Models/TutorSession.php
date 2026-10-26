<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TutorSession extends Model
{
    protected $fillable = ['student_id', 'topic_id', 'hint_stage', 'attempt_count', 'started_at', 'ended_at'];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /** Hint ladder is capped at 4 steps (0-3) — step 3 is the full solver (01-system-flow.md pipeline ⑤) */
    public function canAdvanceHint(): bool
    {
        return $this->hint_stage < 3;
    }
}
