<?php

namespace App\Models;

// NOTE: this REPLACES the default Laravel User model (app/Models/User.php) —
// merge the two rather than overwrite blindly, since Fortify/Jetstream
// traits may already be present on whatever a fresh `laravel new` generated.

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'family_id', 'role', 'theme',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Argon2id, set via config/hashing.php per 06-registration.md
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function identities(): HasMany
    {
        return $this->hasMany(Identity::class);
    }

    public function consents(): HasMany
    {
        return $this->hasMany(Consent::class);
    }

    public function uploadedSourceFiles(): HasMany
    {
        return $this->hasMany(SourceFile::class, 'uploaded_by');
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_confirmed_at !== null;
    }
}
