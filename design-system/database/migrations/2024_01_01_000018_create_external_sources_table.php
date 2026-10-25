<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_domain_id')->constrained()->cascadeOnDelete();
            $table->string('url', 2048);
            $table->string('title');
            $table->string('publisher')->nullable();
            $table->string('license')->nullable();
            $table->timestamp('fetched_at');
            $table->string('content_hash', 64); // detect when the source page changed, re-check every 30 days
            $table->enum('status', ['active', 'changed', 'unreachable'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_sources');
    }
};
