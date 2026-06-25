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
        Schema::dropIfExists('ai_reoptimization_queue');
        Schema::dropIfExists('ai_generation_jobs');
        Schema::dropIfExists('gsc_search_analytics');
        Schema::dropIfExists('gsc_url_inspections');
        Schema::dropIfExists('gsc_sync_logs');

        Schema::create('gsc_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->enum('sync_type', [
                'url_inspection',
                'search_analytics',
                'sitemap_submission',
                'batch_indexing_api',
            ]);
            $table->enum('status', ['running', 'completed', 'failed', 'partial']);
            $table->integer('total_urls')->default(0);
            $table->integer('processed_urls')->default(0);
            $table->integer('failed_urls')->default(0);
            $table->json('error_summary')->nullable();
            $table->integer('api_quota_used')->default(0);
            $table->decimal('duration_seconds', 8, 2)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'sync_type', 'status']);
            $table->index(['tenant_id', 'started_at']);
        });

        Schema::create('gsc_url_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gsc_sync_log_id')->constrained()->cascadeOnDelete();

            $table->string('verdict', 20)->nullable();
            $table->string('coverage_state', 100)->nullable();
            $table->string('robots_txt_state', 50)->nullable();
            $table->string('indexing_state', 50)->nullable();
            $table->string('page_fetch_state', 50)->nullable();
            $table->boolean('crawled_as_mobile')->nullable();
            $table->timestamp('last_crawl_time')->nullable();
            $table->string('canonical_declared_in_page')->nullable();
            $table->string('canonical_selected_by_google')->nullable();

            $table->string('mobile_usability_verdict', 20)->nullable();
            $table->json('mobile_usability_issues')->nullable();

            $table->string('rich_results_verdict', 20)->nullable();
            $table->json('rich_results_items')->nullable();

            $table->json('raw_api_response')->nullable();

            $table->timestamp('inspected_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'content_id', 'inspected_at']);
            $table->index(['tenant_id', 'verdict']);
            $table->index(['tenant_id', 'coverage_state']);
            $table->index(['content_id', 'inspected_at']);
        });

        Schema::create('gsc_search_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();

            $table->string('query');
            $table->string('page_url', 500);
            $table->string('country', 10);
            $table->string('device', 10);
            $table->date('data_date');

            $table->integer('clicks')->default(0);
            $table->integer('impressions')->default(0);
            $table->decimal('ctr', 7, 6)->default(0);
            $table->decimal('position', 6, 2)->default(0);

            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['tenant_id', 'content_id', 'query', 'country', 'device', 'data_date'],
                'unique_gsc_analytics_row'
            );
            $table->index(['tenant_id', 'content_id', 'data_date']);
            $table->index(['tenant_id', 'query', 'data_date']);
            $table->index(['tenant_id', 'data_date']);
            $table->index(['content_id', 'data_date']);
        });

        Schema::create('ai_generation_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->enum('job_type', ['initial_generation', 'reoptimization', 'freshness_update']);
            $table->enum('status', ['pending', 'phase_1', 'phase_2', 'phase_3', 'phase_4', 'completed', 'failed']);
            
            $table->longText('phase_1_draft')->nullable();
            $table->json('phase_2_critique')->nullable();
            $table->longText('phase_3_expanded')->nullable();
            $table->longText('phase_4_final')->nullable();

            $table->integer('tokens_used')->default(0);
            $table->string('llm_model_used')->nullable();
            $table->decimal('generation_cost_usd', 10, 6)->nullable();
            $table->json('error_log')->nullable();
            $table->integer('retry_count')->default(0);
            
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['content_id', 'status']);
            $table->index(['job_type', 'status']);
        });

        Schema::create('ai_reoptimization_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->enum('trigger_reason', [
                'ranking_dropped',
                'cqi_degraded',
                'not_indexed',
                'ctr_low',
                'manual_trigger',
            ]);
            $table->integer('position_before')->nullable();
            $table->integer('position_after')->nullable();
            $table->json('optimization_directives')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'skipped']);
            $table->integer('priority')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'priority', 'scheduled_at']);
            $table->index('content_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_reoptimization_queue');
        Schema::dropIfExists('ai_generation_jobs');
        Schema::dropIfExists('gsc_search_analytics');
        Schema::dropIfExists('gsc_url_inspections');
        Schema::dropIfExists('gsc_sync_logs');
    }
};
