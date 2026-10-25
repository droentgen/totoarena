<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('type'); // 'login' | 'open_unit' | 'answer_item' | 'request_hint' | 'logout' | …
            $table->json('payload')->nullable();
            $table->timestamp('created_at');
        });

        // Analytics (02-architecture.md "Analytics Service") queries this heavily by
        // student+time — an append-only event log needs this index from day one.
        Schema::table('events', function (Blueprint $table) {
            $table->index(['student_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
