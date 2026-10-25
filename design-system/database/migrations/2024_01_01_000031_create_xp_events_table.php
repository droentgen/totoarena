<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xp_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('source'); // 'item_correct' | 'tutor_solved' | 'flashcard_deck' | 'essay_submitted' | 'quest' | …
            $table->integer('amount'); // can be reduced (e.g. repeat-item 20%), never negative in practice
            $table->json('meta')->nullable(); // breakdown shown to the student ("25 XP (D3) +50% ครั้งแรก · COMBO ×1.2")
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xp_events');
    }
};
