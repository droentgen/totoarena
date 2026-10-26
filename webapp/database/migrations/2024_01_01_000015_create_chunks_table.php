<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Laravel's schema builder has no native pgvector column type, so the
 * `embedding` column is added with a raw statement. Requires the pgvector
 * extension (`CREATE EXTENSION IF NOT EXISTS vector;`), enabled here too
 * since this is the first migration that needs it — safe to repeat,
 * Postgres no-ops if it already exists.
 *
 * 1536 dims assumes an OpenAI-compatible embedding size; if you embed with
 * a different model, change this before running in production — changing
 * a vector column's dimension after rows exist means re-embedding everything.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS vector');

        Schema::create('chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_file_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->text('content');
            $table->unsignedInteger('page_number')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE chunks ADD COLUMN embedding vector(1536)');
        DB::statement('CREATE INDEX chunks_embedding_idx ON chunks USING hnsw (embedding vector_cosine_ops)');
    }

    public function down(): void
    {
        Schema::dropIfExists('chunks');
    }
};
