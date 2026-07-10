<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->json('results')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_analyses');
    }
};
