<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_campaigns', function (Blueprint $table): void {
            $table->string('destination_url', 2048)->nullable()->after('publish_status');
        });
    }

    public function down(): void
    {
        Schema::table('content_campaigns', function (Blueprint $table): void {
            $table->dropColumn('destination_url');
        });
    }
};
