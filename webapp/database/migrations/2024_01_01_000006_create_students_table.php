<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained()->cascadeOnDelete();
            $table->string('nickname');
            $table->string('grade'); // ม.1 … ม.6
            $table->string('track')->nullable(); // สายการเรียน, ม.ปลาย only
            $table->string('school')->nullable();
            $table->string('avatar')->nullable();
            $table->string('pin_hash');
            $table->timestamp('pin_locked_until')->nullable(); // 5 wrong attempts → 15 min lock
            $table->unsignedTinyInteger('pin_fail_count')->default(0);
            $table->unsignedSmallInteger('daily_limit_minutes')->nullable();
            $table->time('curfew_start')->nullable(); // e.g. 22:00
            $table->time('curfew_end')->nullable();
            $table->enum('theme', ['dark', 'light', 'system'])->default('dark');
            $table->string('school_google_email')->nullable()->unique();
            // denormalized game state for fast reads (see 03-gamification.md formula) —
            // XPEvent remains the source of truth; these are a cache kept in sync on write.
            $table->unsignedInteger('level')->default(1);
            $table->unsignedBigInteger('total_xp')->default(0);
            $table->unsignedInteger('streak_days')->default(0);
            $table->date('last_active_on')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
