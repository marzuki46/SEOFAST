<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentCampaign;
use App\Models\ContentCampaignTarget;
use App\Models\Product;
use App\Models\SiloBlueprint;
use App\Models\SystemSetting;
use App\Models\Tenant;
use App\Services\ContentCampaignPromptService;
use App\Services\ContentCampaignScheduleService;
use App\Services\ContentCampaignService;
use App\Services\ContentEditorialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ContentCampaignController extends Controller
{
    public function index()
    {
        $campaigns = ContentCampaign::withoutGlobalScopes()
            ->with(['product', 'siloBlueprint', 'targets'])
            ->withCount([
                'topics',
                'topics as tofu_topics_count' => fn ($q) => $q->where('funnel_stage', 'tofu'),
                'topics as mofu_topics_count' => fn ($q) => $q->where('funnel_stage', 'mofu'),
                'topics as bofu_topics_count' => fn ($q) => $q->where('funnel_stage', 'bofu'),
                'contents',
                'contents as published_contents_count' => fn ($q) => $q->where('status', 'published'),
                'contents as draft_contents_count' => fn ($q) => $q->where('status', 'draft'),
                'contents as processing_contents_count' => fn ($q) => $q->where('status', 'ai_processing'),
            ])
            ->latest()->paginate(20);
        $campaignOptions = ContentCampaign::withoutGlobalScopes()->latest()->get(['id', 'name']);
        $products = Product::withoutGlobalScopes()->where('is_active', true)->orderBy('name')->get(['id', 'name']);
        $silos = SiloBlueprint::withoutGlobalScopes()->with('product')->orderBy('silo_name')->get(['id', 'silo_name', 'product_id']);

        $globalSettings = [
            'ai_provider' => SystemSetting::get('ai_provider', 'gemini'),
            // Never render provider credentials into the admin HTML. A blank value
            // means "keep the existing server-side secret" when settings are saved.
            'gemini_api_key' => '',
            'gemini_api_key_configured' => filled(SystemSetting::get('gemini_api_key')),
            'gemini_model' => SystemSetting::get('gemini_model', 'gemini-2.0-flash'),
            '9router_api_key' => '',
            '9router_api_key_configured' => filled(SystemSetting::get('9router_api_key')),
            '9router_api_base' => SystemSetting::get('9router_api_base', 'https://api.9router.com/v1'),
            '9router_model' => SystemSetting::get('9router_model', 'openai/gpt-4o'),
            'openai_api_key' => '',
            'openai_api_key_configured' => filled(SystemSetting::get('openai_api_key')),
            'openai_model' => SystemSetting::get('openai_model', 'gpt-4o'),
            'custom_api_base' => SystemSetting::get('custom_api_base', 'http://localhost:20128/v1'),
            'custom_api_key' => SystemSetting::get('custom_api_key', ''),
            'custom_model' => SystemSetting::get('custom_model', 'custom-model'),
            'model_planner' => SystemSetting::get('apc_model_planner', 'gemini-2.5-flash'),
            'model_writer' => SystemSetting::get('apc_model_writer', 'gemini-2.0-flash'),
            'niche' => SystemSetting::get('apc_niche', 'Jasa & Solusi Bisnis Digital'),
            'brand_voice' => SystemSetting::get('apc_brand_voice', 'Otoritatif, Solutif, Informatif, Profesional'),
            'audience' => SystemSetting::get('apc_audience', 'Pemilik Bisnis, Calon Klien, Pengambil Keputusan'),
            'business_data' => SystemSetting::get('apc_business_data', ''),
            'cta_type' => SystemSetting::get('apc_cta_type', 'whatsapp'),
            'cta_label' => SystemSetting::get('apc_cta_label', 'Hubungi Kami via WhatsApp'),
            'cta_url' => SystemSetting::get('apc_cta_url', ''),
            'word_count_min' => (int) SystemSetting::get('apc_word_count_min', 900),
            'word_count_max' => (int) SystemSetting::get('apc_word_count_max', 1400),
            'keyword_prompt' => SystemSetting::get('apc_keyword_prompt', "Ekstrak dan kembangkan kata kunci entitas SEO:\n1. Target kata kunci utama (Primary Keyword)\n2. 5-8 Entity keywords (LSI / semantic terms) yang relevan\n3. Klasifikasi search intent pengunjung"),
            'content_prompt' => SystemSetting::get('apc_content_prompt', "Tulis artikel dengan gaya bahasa jurnalis atau praktisi profesional manusia (100% natural, bebas klise robot AI, minimal 800-1400 kata).\n- HINDARI pembuka klise seperti 'Di era digital yang serba cepat...'\n- HINDARI sub-judul 'Kesimpulan', ganti dengan langkah aksi praktis\n- JANGAN gunakan garis mendatar (---)\n- Sub-heading jelas (H2, H3) dengan studi kasus/contoh konkret\n- Sisipkan Call To Action (CTA) secara persuasif dan mengalir\n- Optimasi kaidah Google Helpful Content & E-E-A-T"),
            'editorial_enabled' => (bool) SystemSetting::get('apc_editorial_enabled', false),
            'editorial_prompt' => SystemSetting::get('apc_editorial_prompt', app(ContentEditorialService::class)->defaultEditorialPrompt()),
        ];

        return view('admin.content-campaigns.index', compact('campaigns', 'campaignOptions', 'products', 'silos', 'globalSettings'));
    }

    public function updateGlobalSettings(Request $request)
    {
        $validated = $request->validate([
            'ai_provider' => ['nullable', 'string', 'in:gemini,9router,openai,claude,custom'],
            'gemini_api_key' => ['nullable', 'string'],
            'gemini_model' => ['nullable', 'string'],
            '9router_api_key' => ['nullable', 'string'],
            '9router_api_base' => ['nullable', 'string'],
            '9router_model' => ['nullable', 'string'],
            'openai_api_key' => ['nullable', 'string'],
            'openai_model' => ['nullable', 'string'],
            'custom_api_base' => ['nullable', 'string'],
            'custom_api_key' => ['nullable', 'string'],
            'custom_model' => ['nullable', 'string'],
            'model_planner' => ['nullable', 'string'],
            'model_writer' => ['nullable', 'string'],
            'niche' => ['nullable', 'string', 'max:255'],
            'brand_voice' => ['nullable', 'string', 'max:255'],
            'audience' => ['nullable', 'string', 'max:500'],
            'business_data' => ['nullable', 'string', 'max:15000'],
            'cta_type' => ['nullable', 'string', 'max:50'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:2048'],
            'word_count_min' => ['nullable', 'integer', 'min:300', 'max:5000'],
            'word_count_max' => ['nullable', 'integer', 'min:400', 'max:8000'],
            'keyword_prompt' => ['nullable', 'string', 'max:50000'],
            'content_prompt' => ['nullable', 'string', 'max:50000'],
            'editorial_prompt' => ['nullable', 'string', 'max:50000'],
            'editorial_enabled' => ['nullable', 'boolean'],
        ]);

        if (isset($validated['ai_provider'])) {
            SystemSetting::set('ai_provider', $validated['ai_provider'], 'ai');
        }
        if (filled($validated['gemini_api_key'] ?? null)) {
            SystemSetting::set('gemini_api_key', $validated['gemini_api_key'], 'ai');
        }
        if (isset($validated['gemini_model'])) {
            SystemSetting::set('gemini_model', $validated['gemini_model'], 'ai');
        }
        if (filled($validated['9router_api_key'] ?? null)) {
            SystemSetting::set('9router_api_key', $validated['9router_api_key'], 'ai');
        }
        if (isset($validated['9router_api_base'])) {
            SystemSetting::set('9router_api_base', $validated['9router_api_base'], 'ai');
        }
        if (isset($validated['9router_model'])) {
            SystemSetting::set('9router_model', $validated['9router_model'], 'ai');
        }
        if (filled($validated['openai_api_key'] ?? null)) {
            SystemSetting::set('openai_api_key', $validated['openai_api_key'], 'ai');
        }
        if (isset($validated['openai_model'])) {
            SystemSetting::set('openai_model', $validated['openai_model'], 'ai');
        }
        if (isset($validated['custom_api_base'])) {
            SystemSetting::set('custom_api_base', $validated['custom_api_base'], 'ai');
        }
        if (isset($validated['custom_api_key'])) {
            SystemSetting::set('custom_api_key', $validated['custom_api_key'], 'ai');
        }
        if (isset($validated['custom_model'])) {
            SystemSetting::set('custom_model', $validated['custom_model'], 'ai');
        }
        if (isset($validated['model_planner'])) {
            SystemSetting::set('apc_model_planner', $validated['model_planner'], 'ai');
        }
        if (isset($validated['model_writer'])) {
            SystemSetting::set('apc_model_writer', $validated['model_writer'], 'ai');
        }

        if (isset($validated['niche'])) {
            SystemSetting::set('apc_niche', $validated['niche'], 'content');
        }
        if (isset($validated['brand_voice'])) {
            SystemSetting::set('apc_brand_voice', $validated['brand_voice'], 'content');
        }
        if (isset($validated['audience'])) {
            SystemSetting::set('apc_audience', $validated['audience'], 'content');
        }
        if (isset($validated['business_data'])) {
            SystemSetting::set('apc_business_data', $validated['business_data'], 'content');
        }
        if (isset($validated['cta_type'])) {
            SystemSetting::set('apc_cta_type', $validated['cta_type'], 'content');
        }
        if (isset($validated['cta_label'])) {
            SystemSetting::set('apc_cta_label', $validated['cta_label'], 'content');
        }
        if (isset($validated['cta_url'])) {
            SystemSetting::set('apc_cta_url', $validated['cta_url'], 'content');
        }
        if (isset($validated['word_count_min'])) {
            SystemSetting::set('apc_word_count_min', $validated['word_count_min'], 'content', 'integer');
        }
        if (isset($validated['word_count_max'])) {
            SystemSetting::set('apc_word_count_max', $validated['word_count_max'], 'content', 'integer');
        }
        if (isset($validated['keyword_prompt'])) {
            SystemSetting::set('apc_keyword_prompt', $validated['keyword_prompt'], 'content');
        }
        if (isset($validated['content_prompt'])) {
            SystemSetting::set('apc_content_prompt', $validated['content_prompt'], 'content');
        }
        if (isset($validated['editorial_prompt'])) {
            SystemSetting::set('apc_editorial_prompt', $validated['editorial_prompt'], 'content');
        }
        SystemSetting::set('apc_editorial_enabled', $request->boolean('editorial_enabled'), 'content', 'boolean');

        SystemSetting::flushCache();

        // Sinkronkan langsung ke seluruh campaign agar perubahan di tab langsung aktif
        ContentCampaign::query()->get()->each(function (ContentCampaign $c) {
            app(ContentCampaignPromptService::class)->syncGlobalToCampaign($c);
        });

        return back()->with('success', 'Pengaturan terpusat AI, Brand, dan Prompt SEO berhasil disimpan dan disinkronkan ke seluruh campaign.');
    }

    public function topics(ContentCampaign $campaign, ContentCampaignService $service)
    {
        $this->assertTenantAccess($campaign);

        // Jika topik idea tersisa <= 1, otomatis generate ulang seluruh funnel masing-masing 5 topik (TOFU 5, MOFU 5, BOFU 5)
        $ideaCount = $campaign->topics()->where('status', 'idea')->count();
        $hasTofuIdeas = $campaign->topics()->where('funnel_stage', 'tofu')->whereIn('status', ['idea', 'processing'])->exists();
        if ($ideaCount <= 1 || ! $hasTofuIdeas) {
            try {
                $service->replenishTopicsAllFunnels($campaign, 5);
            } catch (\Throwable $e) {
                Log::warning('Auto replenish topics on view failed: '.$e->getMessage());
            }
        }

        $topics = $campaign->topics()
            ->with(['campaignTarget', 'siloBlueprint', 'product', 'content'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $campaign->load(['siloBlueprint', 'product']);

        return view('admin.content-campaigns.topics', compact('campaign', 'topics'));
    }

    public function store(Request $request, ContentCampaignScheduleService $scheduler)
    {
        $data = $this->validated($request);
        $keywords = $this->parseKeywords($data['target_keywords'] ?? '');
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
        $campaign = app(ContentCampaignPromptService::class)->applyDefaults($campaign);
        $targets = $campaign->targets()->get();
        if ($campaign->destination_url && $targets->isNotEmpty()) {
            $targets->each(function (ContentCampaignTarget $target): void {
                $target->update([
                    'landing_url' => $target->campaign->destination_url,
                    'anchor_variations' => [
                        $target->keyword,
                        'informasi tentang '.$target->keyword,
                        'solusi '.$target->keyword,
                    ],
                    'status' => 'ready',
                ]);
            });
        }
        if ($campaign->status === 'active') {
            $scheduler->ensureNext($campaign);
        }

        return back()->with('success', 'Campaign konten berhasil dibuat.');
    }

    public function edit(ContentCampaign $campaign)
    {
        $this->assertTenantAccess($campaign);
        $products = Product::withoutGlobalScopes()->where('is_active', true)->orderBy('name')->get(['id', 'name']);
        $silos = SiloBlueprint::withoutGlobalScopes()->with('product')->orderBy('silo_name')->get(['id', 'silo_name', 'product_id']);
        $campaign->load('targets');

        return view('admin.content-campaigns.edit', compact('campaign', 'products', 'silos'));
    }

    public function settings(ContentCampaign $campaign, ContentCampaignPromptService $prompts)
    {
        $this->assertTenantAccess($campaign);
        $campaign = $prompts->applyDefaults($campaign);

        return view('admin.content-campaigns.settings', compact('campaign'));
    }

    public function updateSettings(Request $request, ContentCampaign $campaign)
    {
        $this->assertTenantAccess($campaign);
        $data = $request->validate([
            'audience' => ['nullable', 'string', 'max:500'],
            'brand_voice' => ['nullable', 'string', 'max:255'],
            'niche' => ['nullable', 'string', 'max:255'],
            'business_data' => ['nullable', 'string', 'max:10000'],
            'entity_seed' => ['nullable', 'string', 'max:500'],
            'keyword_prompt' => ['required', 'string', 'max:50000'],
            'entity_prompt' => ['required', 'string', 'max:50000'],
            'landing_prompt' => ['required', 'string', 'max:50000'],
            'content_prompt' => ['required', 'string', 'max:50000'],
            'word_count_min' => ['required', 'integer', 'min:300', 'max:5000'],
            'word_count_max' => ['required', 'integer', 'min:400', 'max:8000', 'gte:word_count_min'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:2048', $this->ctaUrlOrWhatsappRule()],
            'destination_url' => ['nullable', 'url', 'max:2048'],
        ]);
        $campaign->update($data);

        $this->syncDestinationUrl($campaign->fresh());

        return back()->with('success', 'Prompt dan data reusable campaign berhasil disimpan.');
    }

    public function update(Request $request, ContentCampaign $campaign, ContentCampaignScheduleService $scheduler)
    {
        $this->assertTenantAccess($campaign);
        $data = $this->validated($request);
        $keywords = $this->parseKeywords($data['target_keywords'] ?? '');
        unset($data['target_keywords']);
        $this->assertSiloMatchesProduct($data['silo_blueprint_id'] ?? null, $data['product_id']);

        DB::transaction(function () use ($campaign, $data, $keywords): void {
            $campaign->update($data);
            $campaign->targets()->delete();

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
        });

        $freshCampaign = $campaign->fresh();
        $this->syncDestinationUrl($freshCampaign);
        if ($freshCampaign->status === 'active') {
            $scheduler->ensureNext($freshCampaign);
        }

        return back()->with('success', 'Campaign konten diperbarui.');
    }

    public function destroy(ContentCampaign $campaign)
    {
        $this->assertTenantAccess($campaign);
        $campaignName = $campaign->name;
        $campaign->forceDelete();

        return redirect()->route('admin.content-campaigns.index')
            ->with('success', "Campaign '{$campaignName}' dihapus. Artikel yang sudah terbit tetap dipertahankan.");
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

    public function triggerPost(ContentCampaign $campaign, ContentCampaignScheduleService $scheduler)
    {
        $this->assertTenantAccess($campaign);

        Log::info("=== TRIGGER POST DIRECT EXECUTION FOR CAMPAIGN: {$campaign->id} ({$campaign->name}) ===");

        try {
            $ideaCount = $campaign->topics()->where('status', 'idea')->count();
            if ($ideaCount === 0) {
                Log::info("Topic bank kosong untuk campaign {$campaign->id}. Menjalankan riset instan...");
                $topicService = app(ContentCampaignService::class);
                $topicService->generateTopics($campaign, max(3, $campaign->research_batch_size));
            }

            $topic = $campaign->topics()->where('status', 'idea')->orderBy('id')->first();
            if (! $topic) {
                return back()->with('error', 'Topic bank kosong dan riset AI gagal menghasilkan topik.');
            }

            $topic->update(['status' => 'processing']);

            $slug = Str::slug($topic->primary_keyword) ?: 'artikel';
            $base = $slug;
            $counter = 1;
            while (\App\Models\Content::withoutGlobalScopes()->whereSlug($slug)->exists()) {
                $slug = $base.'-'.$counter++;
            }

            $siloId = $campaign->silo_blueprint_id;
            if (!$siloId) {
                $firstSilo = SiloBlueprint::withoutGlobalScopes()->first();
                $siloId = $firstSilo?->id ?? 1;
            }

            [$content, $job] = DB::transaction(function () use ($campaign, $topic, $slug, $siloId, $scheduler) {
                $content = \App\Models\Content::create([
                    'tenant_id'          => $campaign->tenant_id,
                    'product_id'         => $campaign->product_id,
                    'campaign_id'        => $campaign->id,
                    'campaign_target_id' => $topic->campaign_target_id,
                    'content_topic_id'   => $topic->id,
                    'silo_blueprint_id'  => $siloId,
                    'target_keyword'     => $topic->primary_keyword,
                    'slug'               => $slug,
                    'meta_title'         => $topic->title,
                    'meta_description'   => Str::limit($topic->angle ?: $topic->title, 155),
                    'hierarchy_level'    => $topic->funnel_stage === 'tofu' ? 'pillar' : 'cluster',
                    'status'             => 'ai_processing',
                ]);

                $job = \App\Models\AiGenerationJob::create([
                    'tenant_id'  => $campaign->tenant_id,
                    'content_id' => $content->id,
                    'job_type'   => 'initial_generation',
                    'status'     => 'pending',
                ]);

                $topic->update(['status' => 'used', 'content_id' => $content->id]);
                $campaign->update(['last_run_at' => now(), 'next_run_at' => $scheduler->nextRunAt($campaign)]);
                $scheduler->ensureNext($campaign->fresh());

                return [$content, $job];
            });

            Log::info("Campaign content prepared sync: content_id={$content->id}, job_id={$job->id}. Running ReliableContentGenerator...");

            $generator = new \App\Services\ReliableContentGenerator();
            $generator->generateAndSave($content, $job, 'published');

            Log::info("=== TRIGGER POST COMPLETED SUCCESSFULLY FOR CONTENT {$content->id} ===");

            return redirect()->route('admin.content-campaigns.index')
                ->with('success', "🚀 Artikel '{$content->meta_title}' berhasil digenerate dan dipublikasikan!");

        } catch (\Throwable $e) {
            Log::error("=== TRIGGER POST FAILED FOR CAMPAIGN {$campaign->id}: " . $e->getMessage() . " ===");
            Log::error($e->getTraceAsString());

            return back()->with('error', 'Gagal memicu posting: ' . $e->getMessage());
        }
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'target_keywords' => ['nullable', 'string', 'max:5000'],
            'silo_blueprint_id' => ['nullable', 'integer', 'exists:silo_blueprints,id'],
            'name' => ['required', 'string', 'max:120'],
            'status' => ['nullable', 'in:draft,active,paused'],
            'language' => ['required', 'in:id,en'],
            'funnel_mode' => ['required', 'in:balanced,tofu,mofu,bofu'],
            'audience' => ['nullable', 'string', 'max:500'],
            'brand_voice' => ['nullable', 'string', 'max:255'],
            'niche' => ['nullable', 'string', 'max:255'],
            'business_data' => ['nullable', 'string', 'max:10000'],
            'entity_seed' => ['nullable', 'string', 'max:500'],
            'keyword_prompt' => ['nullable', 'string', 'max:50000'],
            'entity_prompt' => ['nullable', 'string', 'max:50000'],
            'landing_prompt' => ['nullable', 'string', 'max:50000'],
            'content_prompt' => ['nullable', 'string', 'max:50000'],
            'word_count_min' => ['required', 'integer', 'min:300', 'max:5000'],
            'word_count_max' => ['required', 'integer', 'min:400', 'max:8000', 'gte:word_count_min'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:2048', $this->ctaUrlOrWhatsappRule()],
            'destination_url' => ['nullable', 'url', 'max:2048'],
            'auto_research' => ['nullable', 'boolean'],
            'research_batch_size' => ['required', 'integer', 'min:1', 'max:20'],
            'posts_per_day' => ['required', 'integer', 'min:1', 'max:24'],
            'schedule_type' => ['required', 'in:random,fixed'],
            'fixed_hours' => ['nullable', 'string', 'max:255', 'regex:/^\s*(?:[01]\d|2[0-3]):[0-5]\d(?:\s*,\s*(?:[01]\d|2[0-3]):[0-5]\d)*\s*$/'],
            'publish_status' => ['required', 'in:draft,published'],
            'timezone' => ['required', 'timezone'],
        ]);
    }

    private function assertSiloMatchesProduct(?int $siloId, ?int $productId): void
    {
        if (! $siloId || ! $productId) {
            return;
        }
        $silo = SiloBlueprint::withoutGlobalScopes()->findOrFail($siloId);
        abort_unless(! $silo->product_id || (int) $silo->product_id === $productId, 422, 'Silo tersebut terkait produk lain.');
    }

    private function ctaUrlOrWhatsappRule(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            $value = trim((string) $value);
            $isUrl = filter_var($value, FILTER_VALIDATE_URL) !== false;
            $isWhatsapp = preg_match('/^\+?[0-9][0-9 .()\-]{7,24}$/', $value) === 1;

            if (! $isUrl && ! $isWhatsapp) {
                $fail('CTA harus berupa nomor WhatsApp atau URL yang valid.');
            }
        };
    }

    private function syncDestinationUrl(ContentCampaign $campaign): void
    {
        $targets = $campaign->targets()->get();
        if ($targets->isEmpty()) {
            return;
        }

        if (! $campaign->destination_url) {
            $targets->each(fn (ContentCampaignTarget $target) => $target->update([
                'landing_url' => null,
                'anchor_variations' => null,
            ]));

            return;
        }

        $targets->each(fn (ContentCampaignTarget $target) => $target->update([
            'landing_url' => $campaign->destination_url,
            'anchor_variations' => [
                $target->keyword,
                'informasi tentang '.$target->keyword,
                'solusi '.$target->keyword,
            ],
            'status' => 'ready',
        ]));
    }

    private function assertTenantAccess(ContentCampaign $campaign): void
    {
        $user = auth()->user();
        if (!$user) {
            return;
        }
        $tenantId = $user->tenant_id;
        abort_unless(
            $user->isAdmin() || $tenantId === null || (int) $campaign->tenant_id === (int) $tenantId,
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
