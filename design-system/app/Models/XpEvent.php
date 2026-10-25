<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XpEvent extends Model
{
    public const UPDATED_AT = null; // append-only log, no updated_at

    protected $fillable = ['student_id', 'source', 'amount', 'meta'];

    protected function casts(): array
    {
        return ['meta' => 'array'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
