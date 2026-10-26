<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Alters Laravel's default `users` table (guardians) — does NOT recreate
 * it. two_factor_* columns are added by Fortify's own published migration
 * (2026_10_26_064853_add_two_factor_columns_to_users_table.php), which runs
 * after this one in sequence — not duplicated here.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('family_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('role', ['owner', 'co_guardian'])->default('owner');
            $table->enum('theme', ['dark', 'light', 'system'])->default('system');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('family_id');
            $table->dropColumn(['role', 'theme']);
        });
    }
};
