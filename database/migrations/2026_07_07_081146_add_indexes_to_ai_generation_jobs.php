<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_generation_jobs', function (Blueprint $table) {
            $table->index('status', 'idx_ai_jobs_status');
            $table->index(['status', 'updated_at'], 'idx_ai_jobs_status_updated');
            $table->index('content_id', 'idx_ai_jobs_content_id');
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->index('status', 'idx_contents_status');
        });
    }

    public function down(): void
    {
        Schema::table('ai_generation_jobs', function (Blueprint $table) {
            $table->dropIndex('idx_ai_jobs_status');
            $table->dropIndex('idx_ai_jobs_status_updated');
            $table->dropIndex('idx_ai_jobs_content_id');
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->dropIndex('idx_contents_status');
        });
    }
};
