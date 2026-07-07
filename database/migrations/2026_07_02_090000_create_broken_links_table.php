<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('broken_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->string('url', 2048);
            $table->string('url_hash', 64);
            $table->string('anchor_text', 512)->nullable();
            $table->string('link_type', 20)->default('external'); // internal / external / image
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->string('error', 255)->nullable();
            $table->boolean('is_broken')->default(false);
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();

            $table->index(['content_id', 'is_broken']);
            $table->index('checked_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broken_links');
    }
};
