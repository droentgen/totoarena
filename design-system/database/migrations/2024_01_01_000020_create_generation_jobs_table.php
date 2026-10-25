<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generation_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->enum('mode', ['A', 'B', 'C', 'D', 'E']); // E = manual, see 07-knowledge-sourcing.md
            $table->json('scope'); // {subject_id, grade_id, term_id, unit_id, topic_id, source_url?}
            $table->enum('status', ['queued', 'running', 'done', 'failed'])->default('queued');
            $table->unsignedInteger('step_index')->default(0); // progress through the pipeline (search/fetch/synthesize/fact-check)
            $table->decimal('cost', 8, 4)->nullable(); // API cost in USD, for the monthly quota
            $table->foreignId('study_material_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generation_jobs');
    }
};
