<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('silo_blueprints', function (Blueprint $table) {
            $table->string('content_tone', 50)->default('formal')->after('content_framework');
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->string('content_tone', 50)->nullable()->after('content_framework');
        });
    }

    public function down(): void
    {
        Schema::table('silo_blueprints', function (Blueprint $table) {
            $table->dropColumn('content_tone');
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn('content_tone');
        });
    }
};
