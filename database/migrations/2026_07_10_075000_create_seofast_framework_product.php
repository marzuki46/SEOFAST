<?php

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Create category ──
        $category = ProductCategory::firstOrCreate(
            ['slug' => 'seofast-framework'],
            ['name' => 'SEOFAST Framework', 'order' => 1, 'is_active' => true]
        );

        // ── 2. Create product ──
        $product = new Product();
        $product->tenant_id = 1;
        $product->name = 'SEOFAST Framework v3.1';
        $product->slug = 'seofast-framework';
        $product->shortcode = 'seofast-framework';
        $product->description = "SEOFAST adalah SEO automation framework berbasis Laravel 13. Dirancang untuk developer, agency, dan bisnis yang ingin mengelola SEO secara terstruktur — dari silo architecture, AI content pipeline multi-agent, rank tracker, Google Search Console integration, hingga digital marketplace — semua dalam satu ekosistem.\n\nBukan plugin WordPress. Bukan tema. SEOFAST adalah native Laravel application dengan PHP 8.4, Tailwind CSS v4, dan Alpine.js, siap diinstall di server sendiri atau dikelola sebagai SaaS multi-tenant.";
        $product->price = 0;
        $product->features = [
            'AI Content Engine — 4-phase multi-agent pipeline (Drafter, Inquirer, Expander, Editor)',
            'Multi-provider AI support: OpenAI, Gemini, Claude, DeepSeek, 9Router + auto fallback',
            'Content frameworks: AIDA, PAS, How-To, Listicle + E-E-A-T enforcement',
            'Tone options: Formal, Friendly, Persuasive, Authoritative, Conversational',
            'CQI (Content Quality Index) scoring — threshold minimal 80',
            'Topical Map / Silo Architect dengan visual node graph (Drawflow UI)',
            'KGR (Keyword Golden Ratio) scoring + search volume tracking',
            'Deterministic Internal Linking — zero orphan pages, AI anchor injection',
            'Google Search Console OAuth2 integration (URL Inspection, Search Analytics, Indexing API)',
            'SEO Audit Tools: URL audit, broken link scanner, duplicate content detection (AI cosine)',
            'Readability scoring, 404 error tracker, redirect manager',
            'SERP Rank Tracker — daily snapshots, trend analysis, multi-device, multi-country',
            'Auto-reoptimization loop — ranking drop triggers content refresh',
            'Schema markup engine (Article, FAQPage, HowTo, Product, LocalBusiness, BreadcrumbList)',
            'Dynamic sitemap.xml with priority scores + dynamic robots.txt',
            'Ghost publish with noindex placeholder',
            'WordPress Import/Export (WXR 1.2, Yoast SEO meta preservation)',
            'Digital marketplace with Midtrans payment (Snap API)',
            'Pre-order system + buyer portal with support tickets',
            'Page builder with 6 template variants',
            'Multi-language (Indonesia/English) with auto hreflang',
            'Multi-tenant support untuk agency',
            'Cache layers: Cloudflare → Nginx fastcgi_cache → Redis',
        ];
        $product->specifications = [
            ['key' => 'Tech Stack', 'value' => 'Laravel 13 / PHP 8.4+ / MySQL 8.0+'],
            ['key' => 'Frontend', 'value' => 'Tailwind CSS v4 / Alpine.js v3 / Vite 8'],
            ['key' => 'AI Providers', 'value' => 'OpenAI, Google Gemini, Anthropic Claude, DeepSeek, 9Router, Custom API'],
            ['key' => 'Database', 'value' => 'MySQL 8.0+ (JSON indexing, Full-text search)'],
            ['key' => 'Cache', 'value' => 'Cloudflare (L1) → Nginx fastcgi_cache (L2) → Redis + Cache Tags (L3)'],
            ['key' => 'Queue', 'value' => 'Laravel Horizon (Redis) — multi-queue: ai-heavy, gsc-sync, serp-tracking, maintenance'],
            ['key' => 'Storage', 'value' => 'Local / S3 / R2 — WebP compression otomatis'],
            ['key' => 'Payment', 'value' => 'Midtrans Snap API'],
            ['key' => 'Auth', 'value' => 'Dual guard: Admin (web) + Buyer (buyer) with Google OAuth'],
            ['key' => 'Lisensi', 'value' => 'Per-domain / Multi-tenant untuk agency'],
        ];
        $product->faq = [
            ['question' => 'SEOFAST itu WordPress plugin?', 'answer' => 'Bukan. SEOFAST adalah native Laravel application, bukan plugin atau theme WordPress. Dibangun di atas Laravel 13 dengan PHP 8.4.'],
            ['question' => 'Bisa diinstall di server sendiri?', 'answer' => 'Bisa. Lisensi per-domain memungkinkan kamu install di server sendiri. Ada juga opsi SaaS multi-tenant untuk agency.'],
            ['question' => 'Minimal spesifikasi server?', 'answer' => 'Minimal VPS 2GB RAM, PHP 8.4+, MySQL 8.0+, Redis. Untuk production disarankan 4GB RAM + 2 CPU.'],
            ['question' => 'Support multi-tenant untuk agency?', 'answer' => 'Ya. SEOFAST mendukung multi-tenant dengan role management, domain terpisah per client, dan white-label options. Cocok untuk agency SEO yang handle banyak client.'],
            ['question' => 'Apakah include update dan support?', 'answer' => 'Lisensi mencakup update selama masa aktif. Support via ticket system dan WhatsApp untuk konsultasi teknis.'],
            ['question' => 'Bisa dicoba dulu sebelum beli?', 'answer' => 'Silakan konsultasi dulu. Saya bisa kasih demo atau akses trial terbatas. Hubungi saya lewat form Kontak.'],
        ];
        $product->enable_buy_button = false;
        $product->enable_inquiry_button = true;
        $product->inquiry_label = 'Konsultasi & Info Lisensi';
        $product->inquiry_url = url('/contact');
        $product->is_active = true;
        $product->display_sections = ['description', 'features', 'specifications', 'faq'];
        $product->save();

        // ── 3. Attach category ──
        $product->categories()->sync([$category->id]);
    }

    public function down(): void
    {
        Product::where('slug', 'seofast-framework')->forceDelete();
        ProductCategory::where('slug', 'seofast-framework')->forceDelete();
    }
};
