<?php

namespace App\Models;

// NOTE: name collides with PHP's base `Event` concept in some frameworks
// (not Laravel's — Laravel's event system uses listener classes, not an
// `Event` model), but worth double-checking imports if anything else in
// the app defines a facade/alias named `Event`.

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    public const UPDATED_AT = null; // append-only log

    protected $fillable = ['student_id', 'user_id', 'type', 'payload'];

    protected function casts(): array
    {
        return ['payload' => 'array'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
