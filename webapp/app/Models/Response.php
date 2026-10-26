<?php

namespace App\Models;

// NOTE: name collides with Illuminate\Http\Response / Symfony's Response —
// fully-qualified namespaces keep this safe, but any controller using both
// will need an alias (e.g. `use App\Models\Response as ItemResponse;`).

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Response extends Model
{
    protected $fillable = ['attempt_id', 'item_id', 'answer', 'is_correct', 'time_spent_seconds', 'hints_used', 'xp_awarded'];

    protected function casts(): array
    {
        return [
            'answer' => 'array',
            'is_correct' => 'boolean',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
