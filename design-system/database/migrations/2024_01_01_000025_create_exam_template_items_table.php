<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pivot for manual_pick/mixed build modes, where the guardian attaches
 * specific Items instead of (or alongside) the Adaptive Engine picking
 * them at attempt time. Not needed for a pure ai_auto template.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_template_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['exam_template_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_template_items');
    }
};
