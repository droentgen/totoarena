<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasteryState extends Model
{
    protected $fillable = ['student_id', 'topic_id', 'mastery_pct'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /** Crest thresholds per 03-gamification.md (Gold/Diamond/Mythic also need a difficulty-pass check, not modeled here) */
    public function crestTier(): ?string
    {
        return match (true) {
            $this->mastery_pct >= 95 => 'mythic',
            $this->mastery_pct >= 90 => 'diamond',
            $this->mastery_pct >= 75 => 'gold',
            $this->mastery_pct >= 60 => 'silver',
            $this->mastery_pct >= 40 => 'bronze',
            default => null,
        };
    }
}
