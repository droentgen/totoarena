<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('code', 16)->unique(); // the 8-char code shown + QR-encoded
            $table->timestamp('expires_at'); // 10 min, single use (06-registration.md)
            $table->timestamp('used_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->string('device_label')->nullable(); // filled in once the device actually links
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_links');
    }
};
