<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ai_generation_jobs', function (Blueprint $table) {
            $table->longText('phase_1_lsi')->nullable()->after('status');
            // phase_1_draft will be used as phase 2 draft
            // phase_2_critique will be used as phase 3 questions
            $table->longText('phase_4_answers')->nullable()->after('phase_3_expanded');
            $table->longText('phase_5_combined')->nullable()->after('phase_4_answers');
            $table->longText('phase_6_html')->nullable()->after('phase_5_combined');
        });

        // Modify ENUM to support 7 phases
        DB::statement("ALTER TABLE ai_generation_jobs MODIFY COLUMN status ENUM('pending', 'phase_1', 'phase_2', 'phase_3', 'phase_4', 'phase_5', 'phase_6', 'phase_7', 'completed', 'failed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_generation_jobs', function (Blueprint $table) {
            $table->dropColumn(['phase_1_lsi', 'phase_4_answers', 'phase_5_combined', 'phase_6_html']);
        });

        DB::statement("ALTER TABLE ai_generation_jobs MODIFY COLUMN status ENUM('pending', 'phase_1', 'phase_2', 'phase_3', 'phase_4', 'completed', 'failed') DEFAULT 'pending'");
    }
};
