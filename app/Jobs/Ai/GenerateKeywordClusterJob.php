<?php

namespace App\Jobs\Ai;

use App\Models\Content;
use App\Models\SiloBlueprint;
use App\Models\Tenant;
use App\Services\AIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateKeywordClusterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;
    public $tries = 3;

    private SiloBlueprint $silo;

    /**
     * Create a new job instance.
     */
    public function __construct(SiloBlueprint $silo)
    {
        $this->silo = $silo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tenant = $this->silo->tenant;
        $aiService = new AIService($tenant, 'keyword');

        Log::info("Starting GenerateKeywordClusterJob for Silo: {$this->silo->silo_name} (Seed: {$this->silo->seed_keyword})");

        $systemPrompt = "You are an expert SEO architect. Your task is to build a highly optimized topical map (Silo Architecture) based on a seed keyword.
Break down the topic into exactly:
- 1 Pillar page (broad, high competition)
- 3 to 5 Cluster pages (medium competition, specific intent)
- 3 to 5 Sub-cluster pages per cluster (long-tail keywords, informational/commercial intent, low competition/KGR).

Return the structure purely as JSON with the following schema:
{
    \"pillar\": {
        \"target_keyword\": \"string\",
        \"search_volume\": \"integer (estimate)\",
        \"kgr_score\": \"float (estimate)\"
    },
    \"clusters\": [
        {
            \"target_keyword\": \"string\",
            \"search_volume\": \"integer\",
            \"kgr_score\": \"float\",
            \"sub_clusters\": [
                {
                    \"target_keyword\": \"string\",
                    \"search_volume\": \"integer\",
                    \"kgr_score\": \"float\"
                }
            ]
        }
    ]
}";

        $userPrompt = "Seed Keyword: '{$this->silo->seed_keyword}'\nLanguage: {$this->silo->target_language}\nCountry: {$this->silo->target_country}\nPlease generate the JSON topical map.";

        $result = $aiService->generateJson($systemPrompt, $userPrompt);

        if (!$result || !isset($result['pillar'])) {
            Log::error("GenerateKeywordClusterJob failed to generate valid JSON map for Silo ID: {$this->silo->id}");
            return;
        }

        // Process Pillar
        $pillar = $result['pillar'];
        $pillarSlug = \Illuminate\Support\Str::slug($pillar['target_keyword']);
        $pillarContent = Content::create([
            'tenant_id'         => $tenant->id,
            'silo_blueprint_id' => $this->silo->id,
            'target_keyword'    => $pillar['target_keyword'],
            'slug'              => json_encode(['id' => $pillarSlug]),
            'hierarchy_level'   => 'pillar',
            'search_volume'     => $pillar['search_volume'] ?? 1000,
            'kgr_score'         => $pillar['kgr_score'] ?? 0.5,
            'status'            => 'blueprint'
        ]);

        // Process Clusters
        if (isset($result['clusters']) && is_array($result['clusters'])) {
            foreach ($result['clusters'] as $clusterData) {
                $clusterSlug = \Illuminate\Support\Str::slug($clusterData['target_keyword']);
                Content::create([
                    'tenant_id'         => $tenant->id,
                    'silo_blueprint_id' => $this->silo->id,
                    'target_keyword'    => $clusterData['target_keyword'],
                    'slug'              => json_encode(['id' => $clusterSlug]),
                    'hierarchy_level'   => 'cluster',
                    'search_volume'     => $clusterData['search_volume'] ?? 500,
                    'kgr_score'         => $clusterData['kgr_score'] ?? 0.3,
                    'status'            => 'blueprint'
                ]);

                // Process Sub-clusters
                if (isset($clusterData['sub_clusters']) && is_array($clusterData['sub_clusters'])) {
                    foreach ($clusterData['sub_clusters'] as $subClusterData) {
                        $subSlug = \Illuminate\Support\Str::slug($subClusterData['target_keyword']);
                        Content::create([
                            'tenant_id'         => $tenant->id,
                            'silo_blueprint_id' => $this->silo->id,
                            'target_keyword'    => $subClusterData['target_keyword'],
                            'slug'              => json_encode(['id' => $subSlug]),
                            'hierarchy_level'   => 'sub_cluster',
                            'search_volume'     => $subClusterData['search_volume'] ?? 100,
                            'kgr_score'         => $subClusterData['kgr_score'] ?? 0.1,
                            'status'            => 'blueprint'
                        ]);
                    }
                }
            }
        }

        // Update silo count
        $this->silo->update([
            'total_contents' => $this->silo->contents()->count()
        ]);

        Log::info("GenerateKeywordClusterJob completed successfully for Silo ID: {$this->silo->id}");
    }
}
