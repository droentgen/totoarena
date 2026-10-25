<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['key_ideas', 'summary', 'cheatsheet', 'mindmap', 'flashcard', 'example']);
            $table->enum('origin', ['school', 'external', 'mixed', 'manual']);
            $table->json('content'); // shape depends on `type` — rendered by Knowledge Hub views
            $table->unsignedSmallInteger('version')->default(1);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_materials');
    }
};
