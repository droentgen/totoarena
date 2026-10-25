<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $fillable = [
        'family_id', 'nickname', 'grade', 'track', 'school', 'avatar',
        'pin_hash', 'daily_limit_minutes', 'curfew_start', 'curfew_end', 'theme',
        'school_google_email',
    ];

    protected $hidden = ['pin_hash'];

    protected function casts(): array
    {
        return [
            'pin_hash' => 'hashed',
            'pin_locked_until' => 'datetime',
            'last_active_on' => 'date',
        ];
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function deviceLinks(): HasMany
    {
        return $this->hasMany(DeviceLink::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function xpEvents(): HasMany
    {
        return $this->hasMany(XpEvent::class);
    }

    public function masteryStates(): HasMany
    {
        return $this->hasMany(MasteryState::class);
    }

    public function crestAwards(): HasMany
    {
        return $this->hasMany(CrestAward::class);
    }

    public function quests(): HasMany
    {
        return $this->hasMany(Quest::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function tutorSessions(): HasMany
    {
        return $this->hasMany(TutorSession::class);
    }

    public function examTemplates(): HasMany
    {
        return $this->hasMany(ExamTemplate::class);
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(Redemption::class);
    }

    public function isPinLocked(): bool
    {
        return $this->pin_locked_until !== null && $this->pin_locked_until->isFuture();
    }

    /** XP needed to go from current `level` to the next — 03-gamification.md: 120 + 30*(level-1) */
    public function xpToNextLevel(): int
    {
        return 120 + 30 * ($this->level - 1);
    }

    /**
     * Novice Learner / TOTO Challenger / TOTO Vanguard / TOTO Grandmaster.
     * SIMPLIFIED: 03-gamification.md actually gates each tier behind a
     * Promotion Trial pass, not level alone — "เลเวลถึงแล้วแต่ยังไม่ผ่าน
     * Trial จะติดอยู่ที่ tier เดิม". This needs a `tier` column tracking
     * the last *confirmed* tier once Promotion Trials are implemented;
     * level-band-only is a placeholder until then.
     */
    public function tierLabel(): string
    {
        return match (true) {
            $this->level >= 45 => 'TOTO Grandmaster',
            $this->level >= 30 => 'TOTO Vanguard',
            $this->level >= 15 => 'TOTO Challenger',
            default => 'Novice Learner',
        };
    }
}
