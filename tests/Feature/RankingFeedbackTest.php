<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\GscUrlInspection;
use App\Models\SeoFeedbackLoop;
use App\Models\AiReoptimizationQueue;
use App\Models\Tenant;
use App\Models\GscSyncLog;
use App\Services\Seo\RankingFeedbackService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\Gsc\SubmitToIndexingApiJob;
use Tests\TestCase;

class RankingFeedbackTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected Content $content;
    protected GscSyncLog $syncLog;
    protected RankingFeedbackService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'domain' => 'test-tenant.test',
            'subscription_plan' => 'pro',
            'is_active' => true,
        ]);

        $silo = \App\Models\SiloBlueprint::create([
            'tenant_id' => $this->tenant->id,
            'silo_name' => 'SEO Strategies',
            'seed_keyword' => 'seo guide',
            'target_language' => 'en',
            'target_country' => 'US',
            'total_contents' => 10,
            'published_contents' => 2,
        ]);

        $this->content = Content::create([
            'tenant_id' => $this->tenant->id,
            'silo_blueprint_id' => $silo->id,
            'target_keyword' => 'test keyword',
            'slug' => 'test-post',
            'hierarchy_level' => 'pillar',
            'status' => 'published',
            'published_at' => now()->subDays(15), // published 15 days ago (> 14 days)
        ]);

        $this->syncLog = GscSyncLog::create([
            'tenant_id' => $this->tenant->id,
            'sync_type' => 'url_inspection',
            'status' => 'running',
            'started_at' => now(),
        ]);

        $this->service = new RankingFeedbackService();
    }

    public function test_does_nothing_on_indexed_url(): void
    {
        $inspection = GscUrlInspection::create([
            'tenant_id' => $this->tenant->id,
            'content_id' => $this->content->id,
            'gsc_sync_log_id' => $this->syncLog->id,
            'verdict' => 'GOOD',
            'coverage_state' => 'Submitted and indexed',
            'inspected_at' => now(),
        ]);

        $this->service->processInspectionResult($this->content, $inspection);

        $this->assertDatabaseHas('seo_feedback_loops', [
            'content_id' => $this->content->id,
            'gsc_coverage_state' => 'Submitted and indexed',
        ]);

        $this->assertDatabaseMissing('ai_reoptimization_queue', [
            'content_id' => $this->content->id,
        ]);
    }

    public function test_triggers_reoptimization_on_crawled_but_not_indexed(): void
    {
        $inspection = GscUrlInspection::create([
            'tenant_id' => $this->tenant->id,
            'content_id' => $this->content->id,
            'gsc_sync_log_id' => $this->syncLog->id,
            'verdict' => 'NEEDS_ATTENTION',
            'coverage_state' => 'Crawled - currently not indexed',
            'inspected_at' => now(),
        ]);

        $this->service->processInspectionResult($this->content, $inspection);

        $this->assertDatabaseHas('ai_reoptimization_queue', [
            'content_id' => $this->content->id,
            'status' => 'pending',
            'trigger_reason' => 'not_indexed',
            'priority' => 8,
        ]);

        $this->assertEquals('needs_reoptimize', $this->content->fresh()->status);
    }

    public function test_escalates_crawl_priority_on_discovered_but_not_indexed(): void
    {
        Queue::fake();

        $inspection = GscUrlInspection::create([
            'tenant_id' => $this->tenant->id,
            'content_id' => $this->content->id,
            'gsc_sync_log_id' => $this->syncLog->id,
            'verdict' => 'NEEDS_ATTENTION',
            'coverage_state' => 'Discovered - currently not indexed',
            'inspected_at' => now(),
        ]);

        $this->service->processInspectionResult($this->content, $inspection);

        $this->assertDatabaseHas('seo_feedback_loops', [
            'content_id' => $this->content->id,
            'crawl_priority_score' => 20.0,
        ]);

        Queue::assertPushed(SubmitToIndexingApiJob::class, function ($job) {
            return $job->content->id === $this->content->id;
        });
    }

    public function test_logs_mismatch_on_canonical_issue(): void
    {
        $inspection = GscUrlInspection::create([
            'tenant_id' => $this->tenant->id,
            'content_id' => $this->content->id,
            'gsc_sync_log_id' => $this->syncLog->id,
            'verdict' => 'NEEDS_ATTENTION',
            'coverage_state' => 'Duplicate without user-selected canonical',
            'canonical_declared_in_page' => 'https://test-tenant.test/test-post',
            'canonical_selected_by_google' => 'https://test-tenant.test/different-post',
            'inspected_at' => now(),
        ]);

        $this->service->processInspectionResult($this->content, $inspection);

        $this->assertDatabaseHas('seo_feedback_loops', [
            'content_id' => $this->content->id,
            'gsc_coverage_state' => 'Duplicate without user-selected canonical',
        ]);
    }
}
