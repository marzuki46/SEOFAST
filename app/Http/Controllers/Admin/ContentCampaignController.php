<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\Ai\GenerateCampaignLandingPageJob;
use App\Models\ContentCampaign;
use App\Models\ContentCampaignTarget;
use App\Models\Product;
use App\Models\SiloBlueprint;
use App\Models\Tenant;
use App\Services\ContentCampaignScheduleService;
use App\Services\ContentCampaignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentCampaignController extends Controller
{
    public function index()
    {
        $campaigns = ContentCampaign::withoutGlobalScopes()
            ->with(['product', 'siloBlueprint', 'targets'])
            ->withCount(['topics', 'contents'])
            ->latest()->paginate(20);
        $products = Product::withoutGlobalScopes()->where('is_active', true)->orderBy('name')->get(['id', 'name']);
        $silos = SiloBlueprint::withoutGlobalScopes()->with('product')->orderBy('silo_name')->get(['id', 'silo_name', 'product_id']);

        return view('admin.content-campaigns.index', compact('campaigns', 'products', 'silos'));
    }

    public function store(Request $request, ContentCampaignScheduleService $scheduler)
    {
        $data = $this->validated($request);
        $keywords = $this->parseKeywords($data['target_keywords'] ?? '');
        abort_unless($keywords !== [], 422, 'Masukkan minimal satu keyword target.');
        $this->assertSiloMatchesProduct($data['silo_blueprint_id'] ?? null, $data['product_id']);
        unset($data['target_keywords']);
        $campaign = DB::transaction(function () use ($data, $keywords) {
            $campaign = ContentCampaign::create(array_merge($data, [
                'tenant_id' => auth()->user()->tenant_id ?? Tenant::first()?->id ?? 1,
                'status' => $data['status'] ?? 'draft',
            ]));
            foreach ($keywords as $index => $keyword) {
                ContentCampaignTarget::create([
                    'tenant_id' => $campaign->tenant_id,
                    'campaign_id' => $campaign->id,
                    'product_id' => $campaign->product_id,
                    'keyword' => $keyword,
                    'normalized_keyword' => app(ContentCampaignService::class)->normalize($keyword),
                    'sort_order' => $index,
                ]);
            }

            return $campaign;
        });
        $campaign->targets()->pluck('id')->each(fn (int $targetId) => GenerateCampaignLandingPageJob::dispatch($targetId));
        if ($campaign->status === 'active') {
            $scheduler->ensureNext($campaign);
        }

        return back()->with('success', 'Campaign konten berhasil dibuat.');
    }

    public function update(Request $request, ContentCampaign $campaign, ContentCampaignScheduleService $scheduler)
    {
        $this->assertTenantAccess($campaign);
        $data = $this->validated($request);
        $this->assertSiloMatchesProduct($data['silo_blueprint_id'] ?? null, $data['product_id']);
        $campaign->update($data);
        if ($campaign->status === 'active') {
            $scheduler->ensureNext($campaign->fresh());
        }

        return back()->with('success', 'Campaign konten diperbarui.');
    }

    public function research(Request $request, ContentCampaign $campaign, ContentCampaignService $service)
    {
        $this->assertTenantAccess($campaign);
        $data = $request->validate(['count' => ['nullable', 'integer', 'min:1', 'max:20'], 'funnel_stage' => ['nullable', 'in:tofu,mofu,bofu']]);
        $topics = $service->generateTopics($campaign, (int) ($data['count'] ?? $campaign->research_batch_size), $data['funnel_stage'] ?? null);

        return back()->with('success', count($topics).' topik unik ditambahkan ke topic bank.');
    }

    public function toggle(ContentCampaign $campaign, ContentCampaignScheduleService $scheduler)
    {
        $this->assertTenantAccess($campaign);
        $campaign->update(['status' => $campaign->status === 'active' ? 'paused' : 'active']);
        if ($campaign->status === 'active') {
            $scheduler->ensureNext($campaign->fresh());
        }

        return back()->with('success', $campaign->status === 'active' ? 'Campaign diaktifkan.' : 'Campaign dijeda.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'target_keywords' => ['nullable', 'string', 'max:5000'],
            'silo_blueprint_id' => ['required', 'integer', 'exists:silo_blueprints,id'],
            'name' => ['required', 'string', 'max:120'],
            'status' => ['nullable', 'in:draft,active,paused'],
            'language' => ['required', 'in:id,en'],
            'funnel_mode' => ['required', 'in:balanced,tofu,mofu,bofu'],
            'audience' => ['nullable', 'string', 'max:500'],
            'business_data' => ['nullable', 'string', 'max:10000'],
            'entity_seed' => ['nullable', 'string', 'max:500'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'url', 'max:2048'],
            'auto_research' => ['nullable', 'boolean'],
            'research_batch_size' => ['required', 'integer', 'min:1', 'max:20'],
            'posts_per_day' => ['required', 'integer', 'min:1', 'max:24'],
            'schedule_type' => ['required', 'in:random,fixed'],
            'fixed_hours' => ['nullable', 'string', 'max:255', 'regex:/^\s*(?:[01]\d|2[0-3]):[0-5]\d(?:\s*,\s*(?:[01]\d|2[0-3]):[0-5]\d)*\s*$/'],
            'publish_status' => ['required', 'in:draft,published'],
            'timezone' => ['required', 'timezone'],
        ]);
    }

    private function assertSiloMatchesProduct(?int $siloId, int $productId): void
    {
        if (! $siloId) {
            return;
        }
        $silo = SiloBlueprint::withoutGlobalScopes()->findOrFail($siloId);
        abort_unless(! $silo->product_id || (int) $silo->product_id === $productId, 422, 'Silo tersebut terkait produk lain.');
    }

    private function assertTenantAccess(ContentCampaign $campaign): void
    {
        $tenantId = auth()->user()->tenant_id;
        abort_unless(
            auth()->user()->isAdmin() || $tenantId === null || (int) $campaign->tenant_id === (int) $tenantId,
            404
        );
    }

    private function parseKeywords(string $input): array
    {
        return collect(preg_split('/[,\r\n]+/', $input) ?: [])
            ->map(fn ($keyword) => trim(strip_tags($keyword)))
            ->filter()
            ->map(fn ($keyword) => Str::limit($keyword, 191, ''))
            ->filter()
            ->unique(fn ($keyword) => app(ContentCampaignService::class)->normalize($keyword))
            ->take(20)
            ->values()
            ->all();
    }
}
