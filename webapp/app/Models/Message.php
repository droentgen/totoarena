<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = ['tutor_session_id', 'role', 'type', 'content', 'ocr_text', 'hint_label'];

    public function tutorSession(): BelongsTo
    {
        return $this->belongsTo(TutorSession::class);
    }
}
