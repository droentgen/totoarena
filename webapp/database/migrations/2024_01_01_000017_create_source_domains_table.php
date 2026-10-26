<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique(); // e.g. "dltv.ac.th"
            $table->enum('tier', ['T1', 'T2', 'T3']);
            $table->boolean('enabled')->default(true);
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete(); // null = seeded default allowlist
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('source_domains');
    }
};
