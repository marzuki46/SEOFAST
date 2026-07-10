<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $product = Product::where('slug', 'seofast-framework')->first();
        if (!$product) return;

        $product->changelog = [
            [
                'version' => '3.1',
                'label'   => 'Current',
                'color'   => 'indigo',
                'date'    => 'Juli 2026',
                'changes' => [
                    [
                        'type'  => 'added',
                        'items' => [
                            'Content frameworks: AIDA, PAS, How-To, Listicle + E-E-A-T enforcement',
                            'Tone options: Formal, Friendly, Persuasive, Authoritative, Conversational',
                            'Author name & bio configurable via SEO settings',
                            'Anchor keywords di Phase 1 + fallback anchors',
                            'Separate Phase 7 untuk SEO Meta + Embeddings',
                        ],
                    ],
                    [
                        'type'  => 'fixed',
                        'items' => [
                            'Multiple H1 per artikel → single H1 enforcement',
                            'AI hallucination artifacts & code block stripping',
                            '522 timeout — limit 50 + database indexes',
                            'Infinite loop phase transitions & guard clauses',
                            'Content style: 3-5 kalimat per paragraf, tabel untuk data',
                            'Brand injection across all phases',
                            'Duplicate slug handling + unified whereSlug() scope',
                        ],
                    ],
                ],
            ],
            [
                'version' => '3.0',
                'label'   => 'Major Release',
                'color'   => 'emerald',
                'date'    => 'Juni 2026',
                'changes' => [
                    [
                        'type'  => 'added',
                        'items' => [
                            '4-phase multi-agent AI pipeline (Drafter → Inquirer → Expander → Editor)',
                            'Multi-provider AI support + smart fallback',
                            'Topical Map / Silo Architect dengan Drawflow UI',
                            'KGR (Keyword Golden Ratio) scoring',
                            'Deterministic internal linking — zero orphan pages',
                            'Google Search Console OAuth2 integration (3 API layers)',
                            'SEO Audit tools: URL audit, broken links, duplicate content, readability',
                            'SERP Rank Tracker — daily snapshots, trend analysis',
                            'Auto-reoptimization loop — ranking drop triggers content refresh',
                            'Schema markup engine (6 schema types)',
                            'WordPress Import/Export (WXR 1.2, Yoast meta)',
                            'Digital marketplace dengan Midtrans payment',
                            'Buyer portal dengan support ticket system',
                            'Page builder dengan 6 template variants',
                            'Multi-language (ID/EN) dengan auto hreflang',
                            'CQI (Content Quality Index) scoring engine',
                        ],
                    ],
                ],
            ],
            [
                'version' => '2.x',
                'label'   => 'Foundation',
                'color'   => 'amber',
                'date'    => 'Maret–Mei 2026',
                'changes' => [
                    [
                        'type'  => 'added',
                        'items' => [
                            'AI content generation engine (single-phase)',
                            'Basic silo structure management',
                            'Google Search Console legacy Webmasters API',
                            'Media library with WebP conversion',
                            'Admin dashboard & system settings',
                            'Dynamic sitemap.xml & robots.txt',
                            'Page management with templates',
                        ],
                    ],
                ],
            ],
        ];

        $product->documentation = <<<DOC
<h3 class="font-outfit font-bold text-xl text-slate-900 mb-4">Apa Itu SEOFAST?</h3>
<p class="text-slate-600 leading-relaxed mb-6">SEOFAST adalah SEO automation framework berbasis <strong>Laravel 13</strong> yang dirancang untuk membantu developer, agency, dan bisnis mengelola SEO secara terstruktur dan terotomasi. Bukan plugin WordPress — ini adalah native Laravel application dengan PHP 8.4.</p>

<h3 class="font-outfit font-bold text-xl text-slate-900 mb-4">Tech Stack</h3>
<table class="w-full text-sm mb-6">
    <tbody>
        <tr class="border-b border-slate-100"><td class="py-2.5 pr-4 font-semibold text-slate-700 w-1/4">Framework</td><td class="py-2.5 text-slate-600">Laravel 13</td></tr>
        <tr class="border-b border-slate-100"><td class="py-2.5 pr-4 font-semibold text-slate-700">PHP</td><td class="py-2.5 text-slate-600">8.4+</td></tr>
        <tr class="border-b border-slate-100"><td class="py-2.5 pr-4 font-semibold text-slate-700">Database</td><td class="py-2.5 text-slate-600">MySQL 8.0+ (JSON indexing, Full-text search)</td></tr>
        <tr class="border-b border-slate-100"><td class="py-2.5 pr-4 font-semibold text-slate-700">Frontend</td><td class="py-2.5 text-slate-600">Tailwind CSS v4 + Alpine.js v3 + Vite 8</td></tr>
        <tr class="border-b border-slate-100"><td class="py-2.5 pr-4 font-semibold text-slate-700">AI Providers</td><td class="py-2.5 text-slate-600">OpenAI, Google Gemini, Anthropic Claude, DeepSeek, 9Router</td></tr>
        <tr class="border-b border-slate-100"><td class="py-2.5 pr-4 font-semibold text-slate-700">Cache</td><td class="py-2.5 text-slate-600">Cloudflare → Nginx fastcgi_cache → Redis</td></tr>
        <tr class="border-b border-slate-100"><td class="py-2.5 pr-4 font-semibold text-slate-700">Queue</td><td class="py-2.5 text-slate-600">Laravel Horizon (Redis) — multi-queue</td></tr>
        <tr class="border-b border-slate-100"><td class="py-2.5 pr-4 font-semibold text-slate-700">Payment</td><td class="py-2.5 text-slate-600">Midtrans Snap API</td></tr>
    </tbody>
</table>

<h3 class="font-outfit font-bold text-xl text-slate-900 mb-4">Persyaratan Server</h3>
<ul class="space-y-2 text-sm text-slate-600 mb-6">
    <li class="flex items-start gap-2">• PHP 8.4+ (ext: bcmath, ctype, curl, dom, fileinfo, gd, json, mbstring, mysqli, openssl, pdo_mysql, redis, tokenizer, xml, zip)</li>
    <li class="flex items-start gap-2">• MySQL 8.0+ / MariaDB 10.6+</li>
    <li class="flex items-start gap-2">• Redis 6+ (cache, queue, session)</li>
    <li class="flex items-start gap-2">• Composer 2.x</li>
    <li class="flex items-start gap-2">• Node.js 20+ (untuk frontend build)</li>
    <li class="flex items-start gap-2">• Minimum VPS 2GB RAM (recommended 4GB+)</li>
</ul>

<h3 class="font-outfit font-bold text-xl text-slate-900 mb-4">Arsitektur</h3>
<p class="text-slate-600 leading-relaxed mb-6">SEOFAST menggunakan arsitektur modular dengan service layer terpisah. Setiap fitur utama adalah service independen: AIService, GSCService, InternalLinkingService, ImageService, dan lainnya. Semua queue job diproses via Laravel Horizon dengan prioritas queue berbeda (ai-heavy, gsc-sync, serp-tracking, maintenance). Cache bertingkat memastikan performa optimal: Cloudflare di edge, Nginx fastcgi_cache sebagai fallback, dan Redis untuk cache tags dengan invalidasi per-silo.</p>

<h3 class="font-outfit font-bold text-xl text-slate-900 mb-4">Mulai Cepat</h3>
<ol class="space-y-2 text-sm text-slate-600 mb-6 list-decimal list-inside">
    <li>Clone repository ke server</li>
    <li>Jalankan <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs font-mono">composer install</code></li>
    <li>Copy <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs font-mono">.env.example</code> → <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs font-mono">.env</code> dan atur database, Redis, API keys</li>
    <li>Jalankan <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs font-mono">php artisan key:generate</code></li>
    <li>Jalankan <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs font-mono">php artisan migrate --seed</code></li>
    <li>Jalankan <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs font-mono">npm install && npm run build</code></li>
    <li>Buka website dan jalankan <strong>Installation Wizard</strong></li>
</ol>

<p class="text-sm text-slate-500 mt-8 pt-6 border-t border-slate-200">Dokumentasi lengkap tersedia di blog dan repository. Untuk bantuan teknis, hubungi via form Kontak.</p>
DOC;

        $product->display_sections = ['description', 'features', 'specifications', 'faq', 'changelog', 'documentation'];
        $product->save();
    }

    public function down(): void
    {
        $product = Product::where('slug', 'seofast-framework')->first();
        if ($product) {
            $product->changelog = null;
            $product->documentation = null;
            $product->display_sections = ['description', 'features', 'specifications', 'faq'];
            $product->save();
        }
    }
};
