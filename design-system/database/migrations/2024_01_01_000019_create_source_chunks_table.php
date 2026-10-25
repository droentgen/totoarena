<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('external_source_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('indicator_id')->nullable()->constrained()->nullOnDelete();
            $table->text('content');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE source_chunks ADD COLUMN embedding vector(1536)');
        DB::statement('CREATE INDEX source_chunks_embedding_idx ON source_chunks USING hnsw (embedding vector_cosine_ops)');
    }

    public function down(): void
    {
        Schema::dropIfExists('source_chunks');
    }
};
