<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * A citation points at exactly one of chunk_id (school-uploaded content) or
 * source_chunk_id (external content) — never both, never neither. Enforced
 * with a CHECK constraint rather than two nullable FKs trusted to app code,
 * since this table backs "every sentence must be traceable to a source"
 * (07-knowledge-sourcing.md) and a bad row here is a trust/compliance bug.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_material_id')->constrained()->cascadeOnDelete();
            $table->string('sentence_ref'); // which sentence/section in the material's content JSON
            $table->foreignId('chunk_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('source_chunk_id')->nullable()->constrained()->cascadeOnDelete();
            $table->text('quote_excerpt');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE citations ADD CONSTRAINT citations_exactly_one_source CHECK (num_nonnulls(chunk_id, source_chunk_id) = 1)');
    }

    public function down(): void
    {
        Schema::dropIfExists('citations');
    }
};
