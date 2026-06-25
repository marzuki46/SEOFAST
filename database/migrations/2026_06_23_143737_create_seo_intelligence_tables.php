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
        Schema::create('crawl_budget_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->enum('rule_type', [
                'noindex',
                'disallow',
                'sitemap_exclude',
                'crawl_delay',
                'allow',
            ]);
            $table->string('url_pattern');
            $table->string('bot_target')->default('*');
            $table->string('reason')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'rule_type', 'is_active']);
        });

        Schema::create('seo_feedback_loops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();

            // Crawl Priority
            $table->decimal('crawl_priority_score', 5, 2)->default(0);
            $table->timestamp('last_submitted_to_indexing_api_at')->nullable();
            $table->integer('indexing_api_submission_count')->default(0);

            // GSC Indexation State
            $table->string('gsc_coverage_state', 100)->nullable();
            $table->string('gsc_verdict', 20)->nullable();
            $table->string('gsc_indexing_state', 50)->nullable();
            $table->string('gsc_robots_txt_state', 50)->nullable();
            $table->string('gsc_page_fetch_state', 50)->nullable();
            $table->timestamp('gsc_last_crawl_time')->nullable();
            $table->timestamp('gsc_last_sync_at')->nullable();

            // SERP Feedback
            $table->integer('current_serp_position')->nullable();
            $table->integer('previous_serp_position')->nullable();
            $table->decimal('position_change', 5, 1)->nullable();
            $table->boolean('requires_ai_reoptimization')->default(false);
            $table->timestamp('reoptimization_triggered_at')->nullable();
            $table->integer('reoptimization_count')->default(0);

            // GSC Search Analytics (7-day rolling)
            $table->integer('avg_clicks_7d')->nullable();
            $table->integer('avg_impressions_7d')->nullable();
            $table->decimal('avg_ctr_7d', 5, 4)->nullable();
            $table->decimal('avg_position_7d', 5, 2)->nullable();

            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();

            $table->index(['content_id', 'gsc_coverage_state']);
            $table->index(['requires_ai_reoptimization', 'reoptimization_triggered_at'], 'sfl_reopt_state_idx');
        });

        Schema::create('serp_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->string('target_keyword');
            $table->string('target_country', 10)->default('ID');
            $table->string('target_device', 10)->default('desktop');
            $table->tinyInteger('position')->nullable();
            $table->string('ranking_url', 500)->nullable();
            $table->json('serp_features')->nullable();
            $table->date('snapshot_date');
            $table->timestamps();

            $table->index(['content_id', 'snapshot_date']);
            $table->index(['content_id', 'target_keyword', 'snapshot_date']);
            $table->unique(
                ['content_id', 'target_keyword', 'target_country', 'target_device', 'snapshot_date'],
                'unique_serp_snapshot'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serp_snapshots');
        Schema::dropIfExists('seo_feedback_loops');
        Schema::dropIfExists('crawl_budget_rules');
    }
};