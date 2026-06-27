<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->string('slug', 255)->change();
            $table->string('meta_title', 255)->nullable()->change();
            $table->text('meta_description')->nullable()->change();
            $table->longText('body_raw')->nullable()->change();
            $table->string('featured_image_alt', 255)->nullable()->change();
            $table->text('featured_image_caption')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->json('slug')->change();
            $table->json('meta_title')->nullable()->change();
            $table->json('meta_description')->nullable()->change();
            $table->json('body_raw')->nullable()->change();
            $table->json('featured_image_alt')->nullable()->change();
            $table->json('featured_image_caption')->nullable()->change();
        });
    }
};
