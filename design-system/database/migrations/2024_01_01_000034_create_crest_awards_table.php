<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crest_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->enum('tier', ['bronze', 'silver', 'gold', 'diamond', 'mythic']);
            $table->timestamp('awarded_at');
            $table->timestamps();

            $table->unique(['student_id', 'topic_id', 'tier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crest_awards');
    }
};
