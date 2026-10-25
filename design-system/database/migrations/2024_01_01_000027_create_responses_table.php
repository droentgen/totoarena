<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->json('answer'); // selected key(s) / typed text
            $table->boolean('is_correct')->nullable(); // null until graded (essay/short_answer may need async grading)
            $table->unsignedInteger('time_spent_seconds');
            $table->unsignedTinyInteger('hints_used')->default(0);
            $table->unsignedInteger('xp_awarded')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
