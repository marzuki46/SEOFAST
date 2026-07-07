<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('old_url', 1024)->unique();
            $table->string('new_url', 1024);
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->boolean('active')->default(true);
            $table->unsignedInteger('hits')->default(0);
            $table->timestamps();

            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
