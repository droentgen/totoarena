<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_session_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['student', 'toto']);
            $table->enum('type', ['text', 'image'])->default('text');
            $table->text('content');
            $table->text('ocr_text')->nullable();
            $table->string('hint_label')->nullable(); // e.g. "คำใบ้ขั้นที่ 1 · ถามนำ"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
