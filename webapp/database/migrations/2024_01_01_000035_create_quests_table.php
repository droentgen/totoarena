<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete(); // set only for type=guardian
            $table->enum('type', ['daily', 'weekly', 'boss', 'guardian']);
            $table->string('title');
            $table->json('target'); // {kind: 'answer_streak', count: 5, topic_id: …} — shape varies by quest type
            $table->unsignedInteger('reward_xp')->default(0);
            $table->unsignedInteger('reward_toton')->default(0);
            // progress folded in here rather than a separate QuestProgress table — a quest
            // row IS already one student's instance of it, not a shared template
            $table->unsignedInteger('progress_current')->default(0);
            $table->unsignedInteger('progress_target')->default(1);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quests');
    }
};
