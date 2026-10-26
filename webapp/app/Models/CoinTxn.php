<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoinTxn extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['wallet_id', 'amount', 'reason', 'meta'];

    protected function casts(): array
    {
        return ['meta' => 'array'];
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
