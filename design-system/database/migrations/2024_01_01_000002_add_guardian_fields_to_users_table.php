<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Alters Laravel's default `users` table (guardians) — does NOT recreate
 * it. Fortify's own migration already adds two_factor_secret /
 * two_factor_recovery_codes / two_factor_confirmed_at if you publish its
 * config with `--2fa`; this migration adds them too as a fallback in case
 * you don't use that flag. Drop the two_factor_* columns here if Fortify's
 * own migration already created them (avoid a duplicate-column error).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('family_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('role', ['owner', 'co_guardian'])->default('owner');
            $table->enum('theme', ['dark', 'light', 'system'])->default('system');
            $table->string('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('family_id');
            $table->dropColumn(['role', 'theme', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at']);
        });
    }
};
