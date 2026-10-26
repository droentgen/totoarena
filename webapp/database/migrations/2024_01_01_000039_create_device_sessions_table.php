<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Named `device_sessions`, not `sessions` — Laravel's own session driver
 * already owns a `sessions` table (framework-level, in the default
 * skeleton). This one is the parent-visible login/activity log
 * ("Activity: Session" in 02-architecture.md), a different concept.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete(); // set when it's a guardian login instead
            $table->string('device')->nullable(); // "iPad ห้องนั่งเล่น", "MacBook (เครื่องนี้)", …
            $table->string('ip', 45)->nullable();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_sessions');
    }
};
