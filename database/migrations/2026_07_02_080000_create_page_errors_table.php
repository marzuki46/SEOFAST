<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_errors', function (Blueprint $table) {
            $table->id();
            $table->string('url', 2048);
            $table->string('url_hash', 64)->unique();
            $table->string('referer', 2048)->nullable();
            $table->integer('count')->default(1);
            $table->timestamp('first_seen')->useCurrent();
            $table->timestamp('last_seen')->useCurrent();
            $table->timestamps();

            $table->index('last_seen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_errors');
    }
};
