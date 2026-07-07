<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('silo_blueprints', function (Blueprint $table) {
            $table->string('content_framework', 50)->default('default')->after('silo_name');
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->string('content_framework', 50)->nullable()->after('hierarchy_level');
        });
    }

    public function down(): void
    {
        Schema::table('silo_blueprints', function (Blueprint $table) {
            $table->dropColumn('content_framework');
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn('content_framework');
        });
    }
};
