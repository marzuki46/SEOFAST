# SEOFAST V3 — Panduan Pengembangan Lengkap
## GSC Integration, Database Architecture & Full Development Blueprint

> **Versi:** 3.0 | **Stack:** Laravel 13 / PHP 8.4 / MYSQL + pgvector  
> **Tujuan:** Referensi teknis lengkap untuk vibe coding — dari database hingga closed-loop GSC

---

## DAFTAR ISI

1. [Prinsip Arsitektur Utama](#1-prinsip-arsitektur-utama)
2. [Master Database Schema — 16 Tabel Lengkap](#2-master-database-schema)
3. [Entity Relationship Diagram (ERD)](#3-entity-relationship-diagram)
4. [GSC Integration — Sistem Lengkap](#4-gsc-integration)
5. [Closed-Loop Pipeline — Alur Kerja Penuh](#5-closed-loop-pipeline)
6. [Laravel Implementation Guide](#6-laravel-implementation-guide)
7. [Urutan Pembangunan untuk Vibe Coding](#7-urutan-pembangunan)
8. [Checklist Pre-Launch SEO](#8-checklist-pre-launch-seo)

---

## 1. Prinsip Arsitektur Utama

### Filosofi Desain

```
SEOFAST V3 bukan CMS biasa.
Ini adalah SEO Operating System dengan prinsip:

1. ZERO ORPHAN PAGE     → Semua konten terhubung secara deterministik
2. ZERO SOFT FAILURE    → Bot tidak pernah lihat halaman rusak/kosong
3. ZERO DARK SHOT       → AI tidak tebak-tebakan, belajar dari data SERP nyata
4. ZERO CRAWL WASTE     → Setiap crawl budget digunakan untuk halaman bernilai
5. ZERO MANUAL REFRESH  → Konten memperbarui dirinya sendiri secara otomatis
```

### Stack Teknologi

| Layer | Teknologi | Alasan Dipilih |
|-------|-----------|----------------|
| Framework | Laravel 13 | Eloquent Global Scopes, Queue, Broadcasting |
| PHP | 8.4 | Fibers, property hooks, asymmetric visibility |
| Database | MYSQL | pgvector, JSONB indexing, table partitioning |
| Vector DB | pgvector extension | Zero ops, bisa JOIN langsung dengan tabel utama |
| Cache L1 | Cloudflare | Edge cache, HTML statis di 300+ PoP global |
| Cache L2 | Nginx fastcgi_cache | Fallback jika PHP-FPM down |
| Cache L3 | Redis + Cache Tags | Per tenant_id + silo_id invalidation |
| Cache L4 | PostgreSQL query cache | Node graph & navigasi berat |
| Queue | Laravel Horizon | Real-time monitoring, priority queue |
| Storage | S3/R2 | Rendered HTML, uploaded media |
| GSC | OAuth2 + Webmaster API | Source of truth ranking & indexation |
| SERP API | DataForSEO / Semrush | Posisi ranking harian |

---

## 2. Master Database Schema

### Overview 16 Tabel

```
CORE SaaS
├── tenants                    → Master data klien
├── tenant_settings            → Konfigurasi per klien
└── tenant_api_credentials     → 🆕 Penyimpanan OAuth GSC per tenant

CONTENT ARCHITECTURE  
├── silo_blueprints            → Topical map / node graph
├── contents                   → Master konten (direvisi)
├── deterministic_links        → Internal link yang dikunci
├── schema_markups             → 🆕 JSON-LD structured data
└── canonical_mappings         → 🆕 Tabel dedikasi canonical

SEO INTELLIGENCE
├── crawl_budget_rules         → 🆕 Aturan robots.txt & sitemap dinamis
├── seo_bot_logs               → Log crawl bot (partisi per bulan)
├── seo_feedback_loops         → Status indexasi & re-optimasi flag
└── serp_snapshots             → 🆕 Histori posisi ranking harian

GSC INTEGRATION (🆕 Modul Baru)
├── gsc_sync_logs              → 🆕 Log setiap sesi sinkronisasi GSC
├── gsc_url_inspections        → 🆕 Detail inspeksi URL dari GSC API
└── gsc_search_analytics       → 🆕 Data klik, impresi, CTR, posisi dari GSC

AI ENGINE
├── ai_generation_jobs         → 🆕 Pengganti ai_temporary_logs (lebih robust)
└── ai_reoptimization_queue    → 🆕 Antrian re-optimasi artikel yang turun ranking
```

---

### 2.1 Tabel Core SaaS

```php
// MIGRATION FILE: 2024_01_01_000001_create_saas_core_tables.php

Schema::create('tenants', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();                    // URL-friendly identifier
    $table->string('domain')->unique();                  // custom domain klien
    $table->string('subscription_plan');                 // free | starter | pro | enterprise
    $table->integer('ai_credit_balance')->default(0);
    $table->integer('monthly_url_quota')->default(100);  // batas generasi URL/bulan
    $table->integer('monthly_url_used')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamp('trial_ends_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
});

Schema::create('tenant_settings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->string('key');
    $table->longText('value')->nullable();
    $table->unique(['tenant_id', 'key']);
    $table->timestamps();

    // Contoh key yang digunakan sistem:
    // default_language, default_country, llm_provider,
    // llm_model, cqi_threshold, auto_reoptimize_enabled,
    // indexing_api_enabled, serp_tracking_enabled
});

// 🆕 TABEL BARU — Credential OAuth GSC & API keys per tenant
// Disimpan terpisah karena highly sensitive & punya lifecycle sendiri
Schema::create('tenant_api_credentials', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->enum('service', [
        'google_search_console',    // OAuth2 GSC
        'google_indexing_api',      // Service Account Indexing API
        'serp_tracker',             // DataForSEO / Semrush / dll
        'llm_provider',             // OpenAI / Anthropic / Gemini
        'cloudflare',               // Cache purge API
    ]);
    $table->text('access_token')->nullable();            // Encrypted
    $table->text('refresh_token')->nullable();           // Encrypted
    $table->text('service_account_json')->nullable();    // Encrypted — untuk Indexing API
    $table->string('property_url')->nullable();          // sc-domain:example.com
    $table->timestamp('token_expires_at')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->unique(['tenant_id', 'service']);
    $table->index(['tenant_id', 'service', 'is_active']);
});
```

---

### 2.2 Tabel Content Architecture

```php
// MIGRATION FILE: 2024_01_01_000002_create_content_architecture_tables.php

Schema::create('silo_blueprints', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->string('silo_name');
    $table->string('seed_keyword');
    $table->string('target_language', 10)->default('id');
    $table->string('target_country', 10)->default('ID');
    $table->json('visual_graph_data')->nullable();       // Koordinat UI Drawflow
    $table->boolean('is_locked')->default(false);        // Locked = tidak bisa edit link
    $table->integer('total_contents')->default(0);       // Denormalized counter
    $table->integer('published_contents')->default(0);   // Denormalized counter
    $table->timestamps();

    $table->index(['tenant_id', 'is_locked']);
});

Schema::create('contents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('silo_blueprint_id')->constrained()->cascadeOnDelete();

    // === SEO Identity ===
    $table->string('target_keyword');
    $table->string('slug');
    $table->string('meta_title', 70)->nullable();
    $table->string('meta_description', 160)->nullable();
    $table->enum('hierarchy_level', ['pillar', 'cluster', 'sub_cluster']);

    // === KGR & Quality Metrics ===
    $table->integer('search_volume')->default(0);
    $table->decimal('kgr_score', 5, 2)->nullable();      // < 0.25 = golden keyword
    $table->decimal('cqi_score', 5, 2)->nullable();      // Content Quality Index
    $table->decimal('semantic_depth_score', 5, 2)->nullable();
    $table->decimal('entity_coverage_score', 5, 2)->nullable();
    $table->decimal('readability_score', 5, 2)->nullable();

    // === Vector Embedding (pgvector) ===
    // Tipe 'vector' didukung oleh pgvector extension
    // Dimensi disesuaikan model: text-embedding-3-small = 1536
    // Jika menggunakan DB::statement langsung di migration:
    $table->string('vector_embedding_id', 100)->nullable();   // UUID dari pgvector
    $table->string('embedding_model_version', 50)->nullable(); // track model drift
    $table->timestamp('embedding_generated_at')->nullable();

    // === Content Body ===
    $table->longText('body_raw')->nullable();             // Markdown dari AI
    $table->string('rendered_html_path')->nullable();     // Path ke S3/R2 (BUKAN longText)
    $table->string('content_hash', 64)->nullable();       // SHA-256 untuk cache invalidation
    $table->boolean('last_render_intact')->default(true); // Render Integrity Flag

    // === Lifecycle ===
    $table->enum('status', [
        'blueprint',        // Hanya ada di silo, belum digenerate
        'ai_processing',    // Sedang dalam queue AI
        'failed_cqi',       // CQI < threshold, dikembalikan untuk revisi
        'canonicalized',    // Ditandai sebagai duplikat, ada canonical lain
        'published',        // Live di website
        'needs_reoptimize', // Ranking turun, menunggu re-optimasi
    ]);
    $table->timestamp('published_at')->nullable();
    $table->timestamp('last_partial_update_at')->nullable();  // Content freshness

    // === Indexation & Ranking (denormalized untuk performa) ===
    $table->string('gsc_coverage_state', 50)->nullable();     // Mirror dari GSC
    $table->integer('current_serp_position')->nullable();
    $table->timestamp('ranking_last_checked_at')->nullable();

    $table->timestamps();
    $table->softDeletes();

    $table->unique(['tenant_id', 'slug']);
    $table->index(['tenant_id', 'status']);                   // Query by status
    $table->index(['tenant_id', 'silo_blueprint_id', 'hierarchy_level']);
    $table->index(['tenant_id', 'published_at']);             // Freshness engine
    $table->index(['tenant_id', 'current_serp_position']);    // Ranking dashboard
    $table->index('gsc_coverage_state');                      // Filter by indexation
});

Schema::create('deterministic_links', function (Blueprint $table) {
    $table->id();
    $table->foreignId('source_content_id')->constrained('contents')->cascadeOnDelete();
    $table->foreignId('target_content_id')->constrained('contents')->cascadeOnDelete();
    $table->string('mandatory_anchor_text');
    $table->boolean('is_injected_successfully')->default(false);
    $table->timestamp('injected_at')->nullable();
    $table->timestamps();

    $table->unique(
        ['source_content_id', 'target_content_id', 'mandatory_anchor_text'],
        'unique_link_mapping'
    );
    $table->index('source_content_id');
    $table->index(['target_content_id', 'is_injected_successfully']);
});

// 🆕 TABEL BARU — JSON-LD Structured Data
Schema::create('schema_markups', function (Blueprint $table) {
    $table->id();
    $table->foreignId('content_id')->constrained()->cascadeOnDelete();
    $table->enum('schema_type', [
        'Article', 'FAQPage', 'HowTo', 'BreadcrumbList',
        'Product', 'LocalBusiness', 'WebPage', 'ItemList',
    ]);
    $table->json('schema_payload');                       // JSON-LD yang akan di-render
    $table->boolean('is_validated')->default(false);      // via Google Rich Results Test API
    $table->timestamp('validated_at')->nullable();
    $table->string('validation_issues')->nullable();      // Error dari Google Rich Results API
    $table->timestamps();

    $table->index(['content_id', 'schema_type']);
});

// 🆕 TABEL BARU — Canonical Mappings (berdiri sendiri)
Schema::create('canonical_mappings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('content_id')->constrained()->cascadeOnDelete();           // halaman duplikat
    $table->foreignId('canonical_target_id')->constrained('contents')->cascadeOnDelete(); // halaman asli
    $table->enum('reason', [
        'duplicate_intent',    // Terdeteksi AI via vector similarity
        'merge_pending',       // Menunggu digabung secara manual
        'manual_override',     // Admin set secara manual
        'pagination',          // Halaman 2,3,4 dari satu artikel
    ]);
    $table->decimal('similarity_score', 5, 4)->nullable(); // 0.8500 = 85% mirip
    $table->boolean('is_resolved')->default(false);
    $table->timestamp('resolved_at')->nullable();
    $table->timestamps();

    $table->index(['tenant_id', 'is_resolved']);
    $table->index('content_id');
    $table->index('canonical_target_id');
});
```

---

### 2.3 Tabel SEO Intelligence

```php
// MIGRATION FILE: 2024_01_01_000003_create_seo_intelligence_tables.php

// 🆕 TABEL BARU — Crawl Budget Rules (robots.txt & sitemap dinamis)
Schema::create('crawl_budget_rules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->enum('rule_type', [
        'noindex',          // Meta robots noindex
        'disallow',         // robots.txt Disallow
        'sitemap_exclude',  // Exclude dari sitemap.xml
        'crawl_delay',      // Tambah delay untuk bot tertentu
        'allow',            // Override disallow (robots.txt Allow)
    ]);
    $table->string('url_pattern');                        // /tag/*, /page/*, dll
    $table->string('bot_target')->default('*');           // * | Googlebot | Bingbot
    $table->string('reason')->nullable();                 // Audit trail
    $table->boolean('is_active')->default(true);
    $table->integer('priority')->default(0);              // Urutan evaluasi rules
    $table->timestamps();

    $table->index(['tenant_id', 'rule_type', 'is_active']);
});

// Log crawl bot — WAJIB partisi per bulan di level PostgreSQL
// Buat dengan DB::statement di migration, bukan Blueprint biasa
// Contoh:
// CREATE TABLE seo_bot_logs (
//     id BIGSERIAL,
//     tenant_id BIGINT NOT NULL,
//     url_path TEXT NOT NULL,
//     bot_identifier VARCHAR(100),
//     is_rdns_verified BOOLEAN DEFAULT false,
//     http_status_code SMALLINT,
//     response_time_ms INT,
//     cache_hit VARCHAR(20),          -- HIT | MISS | BYPASS | EXPIRED
//     cache_layer VARCHAR(20),        -- edge | nginx | redis | none
//     crawled_at TIMESTAMPTZ NOT NULL
// ) PARTITION BY RANGE (crawled_at);
// 
// CREATE TABLE seo_bot_logs_y2024m01
//     PARTITION OF seo_bot_logs
//     FOR VALUES FROM ('2024-01-01') TO ('2024-02-01');
//
// Buat Laravel migration dengan DB::statement untuk ini.

Schema::create('seo_feedback_loops', function (Blueprint $table) {
    $table->id();
    $table->foreignId('content_id')->constrained()->cascadeOnDelete();

    // === Crawl Priority ===
    $table->decimal('crawl_priority_score', 5, 2)->default(0);
    // Formula: (search_volume_weight * 0.4) + (link_depth_weight * 0.3) + (freshness_weight * 0.3)
    $table->timestamp('last_submitted_to_indexing_api_at')->nullable();
    $table->integer('indexing_api_submission_count')->default(0);

    // === GSC Indexation State ===
    // Nilai enum sesuai GSC API Coverage State:
    // Submitted and indexed | Crawled - currently not indexed | 
    // Discovered - currently not indexed | URL is unknown to Google |
    // Page with redirect | Excluded by 'noindex' tag | Blocked by robots.txt
    $table->string('gsc_coverage_state', 100)->nullable();
    $table->string('gsc_verdict', 20)->nullable();          // PASS | FAIL | NEUTRAL
    $table->string('gsc_indexing_state', 50)->nullable();   // INDEXING_ALLOWED | BLOCKED
    $table->string('gsc_robots_txt_state', 50)->nullable(); // ALLOWED | DISALLOWED
    $table->string('gsc_page_fetch_state', 50)->nullable(); // SUCCESSFUL | SOFT_404 | etc
    $table->timestamp('gsc_last_crawl_time')->nullable();
    $table->timestamp('gsc_last_sync_at')->nullable();

    // === SERP Feedback ===
    $table->integer('current_serp_position')->nullable();
    $table->integer('previous_serp_position')->nullable();
    $table->decimal('position_change', 5, 1)->nullable();   // +3 = naik 3 posisi
    $table->boolean('requires_ai_reoptimization')->default(false);
    $table->timestamp('reoptimization_triggered_at')->nullable();
    $table->integer('reoptimization_count')->default(0);    // Berapa kali sudah di-reoptimasi

    // === GSC Search Analytics (7-day rolling average) ===
    $table->integer('avg_clicks_7d')->nullable();
    $table->integer('avg_impressions_7d')->nullable();
    $table->decimal('avg_ctr_7d', 5, 4)->nullable();        // 0.0345 = 3.45%
    $table->decimal('avg_position_7d', 5, 2)->nullable();

    $table->timestamp('last_sync_at')->nullable();
    $table->timestamps();

    $table->index(['content_id', 'gsc_coverage_state']);
    $table->index(['requires_ai_reoptimization', 'reoptimization_triggered_at']);
});

// 🆕 TABEL BARU — Histori Ranking Harian (pengganti 2-titik-data di seo_feedback_loops)
Schema::create('serp_snapshots', function (Blueprint $table) {
    $table->id();
    $table->foreignId('content_id')->constrained()->cascadeOnDelete();
    $table->string('target_keyword');
    $table->string('target_country', 10)->default('ID');
    $table->string('target_device', 10)->default('desktop'); // desktop | mobile
    $table->tinyInteger('position')->nullable();             // 1-100, null = tidak ranking
    $table->string('ranking_url', 500)->nullable();          // URL yang actual ranking
    $table->json('serp_features')->nullable();
    // Contoh serp_features:
    // ["featured_snippet", "people_also_ask", "image_pack", "video_carousel", "ai_overview"]
    $table->date('snapshot_date');
    $table->timestamps();

    $table->index(['content_id', 'snapshot_date']);
    $table->index(['content_id', 'target_keyword', 'snapshot_date']);
    $table->unique(
        ['content_id', 'target_keyword', 'target_country', 'target_device', 'snapshot_date'],
        'unique_serp_snapshot'
    );
});
```

---

### 2.4 Tabel GSC Integration (Modul Baru Lengkap)

```php
// MIGRATION FILE: 2024_01_01_000004_create_gsc_integration_tables.php

// 🆕 TABEL BARU — Log setiap sesi sinkronisasi dengan GSC
Schema::create('gsc_sync_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->enum('sync_type', [
        'url_inspection',       // Cek status indexasi URL tertentu
        'search_analytics',     // Tarik data klik/impresi/posisi
        'sitemap_submission',   // Submit sitemap ke GSC
        'batch_indexing_api',   // Submit URL ke Google Indexing API
    ]);
    $table->enum('status', ['running', 'completed', 'failed', 'partial']);
    $table->integer('total_urls')->default(0);
    $table->integer('processed_urls')->default(0);
    $table->integer('failed_urls')->default(0);
    $table->json('error_summary')->nullable();              // Summary error per URL
    $table->integer('api_quota_used')->default(0);          // Berapa unit quota terpakai
    $table->decimal('duration_seconds', 8, 2)->nullable();
    $table->timestamp('started_at');
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();

    $table->index(['tenant_id', 'sync_type', 'status']);
    $table->index(['tenant_id', 'started_at']);
});

// 🆕 TABEL BARU — Hasil detail URL Inspection dari GSC API
// Ini adalah "foto" lengkap kondisi URL di mata Google
Schema::create('gsc_url_inspections', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('content_id')->constrained()->cascadeOnDelete();
    $table->foreignId('gsc_sync_log_id')->constrained()->cascadeOnDelete();

    // Data persis dari response GSC URL Inspection API
    // https://developers.google.com/webmaster-tools/v1/urlInspection.index/inspect

    // inspectionResult.indexStatusResult
    $table->string('verdict', 20)->nullable();
    // PASS = indexed, FAIL = not indexed, NEUTRAL = redirect/noindex, VERDICT_UNSPECIFIED
    
    $table->string('coverage_state', 100)->nullable();
    // "Submitted and indexed"
    // "Crawled - currently not indexed"
    // "Discovered - currently not indexed"
    // "Page with redirect"
    // "Excluded by 'noindex' tag"
    // "Blocked by robots.txt"
    
    $table->string('robots_txt_state', 50)->nullable();
    // ALLOWED | DISALLOWED
    
    $table->string('indexing_state', 50)->nullable();
    // INDEXING_ALLOWED | BLOCKED_BY_META_TAG | BLOCKED_BY_HTTP_HEADER | BLOCKED_BY_ROBOTS_TXT
    
    $table->string('page_fetch_state', 50)->nullable();
    // SUCCESSFUL | SOFT_404 | BLOCKED_ROBOTS_TXT | NOT_FOUND | SERVER_ERROR | etc
    
    $table->boolean('crawled_as_mobile')->nullable();
    $table->timestamp('last_crawl_time')->nullable();
    $table->string('canonical_declared_in_page')->nullable();   // Canonical tag di halaman
    $table->string('canonical_selected_by_google')->nullable(); // Canonical yang dipilih Google

    // inspectionResult.mobileUsabilityResult
    $table->string('mobile_usability_verdict', 20)->nullable(); // PASS | FAIL | VERDICT_UNSPECIFIED
    $table->json('mobile_usability_issues')->nullable();        // Array of issues

    // inspectionResult.richResultsResult
    $table->string('rich_results_verdict', 20)->nullable();     // PASS | FAIL | VERDICT_UNSPECIFIED
    $table->json('rich_results_items')->nullable();             // Detected rich result types

    // Raw response untuk audit
    $table->json('raw_api_response')->nullable();

    $table->timestamp('inspected_at');
    $table->timestamps();

    $table->index(['tenant_id', 'content_id', 'inspected_at']);
    $table->index(['tenant_id', 'verdict']);
    $table->index(['tenant_id', 'coverage_state']);
    $table->index(['content_id', 'inspected_at']);
});

// 🆕 TABEL BARU — Data Search Analytics dari GSC
// Klik, Impresi, CTR, Posisi per keyword per halaman per tanggal
Schema::create('gsc_search_analytics', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('content_id')->constrained()->cascadeOnDelete();

    // Dimensi dari GSC Search Analytics API
    $table->string('query');                                // Kata kunci pencarian
    $table->string('page_url', 500);                        // URL halaman (fullURL)
    $table->string('country', 10);                          // idn, usa, sgp, dll (ISO 3166-1 alpha-3)
    $table->string('device', 10);                           // DESKTOP | MOBILE | TABLET
    $table->date('data_date');                              // Tanggal data ini

    // Metrik
    $table->integer('clicks')->default(0);
    $table->integer('impressions')->default(0);
    $table->decimal('ctr', 7, 6)->default(0);               // 0.034500 = 3.45%
    $table->decimal('position', 6, 2)->default(0);          // Rata-rata posisi

    $table->timestamp('synced_at');
    $table->timestamps();

    // Composite unique key — satu baris per kombinasi dimensi per hari
    $table->unique(
        ['tenant_id', 'content_id', 'query', 'country', 'device', 'data_date'],
        'unique_gsc_analytics_row'
    );
    $table->index(['tenant_id', 'content_id', 'data_date']);
    $table->index(['tenant_id', 'query', 'data_date']);
    $table->index(['tenant_id', 'data_date']);
    $table->index(['content_id', 'data_date']);

    // Wajib tambahkan table partitioning by month di level PostgreSQL
    // untuk tabel ini karena akan sangat besar
});
```

---

### 2.5 Tabel AI Engine

```php
// MIGRATION FILE: 2024_01_01_000005_create_ai_engine_tables.php

// 🆕 Pengganti ai_temporary_logs yang lebih robust
Schema::create('ai_generation_jobs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('content_id')->constrained()->cascadeOnDelete();
    $table->enum('job_type', ['initial_generation', 'reoptimization', 'freshness_update']);
    $table->enum('status', ['pending', 'phase_1', 'phase_2', 'phase_3', 'phase_4', 'completed', 'failed']);
    
    // Output setiap fase — nullable karena bertahap
    $table->longText('phase_1_draft')->nullable();          // Drafter output
    $table->json('phase_2_critique')->nullable();           // Inquirer findings (JSON)
    $table->longText('phase_3_expanded')->nullable();       // Expander output
    $table->longText('phase_4_final')->nullable();          // Master Editor final HTML

    // Tracking
    $table->integer('tokens_used')->default(0);             // Total LLM tokens terpakai
    $table->string('llm_model_used')->nullable();           // gpt-4o | claude-3-5-sonnet | dll
    $table->decimal('generation_cost_usd', 10, 6)->nullable(); // Biaya dalam USD
    $table->json('error_log')->nullable();                  // Error detail per fase
    $table->integer('retry_count')->default(0);
    
    $table->timestamp('started_at')->nullable();
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();

    $table->index(['content_id', 'status']);
    $table->index(['job_type', 'status']);
});

// 🆕 Antrian re-optimasi — terpisah dari job generasi biasa
Schema::create('ai_reoptimization_queue', function (Blueprint $table) {
    $table->id();
    $table->foreignId('content_id')->constrained()->cascadeOnDelete();
    $table->enum('trigger_reason', [
        'ranking_dropped',      // Posisi turun setelah 30 hari
        'cqi_degraded',         // CQI turun (konten usang)
        'not_indexed',          // Masih crawled-not-indexed setelah 14 hari
        'ctr_low',              // CTR < rata-rata niche meskipun di halaman 1
        'manual_trigger',       // Admin trigger manual
    ]);
    $table->integer('position_before')->nullable();
    $table->integer('position_after')->nullable();
    $table->json('optimization_directives')->nullable();
    // Instruksi spesifik untuk AI:
    // {"add_entities": ["brand X", "harga Y"], "expand_faq": true, "update_stats": true}
    $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'skipped']);
    $table->integer('priority')->default(0);               // Makin tinggi = makin urgent
    $table->timestamp('scheduled_at')->nullable();
    $table->timestamp('processed_at')->nullable();
    $table->timestamps();

    $table->index(['status', 'priority', 'scheduled_at']);
    $table->index('content_id');
});
```

---

## 3. Entity Relationship Diagram (ERD)

```
┌─────────────┐         ┌──────────────────┐         ┌─────────────────────┐
│   tenants   │────1:N──│  silo_blueprints │────1:N──│      contents       │
│─────────────│         │──────────────────│         │─────────────────────│
│ id          │         │ id               │         │ id                  │
│ name        │         │ tenant_id (FK)   │         │ tenant_id (FK)      │
│ domain      │         │ silo_name        │         │ silo_blueprint_id   │
│ plan        │         │ seed_keyword     │         │ target_keyword      │
│ ...         │         │ visual_graph_data│         │ slug                │
└─────────────┘         │ is_locked        │         │ hierarchy_level     │
       │                └──────────────────┘         │ cqi_score           │
       │                                             │ status              │
       │                                             │ vector_embedding_id │
       │         ┌──────────────────────┐            └─────────────────────┘
       1:N        │  tenant_api_creds   │                      │
       │         │──────────────────────│                1:N   │   N:M (self)
       └────────▶│ tenant_id (FK)       │                      │
                 │ service (enum)       │         ┌────────────▼──────────────┐
                 │ access_token (enc)   │         │    deterministic_links    │
                 │ refresh_token (enc)  │         │───────────────────────────│
                 │ token_expires_at     │         │ source_content_id (FK)    │
                 └──────────────────────┘         │ target_content_id (FK)    │
                                                  │ mandatory_anchor_text     │
                                                  │ is_injected_successfully  │
                                                  └───────────────────────────┘

contents (lanjutan relasi)
       │
       ├──1:N──▶ schema_markups        (JSON-LD per halaman)
       ├──1:1──▶ seo_feedback_loops    (Status indexasi & SERP)
       ├──1:N──▶ serp_snapshots        (Histori ranking harian)
       ├──1:N──▶ gsc_url_inspections   (Hasil URL Inspection API)
       ├──1:N──▶ gsc_search_analytics  (Klik/Impresi/CTR per query)
       ├──1:N──▶ ai_generation_jobs    (Riwayat generasi AI)
       ├──1:N──▶ ai_reoptimization_queue (Antrian re-optimasi)
       ├──1:N──▶ canonical_mappings    (sebagai content_id = duplikat)
       └──1:N──▶ canonical_mappings    (sebagai canonical_target_id = asli)

tenants (lanjutan relasi)
       │
       ├──1:N──▶ crawl_budget_rules    (Aturan robots.txt & sitemap)
       ├──1:N──▶ seo_bot_logs          (Log crawl bot — partisi)
       └──1:N──▶ gsc_sync_logs         (Riwayat sinkronisasi GSC)
                       │
                       └──1:N──▶ gsc_url_inspections
```

---

## 4. GSC Integration — Sistem Lengkap

### 4.1 Arsitektur OAuth2 GSC

```
FLOW OAUTH2 GSC PER TENANT:

[Admin Tenant]
    │ Klik "Hubungkan Google Search Console"
    ▼
[Laravel Controller: GscOAuthController]
    │ Generate state token + simpan di session
    │ Build Google OAuth URL dengan scopes:
    │   - https://www.googleapis.com/auth/webmasters.readonly
    │   - https://www.googleapis.com/auth/indexing (untuk Indexing API)
    ▼
[Google OAuth Consent Screen]
    │ User login & setujui akses
    ▼
[Callback URL: /gsc/callback?code=xxx&state=xxx]
    │ Validasi state token (CSRF protection)
    │ Exchange code → access_token + refresh_token
    │ Encrypt tokens sebelum simpan
    ▼
[tenant_api_credentials]
    │ Simpan: access_token, refresh_token, token_expires_at
    │ Simpan: property_url (sc-domain:example.com)
    ▼
[Auto-refresh Token]
    │ Setiap API call, cek token_expires_at
    │ Jika < 5 menit, refresh otomatis via refresh_token
    │ Update tenant_api_credentials
```

### 4.2 Google Search Console Service

```php
// app/Services/Gsc/GoogleSearchConsoleService.php

namespace App\Services\Gsc;

use App\Models\TenantApiCredential;
use App\Models\GscSyncLog;
use App\Models\GscUrlInspection;
use App\Models\GscSearchAnalytic;
use App\Models\Content;
use Google\Client;
use Google\Service\SearchConsole;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

class GoogleSearchConsoleService
{
    private Client $client;
    private int $tenantId;
    private TenantApiCredential $credential;

    public function __construct(int $tenantId)
    {
        $this->tenantId = $tenantId;
        $this->credential = TenantApiCredential::where('tenant_id', $tenantId)
            ->where('service', 'google_search_console')
            ->where('is_active', true)
            ->firstOrFail();

        $this->client = $this->buildAuthenticatedClient();
    }

    /**
     * URL Inspection — Cek status satu URL di GSC
     * Quota: 2000 requests/day per property
     */
    public function inspectUrl(Content $content, GscSyncLog $syncLog): GscUrlInspection
    {
        $searchConsole = new SearchConsole($this->client);

        $requestBody = new SearchConsole\InspectUrlIndexRequest([
            'inspectionUrl'   => $this->buildFullUrl($content->slug),
            'siteUrl'         => $this->credential->property_url,
            'languageCode'    => 'id',
        ]);

        $response = $searchConsole->urlInspection_index->inspect($requestBody);
        $result   = $response->getInspectionResult();
        $indexStatus = $result->getIndexStatusResult();
        $mobile   = $result->getMobileUsabilityResult();
        $rich     = $result->getRichResultsResult();

        return GscUrlInspection::updateOrCreate(
            [
                'tenant_id'       => $this->tenantId,
                'content_id'      => $content->id,
                'gsc_sync_log_id' => $syncLog->id,
            ],
            [
                'verdict'                         => $indexStatus->getVerdict(),
                'coverage_state'                  => $indexStatus->getCoverageState(),
                'robots_txt_state'                => $indexStatus->getRobotsTxtState(),
                'indexing_state'                  => $indexStatus->getIndexingState(),
                'page_fetch_state'                => $indexStatus->getPageFetchState(),
                'crawled_as_mobile'               => $indexStatus->getCrawledAs() === 'MOBILE',
                'last_crawl_time'                 => $indexStatus->getLastCrawlTime()
                    ? Carbon::parse($indexStatus->getLastCrawlTime()) : null,
                'canonical_declared_in_page'      => $indexStatus->getPageClassification()
                    ?->getCanonicalUrl() ?? null,
                'canonical_selected_by_google'    => $indexStatus->getGoogleCanonical(),
                'mobile_usability_verdict'        => $mobile?->getVerdict(),
                'mobile_usability_issues'         => $mobile?->getIssues() ?? [],
                'rich_results_verdict'            => $rich?->getVerdict(),
                'rich_results_items'              => $rich?->getDetectedItems() ?? [],
                'raw_api_response'                => json_decode(json_encode($result), true),
                'inspected_at'                    => now(),
            ]
        );
    }

    /**
     * Search Analytics — Tarik data klik/impresi/posisi per query
     * Quota: 1200 requests/minute, data tersedia dengan delay 3-4 hari
     */
    public function fetchSearchAnalytics(
        string $startDate,
        string $endDate,
        int $tenantId
    ): array {
        $searchConsole = new SearchConsole($this->client);

        $request = new SearchConsole\SearchAnalyticsQueryRequest([
            'startDate'  => $startDate,  // format: 'YYYY-MM-DD'
            'endDate'    => $endDate,
            'dimensions' => ['query', 'page', 'country', 'device'],
            'rowLimit'   => 25000,       // Maksimum per request
            'dataState'  => 'final',     // final | all (final = data lengkap)
        ]);

        $response = $searchConsole->searchanalytics->query(
            $this->credential->property_url,
            $request
        );

        $rows    = $response->getRows() ?? [];
        $upserts = [];

        foreach ($rows as $row) {
            $keys = $row->getKeys();      // [query, page, country, device]
            [$query, $pageUrl, $country, $device] = $keys;

            // Temukan content_id dari URL
            $slug      = $this->extractSlug($pageUrl);
            $contentId = Content::where('tenant_id', $tenantId)
                ->where('slug', $slug)
                ->value('id');

            if (!$contentId) continue;

            $upserts[] = [
                'tenant_id'   => $tenantId,
                'content_id'  => $contentId,
                'query'       => $query,
                'page_url'    => $pageUrl,
                'country'     => strtolower($country),
                'device'      => strtolower($device),
                'data_date'   => $endDate,
                'clicks'      => (int) $row->getClicks(),
                'impressions' => (int) $row->getImpressions(),
                'ctr'         => round($row->getCtr(), 6),
                'position'    => round($row->getPosition(), 2),
                'synced_at'   => now(),
            ];
        }

        // Batch upsert untuk performa
        foreach (array_chunk($upserts, 500) as $chunk) {
            GscSearchAnalytic::upsert(
                $chunk,
                ['tenant_id', 'content_id', 'query', 'country', 'device', 'data_date'],
                ['clicks', 'impressions', 'ctr', 'position', 'synced_at']
            );
        }

        return [
            'total_rows'   => count($rows),
            'saved_rows'   => count($upserts),
            'skipped_rows' => count($rows) - count($upserts),
        ];
    }

    /**
     * Submit URL ke Google Indexing API
     * Membutuhkan Service Account (bukan OAuth user biasa)
     * Quota: 200 requests/day per property
     */
    public function submitToIndexingApi(array $urls): array
    {
        $serviceAccountCred = TenantApiCredential::where('tenant_id', $this->tenantId)
            ->where('service', 'google_indexing_api')
            ->firstOrFail();

        $serviceAccount = json_decode(
            Crypt::decryptString($serviceAccountCred->service_account_json), 
            true
        );

        $client = new Client();
        $client->setAuthConfig($serviceAccount);
        $client->addScope('https://www.googleapis.com/auth/indexing');
        $client->fetchAccessTokenWithAssertion();

        $accessToken = $client->getAccessToken()['access_token'];
        $results     = [];

        foreach ($urls as $url) {
            $response = \Http::withToken($accessToken)
                ->post('https://indexing.googleapis.com/v3/urlNotifications:publish', [
                    'url'  => $url,
                    'type' => 'URL_UPDATED',  // URL_UPDATED | URL_DELETED
                ]);

            $results[$url] = [
                'success'       => $response->successful(),
                'http_status'   => $response->status(),
                'response_body' => $response->json(),
            ];

            // Throttle — max 100 req/menit
            usleep(600000); // 0.6 detik
        }

        return $results;
    }

    private function buildAuthenticatedClient(): Client
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        $accessToken = Crypt::decryptString($this->credential->access_token);

        // Auto-refresh jika hampir expired
        if ($this->credential->token_expires_at?->subMinutes(5)->isPast()) {
            $refreshToken = Crypt::decryptString($this->credential->refresh_token);
            $client->setAccessToken(['refresh_token' => $refreshToken]);
            $newToken = $client->fetchAccessTokenWithRefreshToken();

            $this->credential->update([
                'access_token'     => Crypt::encryptString($newToken['access_token']),
                'token_expires_at' => now()->addSeconds($newToken['expires_in']),
            ]);

            $accessToken = $newToken['access_token'];
        }

        $client->setAccessToken($accessToken);
        return $client;
    }

    private function buildFullUrl(string $slug): string
    {
        $domain = \App\Models\Tenant::find($this->tenantId)->domain;
        return "https://{$domain}/{$slug}";
    }

    private function extractSlug(string $pageUrl): string
    {
        $path = parse_url($pageUrl, PHP_URL_PATH);
        return ltrim($path, '/');
    }
}
```

### 4.3 GSC Sync Jobs (Queue)

```php
// app/Jobs/Gsc/SyncUrlInspectionJob.php
// Dijadwalkan via Laravel Scheduler — setiap hari pukul 03:00

namespace App\Jobs\Gsc;

use App\Models\Content;
use App\Models\GscSyncLog;
use App\Services\Gsc\GoogleSearchConsoleService;
use App\Services\Seo\RankingFeedbackService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Middleware\RateLimited;

class SyncUrlInspectionJob implements ShouldQueue
{
    use Queueable;

    public int $tries    = 3;
    public int $timeout  = 3600; // 1 jam

    public function __construct(
        private readonly int $tenantId,
        private readonly int $batchSize = 50  // Sesuaikan dengan quota GSC
    ) {}

    public function middleware(): array
    {
        // Rate limiting: max 2000 request/hari per property
        // Spread over 24 jam = ~83 req/jam = 1.4 req/menit
        return [new RateLimited('gsc-url-inspection')];
    }

    public function handle(
        GoogleSearchConsoleService $gscService,
        RankingFeedbackService $feedbackService
    ): void {
        $syncLog = GscSyncLog::create([
            'tenant_id'  => $this->tenantId,
            'sync_type'  => 'url_inspection',
            'status'     => 'running',
            'started_at' => now(),
        ]);

        // Prioritaskan URL yang belum diinspeksi > 7 hari
        // atau yang status-nya tidak ideal
        $contents = Content::where('tenant_id', $this->tenantId)
            ->where('status', 'published')
            ->where(function ($query) {
                $query
                    ->whereNull('gsc_coverage_state')
                    ->orWhere('gsc_coverage_state', '!=', 'Submitted and indexed')
                    ->orWhereHas('latestUrlInspection', function ($q) {
                        $q->where('inspected_at', '<', now()->subDays(7));
                    });
            })
            ->orderByRaw("
                CASE 
                    WHEN gsc_coverage_state IS NULL THEN 1
                    WHEN gsc_coverage_state != 'Submitted and indexed' THEN 2
                    ELSE 3
                END
            ")
            ->limit($this->batchSize)
            ->get();

        $processed = 0;
        $failed    = 0;

        foreach ($contents as $content) {
            try {
                $inspection = $gscService->inspectUrl($content, $syncLog);

                // Update denormalized field di tabel contents
                $content->update([
                    'gsc_coverage_state' => $inspection->coverage_state,
                ]);

                // Trigger feedback logic
                $feedbackService->processInspectionResult($content, $inspection);

                $processed++;
            } catch (\Exception $e) {
                $failed++;
                \Log::error("GSC inspection failed for content {$content->id}: " . $e->getMessage());
            }

            // Throttle — GSC URL Inspection: jangan > 1 req/detik
            sleep(1);
        }

        $syncLog->update([
            'status'          => $failed > 0 && $processed === 0 ? 'failed' : 
                                 ($failed > 0 ? 'partial' : 'completed'),
            'total_urls'      => $contents->count(),
            'processed_urls'  => $processed,
            'failed_urls'     => $failed,
            'completed_at'    => now(),
            'duration_seconds'=> now()->diffInSeconds($syncLog->started_at),
        ]);
    }
}

// app/Jobs/Gsc/SyncSearchAnalyticsJob.php
class SyncSearchAnalyticsJob implements ShouldQueue
{
    use Queueable;

    public int $tries   = 3;
    public int $timeout = 7200;

    public function __construct(private readonly int $tenantId) {}

    public function handle(GoogleSearchConsoleService $gscService): void
    {
        $syncLog = GscSyncLog::create([
            'tenant_id'  => $this->tenantId,
            'sync_type'  => 'search_analytics',
            'status'     => 'running',
            'started_at' => now(),
        ]);

        // GSC punya delay 3-4 hari — ambil data dari 4 hari lalu
        $endDate   = now()->subDays(4)->format('Y-m-d');
        $startDate = now()->subDays(10)->format('Y-m-d'); // 7 hari rolling window

        $result = $gscService->fetchSearchAnalytics($startDate, $endDate, $this->tenantId);

        $syncLog->update([
            'status'           => 'completed',
            'processed_urls'   => $result['saved_rows'],
            'completed_at'     => now(),
            'duration_seconds' => now()->diffInSeconds($syncLog->started_at),
        ]);
    }
}
```

### 4.4 Ranking Feedback Service

```php
// app/Services/Seo/RankingFeedbackService.php

namespace App\Services\Seo;

use App\Models\Content;
use App\Models\GscUrlInspection;
use App\Models\SeoFeedbackLoop;
use App\Models\AiReoptimizationQueue;

class RankingFeedbackService
{
    /**
     * Proses hasil URL inspection dan trigger aksi yang tepat
     */
    public function processInspectionResult(Content $content, GscUrlInspection $inspection): void
    {
        $feedback = SeoFeedbackLoop::firstOrCreate(['content_id' => $content->id]);

        $feedback->update([
            'gsc_coverage_state'    => $inspection->coverage_state,
            'gsc_verdict'           => $inspection->verdict,
            'gsc_indexing_state'    => $inspection->indexing_state,
            'gsc_robots_txt_state'  => $inspection->robots_txt_state,
            'gsc_page_fetch_state'  => $inspection->page_fetch_state,
            'gsc_last_crawl_time'   => $inspection->last_crawl_time,
            'gsc_last_sync_at'      => now(),
            'last_sync_at'          => now(),
        ]);

        // Deteksi masalah dan trigger aksi
        $this->detectAndActOnIssues($content, $inspection, $feedback);
    }

    private function detectAndActOnIssues(
        Content $content,
        GscUrlInspection $inspection,
        SeoFeedbackLoop $feedback
    ): void {
        match ($inspection->coverage_state) {

            // Ideal — tidak perlu aksi
            'Submitted and indexed' => null,

            // Terindeks tapi bukan URL yang kita inginkan sebagai canonical
            'Duplicate without user-selected canonical' => 
                $this->flagCanonicalMismatch($content, $inspection),

            // Sudah dicrawl tapi tidak diindeks — cek konten
            'Crawled - currently not indexed' => 
                $this->triggerContentAudit($content, $feedback),

            // Belum pernah dicrawl — prioritaskan di sitemap
            'Discovered - currently not indexed' =>
                $this->escalateCrawlPriority($content, $feedback),

            // Diblokir — cek robots.txt / noindex tag
            'Excluded by \'noindex\' tag',
            'Blocked by robots.txt' =>
                $this->alertBlockedContent($content, $inspection),

            default => null,
        };
    }

    private function triggerContentAudit(Content $content, SeoFeedbackLoop $feedback): void
    {
        // Jika sudah crawled-not-indexed > 14 hari → trigger re-optimasi
        $daysSincePublish = $content->published_at?->diffInDays(now()) ?? 0;

        if ($daysSincePublish > 14 && $feedback->reoptimization_count < 3) {
            AiReoptimizationQueue::firstOrCreate(
                ['content_id' => $content->id, 'status' => 'pending'],
                [
                    'trigger_reason'          => 'not_indexed',
                    'status'                  => 'pending',
                    'priority'                => 8,
                    'optimization_directives' => json_encode([
                        'improve_eeat'        => true,
                        'add_author_bio'      => true,
                        'expand_depth'        => true,
                        'add_internal_links'  => true,
                    ]),
                    'scheduled_at'            => now()->addHours(2),
                ]
            );

            $content->update(['status' => 'needs_reoptimize']);
        }
    }

    private function escalateCrawlPriority(Content $content, SeoFeedbackLoop $feedback): void
    {
        // Naikkan crawl priority score dan submit ulang ke Indexing API
        $feedback->increment('crawl_priority_score', 20);
        
        // Dispatch job submit ke Indexing API
        \App\Jobs\Gsc\SubmitToIndexingApiJob::dispatch($content)->delay(now()->addMinutes(30));
    }

    private function flagCanonicalMismatch(Content $content, GscUrlInspection $inspection): void
    {
        if ($inspection->canonical_selected_by_google !== $inspection->canonical_declared_in_page) {
            \Log::warning("Canonical mismatch for content {$content->id}", [
                'declared' => $inspection->canonical_declared_in_page,
                'selected' => $inspection->canonical_selected_by_google,
            ]);
            // Bisa trigger notifikasi ke admin tenant
        }
    }

    private function alertBlockedContent(Content $content, GscUrlInspection $inspection): void
    {
        // Kirim notifikasi ke admin tenant — konten terpublish tapi diblokir
        \App\Notifications\BlockedContentAlert::dispatch($content, $inspection);
    }
}
```

---

## 5. Closed-Loop Pipeline — Alur Kerja Penuh

```
CLOSED-LOOP SEOFAST V3 — FULL FLOW

┌─────────────────────────────────────────────────────────────────┐
│                         CONTENT CREATION                        │
│                                                                 │
│  [Silo Architect] → [4-Phase AI] → [CQI Check] → [Published]  │
│                                          ↓                      │
│                                    CQI < 80?                    │
│                                     ↓                           │
│                              [AI Re-draft] ──────────┐         │
└───────────────────────────────────────────────────────┼─────────┘
                                                        │
┌───────────────────────────────────────────────────────▼─────────┐
│                      INDEXATION LOOP                            │
│                                                                 │
│  [Published] → [Sitemap Submit] → [Indexing API Submit]        │
│                                           ↓                     │
│                              [GSC URL Inspection] (harian)      │
│                                           ↓                     │
│                    ┌─────────────────────────────────┐          │
│                    │    Coverage State Check          │          │
│                    ├─────────────────────────────────┤          │
│                    │ ✅ Indexed          → Monitor   │          │
│                    │ ⚠️ Crawled-Not-Idx → Re-optimize│          │
│                    │ ⏳ Discovered      → Escalate   │          │
│                    │ 🚫 Blocked         → Alert      │          │
│                    └─────────────────────────────────┘          │
└─────────────────────────────────────────────────────────────────┘
                                │
┌───────────────────────────────▼─────────────────────────────────┐
│                       RANKING LOOP                              │
│                                                                 │
│  [GSC Search Analytics] → [SERP Snapshot] → [Trend Analysis]  │
│                                    ↓                            │
│                         Ranking turun > 5 posisi?              │
│                         setelah 30 hari?                        │
│                                    ↓                            │
│                    [ai_reoptimization_queue] (priority based)   │
│                                    ↓                            │
│              [AI Re-optimization] → [CQI Check] → [Re-publish] │
│                                    ↓                            │
│                         [Submit ke Indexing API]                │
│                                    ↓                            │
│                         [Monitor 14 hari]                       │
│                                    ↓                            │
│                         Tidak ada perbaikan?                    │
│                         → Eskalasi ke admin + Manual review     │
└─────────────────────────────────────────────────────────────────┘
```

---

## 6. Laravel Implementation Guide

### 6.1 Models Utama

```php
// app/Models/Content.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\TenantScope;

class Content extends Model
{
    use SoftDeletes;

    protected $casts = [
        'published_at'              => 'datetime',
        'last_partial_update_at'    => 'datetime',
        'ranking_last_checked_at'   => 'datetime',
        'embedding_generated_at'    => 'datetime',
        'cqi_score'                 => 'decimal:2',
        'kgr_score'                 => 'decimal:2',
    ];

    // Global Scope — SEMUA query otomatis filter by tenant_id
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());
    }

    // Relasi
    public function silo()         { return $this->belongsTo(SiloBlueprint::class, 'silo_blueprint_id'); }
    public function tenant()       { return $this->belongsTo(Tenant::class); }
    public function feedback()     { return $this->hasOne(SeoFeedbackLoop::class); }
    public function serpHistory()  { return $this->hasMany(SerpSnapshot::class); }
    public function schemaMarkups(){ return $this->hasMany(SchemaMarkup::class); }
    public function inboundLinks() { return $this->hasMany(DeterministicLink::class, 'target_content_id'); }
    public function outboundLinks(){ return $this->hasMany(DeterministicLink::class, 'source_content_id'); }
    public function aiJobs()       { return $this->hasMany(AiGenerationJob::class); }
    public function urlInspections(){ return $this->hasMany(GscUrlInspection::class); }
    public function latestUrlInspection() {
        return $this->hasOne(GscUrlInspection::class)->latestOfMany('inspected_at');
    }
    public function searchAnalytics(){ return $this->hasMany(GscSearchAnalytic::class); }

    // Scopes
    public function scopePublished($query)      { return $query->where('status', 'published'); }
    public function scopeNeedsReoptimize($query){ return $query->where('status', 'needs_reoptimize'); }
    public function scopeNotIndexed($query) {
        return $query->where('gsc_coverage_state', '!=', 'Submitted and indexed')
                     ->whereNotNull('gsc_coverage_state');
    }
}
```

### 6.2 Laravel Scheduler — Jadwal Sync GSC

```php
// routes/console.php

use Illuminate\Support\Facades\Schedule;
use App\Jobs\Gsc\SyncUrlInspectionJob;
use App\Jobs\Gsc\SyncSearchAnalyticsJob;
use App\Jobs\Seo\ProcessSerpSnapshotsJob;
use App\Jobs\Seo\ContentFreshnessJob;
use App\Jobs\Seo\CrawlPriorityRecalculateJob;

// Sync URL Inspection — setiap hari pukul 03:00 per tenant
Schedule::call(function () {
    \App\Models\TenantApiCredential::where('service', 'google_search_console')
        ->where('is_active', true)
        ->get()
        ->each(function ($cred) {
            SyncUrlInspectionJob::dispatch($cred->tenant_id)
                ->onQueue('gsc-sync');
        });
})->dailyAt('03:00');

// Sync Search Analytics — setiap hari pukul 04:00 (setelah URL inspection selesai)
Schedule::call(function () {
    \App\Models\TenantApiCredential::where('service', 'google_search_console')
        ->where('is_active', true)
        ->get()
        ->each(fn ($cred) => SyncSearchAnalyticsJob::dispatch($cred->tenant_id)->onQueue('gsc-sync'));
})->dailyAt('04:00');

// SERP Snapshot — setiap hari pukul 06:00
Schedule::job(ProcessSerpSnapshotsJob::class)->dailyAt('06:00')->onQueue('serp-tracking');

// Re-optimization Queue — setiap jam (proses antrian yang sudah scheduled)
Schedule::call(function () {
    \App\Models\AiReoptimizationQueue::where('status', 'pending')
        ->where('scheduled_at', '<=', now())
        ->orderByDesc('priority')
        ->limit(5) // Batasi per jam sesuai quota LLM
        ->get()
        ->each(fn ($item) => \App\Jobs\Ai\ProcessReoptimizationJob::dispatch($item)->onQueue('ai-heavy'));
})->hourly();

// Content Freshness Engine — setiap hari Senin pukul 02:00
Schedule::job(ContentFreshnessJob::class)->weeklyOn(1, '02:00')->onQueue('ai-heavy');

// Crawl Priority Recalculate — setiap hari pukul 01:00
Schedule::job(CrawlPriorityRecalculateJob::class)->dailyAt('01:00')->onQueue('maintenance');

// Purge bot logs lama (> 90 hari) — setiap minggu
Schedule::call(function () {
    \DB::table('seo_bot_logs')
        ->where('crawled_at', '<', now()->subDays(90))
        ->delete();
})->weekly();
```

### 6.3 Queue Configuration

```php
// config/horizon.php — Hierarchy Queue

'environments' => [
    'production' => [
        // Queue AI berat — 2 worker, timeout 30 menit
        'ai-heavy' => [
            'connection'  => 'redis',
            'queue'       => ['ai-heavy'],
            'balance'     => 'auto',
            'processes'   => 2,
            'tries'       => 3,
            'timeout'     => 1800,
        ],
        // GSC sync — 3 worker, dengan rate limiting
        'gsc-sync' => [
            'connection'  => 'redis',
            'queue'       => ['gsc-sync'],
            'balance'     => 'auto',
            'processes'   => 3,
            'tries'       => 3,
            'timeout'     => 3600,
        ],
        // SERP tracking — 5 worker, lebih cepat
        'serp-tracking' => [
            'connection'  => 'redis',
            'queue'       => ['serp-tracking'],
            'balance'     => 'auto',
            'processes'   => 5,
            'tries'       => 3,
            'timeout'     => 300,
        ],
        // Default maintenance — 2 worker
        'default' => [
            'connection'  => 'redis',
            'queue'       => ['default', 'maintenance'],
            'balance'     => 'auto',
            'processes'   => 2,
            'tries'       => 3,
            'timeout'     => 60,
        ],
    ],
],
```

---

## 7. Urutan Pembangunan untuk Vibe Coding

### FASE 1 — FONDASI (Minggu 1-2)
```
✅ Setup Laravel 13 + PostgreSQL + pgvector extension
✅ Install packages wajib:
   - google/apiclient (GSC + Indexing API)
   - predis/predis (Redis)
   - laravel/horizon (Queue monitoring)
   - spatie/laravel-permission (Role per tenant)

✅ Buat semua migrations (urutan penting!):
   1. tenants
   2. tenant_settings
   3. tenant_api_credentials       ← JANGAN LUPA encrypt tokens
   4. silo_blueprints
   5. contents                     ← Dengan semua indexes
   6. deterministic_links
   7. schema_markups
   8. canonical_mappings
   9. crawl_budget_rules
   10. seo_bot_logs                ← Dengan partisi PostgreSQL
   11. seo_feedback_loops
   12. serp_snapshots
   13. gsc_sync_logs
   14. gsc_url_inspections
   15. gsc_search_analytics        ← Dengan partisi PostgreSQL
   16. ai_generation_jobs
   17. ai_reoptimization_queue

✅ Global Scopes + Multi-tenant middleware
✅ Basic CRUD: Tenant, Silo, Content
```

### FASE 2 — RENDERING ENGINE (Minggu 3-4)
```
✅ Markdown → HTML pipeline
✅ Render Integrity Middleware (503 logic)
✅ 4-tier cache setup + invalidation
✅ Dynamic robots.txt dari crawl_budget_rules
✅ Dynamic sitemap.xml dengan priority score
✅ Schema markup injection (JSON-LD)
✅ Canonical tag engine
✅ Internal link injection dari deterministic_links
```

### FASE 3 — GSC INTEGRATION (Minggu 5-6) ← FOKUS FASE INI
```
✅ Google OAuth2 setup + callback handler
✅ Token encryption + auto-refresh
✅ GscService: inspectUrl()
✅ GscService: fetchSearchAnalytics()
✅ GscService: submitToIndexingApi()
✅ SyncUrlInspectionJob
✅ SyncSearchAnalyticsJob
✅ RankingFeedbackService
✅ GSC Dashboard (status indexasi semua URL)
✅ Laravel Scheduler setup
✅ Horizon setup + monitoring
```

### FASE 4 — AI PIPELINE (Minggu 7-9)
```
✅ 4-phase generation (queue-based per fase)
✅ CQI scoring engine
✅ pgvector: generate + store embeddings
✅ Duplicate detection (cosine similarity query)
✅ Canonical auto-suggestion
✅ AI Reoptimization pipeline
✅ Content Freshness Engine
```

### FASE 5 — SERP & ANALYTICS (Minggu 10-11)
```
✅ SERP API integration (DataForSEO/Semrush)
✅ Daily SERP snapshot
✅ Trend analysis (ranking chart)
✅ Auto re-optimize trigger
✅ CTR anomaly detection
✅ Crawl Priority Calculator
✅ Indexing API batch submission
```

### FASE 6 — SaaS & BILLING (Minggu 12)
```
✅ Subscription plan enforcement
✅ AI credit tracking
✅ Monthly URL quota
✅ Custom domain + SSL provisioning
✅ Tenant onboarding flow
✅ Admin super dashboard
```

---

## 8. Checklist Pre-Launch SEO

### Technical SEO
- [ ] Semua halaman punya `<title>` unik dan deskriptif (< 70 karakter)
- [ ] Meta description unik per halaman (< 160 karakter)
- [ ] Canonical tag terpasang di setiap halaman
- [ ] Hreflang (jika multi-bahasa)
- [ ] robots.txt valid dan tidak memblokir konten penting
- [ ] sitemap.xml dengan `<priority>` dan `<lastmod>` dinamis
- [ ] JSON-LD Schema.org minimal: Article + BreadcrumbList
- [ ] Core Web Vitals: LCP < 2.5s, INP < 200ms, CLS < 0.1
- [ ] HTTPS dengan valid SSL
- [ ] Mobile-friendly (Googlebot crawl as mobile)

### GSC Integration
- [ ] Property GSC terverifikasi (DNS atau HTML tag)
- [ ] OAuth token tersimpan dan bisa di-refresh
- [ ] Sitemap.xml sudah disubmit ke GSC
- [ ] URL Inspection berjalan untuk semua halaman live
- [ ] Search Analytics sync berjalan harian
- [ ] Indexing API service account sudah dikonfigurasi

### Content Architecture
- [ ] Tidak ada orphan page (semua ada inbound link)
- [ ] Semua deterministic_links berhasil diinjeksikan
- [ ] CQI score semua halaman > 80
- [ ] Tidak ada duplikat konten (similarity < 85%)
- [ ] Canonical mapping sudah diselesaikan

### Monitoring
- [ ] Laravel Horizon berjalan dan terpantau
- [ ] Alert jika queue latency > 5 menit
- [ ] Alert jika ada konten terblokir (noindex/robots.txt)
- [ ] Alert jika GSC sync gagal > 2 kali berturut-turut
- [ ] Alert jika ranking turun > 10 posisi dalam 7 hari

---

## Catatan Penting untuk Vibe Coding

### Packages yang Wajib Dipasang

```bash
composer require google/apiclient:^2.15
composer require predis/predis
composer require laravel/horizon
composer require spatie/laravel-permission
composer require pgvector/pgvector  # Jika ada Laravel wrapper
pip install pgvector  # Untuk script Python jika diperlukan

# PostgreSQL extension (di server/docker):
# CREATE EXTENSION IF NOT EXISTS vector;
# CREATE EXTENSION IF NOT EXISTS pg_trgm;  # Untuk full-text search
```

### Environment Variables yang Dibutuhkan

```env
# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=https://yourdomain.com/gsc/callback

# Encryption key untuk API tokens (HARUS BERBEDA dari APP_KEY)
CREDENTIAL_ENCRYPTION_KEY=

# SERP Tracking API
DATAFORSEO_LOGIN=
DATAFORSEO_PASSWORD=

# Storage
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=

# Redis
REDIS_HOST=
REDIS_PASSWORD=
REDIS_PORT=6379
```

---

*Dokumen ini adalah referensi hidup — update setiap kali ada perubahan arsitektur.*  
*Versi: 3.0 | Terakhir diperbarui: 2024*