<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = ['student_id', 'toton_balance'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function coinTxns(): HasMany
    {
        return $this->hasMany(CoinTxn::class);
    }
}
