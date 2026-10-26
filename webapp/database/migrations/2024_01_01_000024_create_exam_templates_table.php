<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->json('topic_ids'); // array of Topic ids in scope
            $table->enum('difficulty', ['D1', 'D2', 'D3', 'D4', 'D5'])->nullable(); // null when build_mode = manual_pick
            $table->unsignedSmallInteger('item_count');
            $table->unsignedSmallInteger('duration_minutes');
            $table->unsignedTinyInteger('mcq_percent')->default(100);
            $table->timestamp('due_at')->nullable();
            $table->unsignedInteger('bonus_toton')->default(0);
            $table->enum('build_mode', ['ai_auto', 'manual_pick', 'mixed'])->default('ai_auto');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_templates');
    }
};
