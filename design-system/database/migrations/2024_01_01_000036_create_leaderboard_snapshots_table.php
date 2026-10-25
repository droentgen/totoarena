<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaderboard_snapshots', function (Blueprint $table) {
            $table->id();
            $table->enum('scope', ['weekly', 'apex']); // Ghost Rival compares a student to their own past snapshot, no row needed here
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('rank');
            $table->unsignedBigInteger('xp');
            $table->timestamp('captured_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboard_snapshots');
    }
};
