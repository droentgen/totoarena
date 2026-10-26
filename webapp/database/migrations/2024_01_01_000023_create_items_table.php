<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['mcq', 'fill_blank', 'matching', 'short_answer', 'essay']);
            $table->enum('difficulty', ['D1', 'D2', 'D3', 'D4', 'D5']);
            $table->string('bloom'); // จำ/เข้าใจ, ประยุกต์, วิเคราะห์, … (03-gamification.md D1–D5 table)
            $table->text('stem');
            $table->json('options')->nullable(); // mcq/matching choices
            $table->string('correct_key')->nullable(); // mcq/fill_blank answer key
            $table->text('explanation')->nullable();
            $table->json('rubric')->nullable(); // short_answer/essay scoring criteria
            $table->enum('source', ['ai_generated', 'manual'])->default('ai_generated');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
