# SEOFAST V3 — Panduan Pengembangan Lengkap
## GSC Integration, Database Architecture & Full Development Blueprint

> **Versi:** 3.1 | **Stack:** Laravel 13 / PHP 8.4 / MySQL + Socialite OAuth  
> **Tujuan:** Super SEO CMS — AI Content Factory + Digital Marketplace + Client Management  
> **Model:** WordPress-killer yang SEO-native, bukan plugin-based

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
9. [🆕 Buyer Portal & Digital Marketplace](#9-buyer-portal)
10. [🆕 Google OAuth Login — Admin & Buyer](#10-google-oauth)
11. [🆕 Client Management — Super Admin](#11-client-management)
12. [🆕 System Settings — Tabbed Architecture](#12-system-settings-tabs)

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
| Database | MySQL 8.0+ | JSON indexing, Full-text search, table partitioning |
| Auth | Laravel Socialite | Google OAuth untuk Admin & Buyer |
| Cache L1 | Cloudflare | Edge cache, HTML statis di 300+ PoP global |
| Cache L2 | Nginx fastcgi_cache | Fallback jika PHP-FPM down |
| Cache L3 | Redis + Cache Tags | Per user_id + silo_id invalidation |
| Cache L4 | MySQL query cache | Node graph & navigasi berat |
| Queue | Laravel Horizon | Real-time monitoring, priority queue |
| Storage | S3/R2 / Local | Rendered HTML, uploaded media, produk digital |
| GSC | OAuth2 + Webmaster API | Source of truth ranking & indexation |
| SERP API | DataForSEO / Semrush | Posisi ranking harian |
| Payment | Midtrans | Pembayaran produk digital dengan kode unik |
| Email | SMTP / Mailgun | Notifikasi order, akses produk, alert SEO |

---

## 2. Master Database Schema

### Hirarki Pengguna (Tanpa Multi-Tenant)
Sistem ini menggunakan model kepemilikan tunggal (Single Ownership CMS) dengan hirarki role sebagai berikut:
1. **Super Admin**: Pemilik tunggal platform ini yang memiliki kontrol penuh atas semua pengaturan, artikel, SEO, dan produk.
2. **User Pembantu**: Pengguna dengan akses terbatas yang diundang oleh Super Admin untuk membantu mengelola konten.
3. **Buyer**: Pembeli dari produk digital/layanan yang memiliki akses ke Buyer Portal.


### Overview 16 Tabel

```
CORE PLATFORM
├── users                    → Data pengguna/buyer (Bukan user)
├── user_settings            → Konfigurasi per klien
└── user_api_credentials     → 🆕 Penyimpanan OAuth GSC per user

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

Schema::create('users', function (Blueprint $table) {
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

Schema::create('user_settings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('key');
    $table->longText('value')->nullable();
    $table->unique(['user_id', 'key']);
    $table->timestamps();

    // Contoh key yang digunakan sistem:
    // default_language, default_country, llm_provider,
    // llm_model, cqi_threshold, auto_reoptimize_enabled,
    // indexing_api_enabled, serp_tracking_enabled
});

// 🆕 TABEL BARU — Credential OAuth GSC & API keys per user
// Disimpan terpisah karena highly sensitive & punya lifecycle sendiri
Schema::create('user_api_credentials', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
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

    $table->unique(['user_id', 'service']);
    $table->index(['user_id', 'service', 'is_active']);
});
```

---

### 2.2 Tabel Content Architecture

```php
// MIGRATION FILE: 2024_01_01_000002_create_content_architecture_tables.php

Schema::create('silo_blueprints', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('silo_name');
    $table->string('seed_keyword');
    $table->string('target_language', 10)->default('id');
    $table->string('target_country', 10)->default('ID');
    $table->json('visual_graph_data')->nullable();       // Koordinat UI Drawflow
    $table->boolean('is_locked')->default(false);        // Locked = tidak bisa edit link
    $table->integer('total_contents')->default(0);       // Denormalized counter
    $table->integer('published_contents')->default(0);   // Denormalized counter
    $table->timestamps();

    $table->index(['user_id', 'is_locked']);
});

Schema::create('contents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
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

    // === Vector Embedding (MySQL Vector) ===
    // Tipe 'vector' didukung oleh MySQL Vector extension
    // Dimensi disesuaikan model: text-embedding-3-small = 1536
    // Jika menggunakan DB::statement langsung di migration:
    $table->string('vector_embedding_id', 100)->nullable();   // UUID dari MySQL Vector
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

    $table->unique(['user_id', 'slug']);
    $table->index(['user_id', 'status']);                   // Query by status
    $table->index(['user_id', 'silo_blueprint_id', 'hierarchy_level']);
    $table->index(['user_id', 'published_at']);             // Freshness engine
    $table->index(['user_id', 'current_serp_position']);    // Ranking dashboard
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
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
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

    $table->index(['user_id', 'is_resolved']);
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
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
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

    $table->index(['user_id', 'rule_type', 'is_active']);
});

// Log crawl bot — WAJIB partisi per bulan di level MySQL
// Buat dengan DB::statement di migration, bukan Blueprint biasa
// Contoh:
// CREATE TABLE seo_bot_logs (
//     id BIGSERIAL,
//     user_id BIGINT NOT NULL,
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
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
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

    $table->index(['user_id', 'sync_type', 'status']);
    $table->index(['user_id', 'started_at']);
});

// 🆕 TABEL BARU — Hasil detail URL Inspection dari GSC API
// Ini adalah "foto" lengkap kondisi URL di mata Google
Schema::create('gsc_url_inspections', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
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

    $table->index(['user_id', 'content_id', 'inspected_at']);
    $table->index(['user_id', 'verdict']);
    $table->index(['user_id', 'coverage_state']);
    $table->index(['content_id', 'inspected_at']);
});

// 🆕 TABEL BARU — Data Search Analytics dari GSC
// Klik, Impresi, CTR, Posisi per keyword per halaman per tanggal
Schema::create('gsc_search_analytics', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
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
        ['user_id', 'content_id', 'query', 'country', 'device', 'data_date'],
        'unique_gsc_analytics_row'
    );
    $table->index(['user_id', 'content_id', 'data_date']);
    $table->index(['user_id', 'query', 'data_date']);
    $table->index(['user_id', 'data_date']);
    $table->index(['content_id', 'data_date']);

    // Wajib tambahkan table partitioning by month di level MySQL
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
│   users   │────1:N──│  silo_blueprints │────1:N──│      contents       │
│─────────────│         │──────────────────│         │─────────────────────│
│ id          │         │ id               │         │ id                  │
│ name        │         │ user_id (FK)   │         │ user_id (FK)      │
│ domain      │         │ silo_name        │         │ silo_blueprint_id   │
│ plan        │         │ seed_keyword     │         │ target_keyword      │
│ ...         │         │ visual_graph_data│         │ slug                │
└─────────────┘         │ is_locked        │         │ hierarchy_level     │
       │                └──────────────────┘         │ cqi_score           │
       │                                             │ status              │
       │                                             │ vector_embedding_id │
       │         ┌──────────────────────┐            └─────────────────────┘
       1:N        │  user_api_creds   │                      │
       │         │──────────────────────│                1:N   │   N:M (self)
       └────────▶│ user_id (FK)       │                      │
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

users (lanjutan relasi)
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
FLOW OAUTH2 GSC PER user:

[Admin user]
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
[user_api_credentials]
    │ Simpan: access_token, refresh_token, token_expires_at
    │ Simpan: property_url (sc-domain:example.com)
    ▼
[Auto-refresh Token]
    │ Setiap API call, cek token_expires_at
    │ Jika < 5 menit, refresh otomatis via refresh_token
    │ Update user_api_credentials
```

### 4.2 Google Search Console Service

```php
// app/Services/Gsc/GoogleSearchConsoleService.php

namespace App\Services\Gsc;

use App\Models\userApiCredential;
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
    private int $userId;
    private userApiCredential $credential;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->credential = userApiCredential::where('user_id', $userId)
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
                'user_id'       => $this->userId,
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
        int $userId
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
            $contentId = Content::where('user_id', $userId)
                ->where('slug', $slug)
                ->value('id');

            if (!$contentId) continue;

            $upserts[] = [
                'user_id'   => $userId,
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
                ['user_id', 'content_id', 'query', 'country', 'device', 'data_date'],
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
        $serviceAccountCred = userApiCredential::where('user_id', $this->userId)
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
        $domain = \App\Models\user::find($this->userId)->domain;
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
        private readonly int $userId,
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
            'user_id'  => $this->userId,
            'sync_type'  => 'url_inspection',
            'status'     => 'running',
            'started_at' => now(),
        ]);

        // Prioritaskan URL yang belum diinspeksi > 7 hari
        // atau yang status-nya tidak ideal
        $contents = Content::where('user_id', $this->userId)
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

    public function __construct(private readonly int $userId) {}

    public function handle(GoogleSearchConsoleService $gscService): void
    {
        $syncLog = GscSyncLog::create([
            'user_id'  => $this->userId,
            'sync_type'  => 'search_analytics',
            'status'     => 'running',
            'started_at' => now(),
        ]);

        // GSC punya delay 3-4 hari — ambil data dari 4 hari lalu
        $endDate   = now()->subDays(4)->format('Y-m-d');
        $startDate = now()->subDays(10)->format('Y-m-d'); // 7 hari rolling window

        $result = $gscService->fetchSearchAnalytics($startDate, $endDate, $this->userId);

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
            // Bisa trigger notifikasi ke admin user
        }
    }

    private function alertBlockedContent(Content $content, GscUrlInspection $inspection): void
    {
        // Kirim notifikasi ke admin user — konten terpublish tapi diblokir
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
use App\Models\Scopes\userScope;

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

    // Global Scope — SEMUA query otomatis filter by user_id
    protected static function booted(): void
    {
        static::addGlobalScope(new userScope());
    }

    // Relasi
    public function silo()         { return $this->belongsTo(SiloBlueprint::class, 'silo_blueprint_id'); }
    public function user()       { return $this->belongsTo(user::class); }
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

// Sync URL Inspection — setiap hari pukul 03:00 per user
Schedule::call(function () {
    \App\Models\userApiCredential::where('service', 'google_search_console')
        ->where('is_active', true)
        ->get()
        ->each(function ($cred) {
            SyncUrlInspectionJob::dispatch($cred->user_id)
                ->onQueue('gsc-sync');
        });
})->dailyAt('03:00');

// Sync Search Analytics — setiap hari pukul 04:00 (setelah URL inspection selesai)
Schedule::call(function () {
    \App\Models\userApiCredential::where('service', 'google_search_console')
        ->where('is_active', true)
        ->get()
        ->each(fn ($cred) => SyncSearchAnalyticsJob::dispatch($cred->user_id)->onQueue('gsc-sync'));
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
Schedule::job(CrawlPriorityRecalculateJob::class)->dailyAt('01:00')->onQueue('mainuserce');

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
        // Default mainuserce — 2 worker
        'default' => [
            'connection'  => 'redis',
            'queue'       => ['default', 'mainuserce'],
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
✅ Setup Laravel 13 + MySQL + MySQL Vector extension
✅ Install packages wajib:
   - google/apiclient (GSC + Indexing API)
   - predis/predis (Redis)
   - laravel/horizon (Queue monitoring)
   - spatie/laravel-permission (Role per user)

✅ Buat semua migrations (urutan penting!):
   1. users
   2. user_settings
   3. user_api_credentials       ← JANGAN LUPA encrypt tokens
   4. silo_blueprints
   5. contents                     ← Dengan semua indexes
   6. deterministic_links
   7. schema_markups
   8. canonical_mappings
   9. crawl_budget_rules
   10. seo_bot_logs                ← Dengan partisi MySQL
   11. seo_feedback_loops
   12. serp_snapshots
   13. gsc_sync_logs
   14. gsc_url_inspections
   15. gsc_search_analytics        ← Dengan partisi MySQL
   16. ai_generation_jobs
   17. ai_reoptimization_queue

✅ Global Scopes + Multi-user middleware
✅ Basic CRUD: user, Silo, Content
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
✅ Phase Image Selection & WebP Conversion (mengambil gambar dari penyedia gratis, resize, optimasi WebP otomatis, simpan ke lokal dan inject markdown)
✅ CQI scoring engine
✅ MySQL Vector: generate + store embeddings
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
✅ user onboarding flow
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
composer require MySQL Vector/MySQL Vector  # Jika ada Laravel wrapper
pip install MySQL Vector  # Untuk script Python jika diperlukan

# MySQL extension (di server/docker):
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
==================================================
# SEOFAST V3 - Alur Kerja Closed-Loop Pipeline

Dokumen ini menjelaskan 6 fase utama dalam arsitektur pembuatan konten dan manajemen SEO yang serba terotomatisasi di SEOFAST V3.

---

## 1. Input Keyword & Topical Map
*(Seed keyword → klaster keyword → silo blueprint)*

*   **Input Seed Keyword**: Admin memasukkan seed keyword utama (contoh: "jasa SEO Surabaya"). Sistem menyimpan ke `silo_blueprints.seed_keyword` dan mencatat bahasa + negara target.
*   **AI Generate Keyword Cluster**: LLM memecah seed keyword menjadi: 1 pillar page, N cluster pages (volume sedang), dan M sub-cluster pages (long-tail, KGR < 0.25). Setiap keyword dicek KGR score dan search volume via API.
*   **Visual Silo Architect**: Struktur hierarki divisualisasikan di UI Drawflow. Admin bisa drag-drop, tambah/hapus node. Setelah dikonfirmasi, silo di-lock. Koordinat node tersimpan di `visual_graph_data` (JSON).

**Output fase ini**: Tabel `contents` terisi semua baris dengan status `blueprint` — belum ada konten, hanya keyword + hierarki + slug yang sudah direncanakan.

---

## 2. Rekayasa Internal Link
*(Tentukan siapa link ke siapa, pakai anchor text apa)*

*   **AI Mapping Link Antar Konten**: AI menganalisis semua keyword di silo, lalu membuat peta link: pillar → cluster (pakai *exact match anchor*), cluster → sub-cluster (pakai *partial match*), sub-cluster → pillar (pakai *branded anchor*). Tidak boleh ada orphan page.
*   **Simpan ke `deterministic_links`**: Setiap pasangan source → target + anchor text yang wajib dipakai disimpan dan dikunci. Saat AI menulis konten nanti, sistem akan memaksa anchor ini diinjeksikan. Field `is_injected_successfully` akan diupdate saat rendering.
*   **Visualisasi Link Graph**: Semua link divisualisasikan sebagai garis di atas node graph Drawflow. Admin bisa review dan override manual sebelum proses lanjut. Setelah approved, `silo_blueprints.is_locked = true`.

**Output fase ini**: Tabel `deterministic_links` terisi lengkap. Setiap halaman sudah tahu akan dapat link dari mana dan akan link ke mana — sebelum satu kata pun ditulis.

---

## 3. Pre-render URL (Ghost Publish)
*(Semua URL aktif, konten belum ada — siap untuk GSC)*

*   **Generate Slug & Register Route**: Semua slug dari tabel `contents` diregistrasi sebagai URL aktif di Laravel. Akses ke URL ini saat status blueprint mengembalikan halaman placeholder bertag `noindex` — Googlebot tidak mengindeks, tapi URL sudah "ada".
*   **Sitemap & Crawl Priority Pre-load**: URL yang sudah siap publikasi dimasukkan ke `sitemap.xml` dinamis dengan `<priority>` berdasarkan `crawl_priority_score`. URL blueprint tidak masuk sitemap. `robots.txt` di-generate dari `crawl_budget_rules`.
*   **Kalkulasi Crawl Priority Score**: Formula: `(search_volume_weight × 0.4) + (hierarchy_level_weight × 0.3) + (inbound_links_count × 0.3)`. Halaman pillar selalu dapat skor tertinggi dan diprioritaskan untuk diisi konten lebih dulu.

**Output fase ini**: Semua URL terdaftar dan terurut berdasarkan prioritas. Queue pembuatan konten sudah diisi ke Laravel Horizon — siap diproses AI secara berurutan dari prioritas tertinggi.

---

## 4. Pemilihan Gambar
*(Admin pilih gambar sebelum AI nulis — kontrol kualitas visual)*

*   **Pencarian Gambar dari Free Provider**: Sistem terintegrasi dengan Unsplash API, Pexels API, dan Pixabay API. Berdasarkan target keyword konten, sistem menampilkan 10-20 kandidat gambar. Admin memilih mana yang sesuai.
*   **Auto-processing Gambar Terpilih**: Setelah admin pilih: 
    1. File diunduh dan disimpan ke S3/R2. 
    2. Filename di-rename jadi `target-keyword-deskripsi.webp`. 
    3. Alt text di-generate AI berdasarkan konteks artikel + keyword target. 
    4. Gambar dikompresi ke WebP. 
    5. Width/height dicatat untuk mencegah CLS.
*   **Simpan Metadata Gambar**: Semua metadata tersimpan (path S3, alt text, title tag gambar, dimensi, ukuran file, atribusi lisensi, hash SHA-256). Data ini akan disuntikkan AI saat menulis konten.

> **Mengapa gambar dipilih duluan?** AI yang menulis konten akan langsung menerima `image_url` + `alt_text` + `caption` sebagai bagian dari promptnya — sehingga gambar terintegrasi secara natural ke dalam narasi, bukan ditempel belakangan. Ini juga mencegah AI membuat referensi ke gambar yang tidak ada.

---

## 5. 4-Phase AI Content Factory
*(AI 1 → AI 2 → AI 1 → AI 3 — dengan internal link wajib)*

*   **Fase 1 — AI Drafter (AI 1)**: Prompt berisi target keyword, search volume, hierarki, intent pengguna, dan metadata gambar. AI menulis outline + draft lengkap. Output disimpan ke `ai_generation_jobs.phase_1_draft`.
*   **Fase 2 — AI Inquirer (AI 2)**: AI kedua (model yang lebih kritis) me-review draft. Tugasnya: mencari intent yang terlewat, pertanyaan yang belum dijawab, dan celah konten vs kompetitor top 10. Output berupa JSON critique.
*   **Fase 3 — AI Expander (AI 1 kembali)**: AI 1 menerima draft awal + critique dari AI 2. Tugasnya: menjawab semua celah, menambah entitas, memperkuat argumen, dan menambah FAQ. Output adalah versi yang jauh lebih mendalam.
*   **Fase 4 — Master Editor (AI 3) & Inject Link**: AI 3 (editor senior) memoles copywriting agar natural dan engaging, lalu **menyisipkan setiap anchor text wajib (dari `deterministic_links`) dan gambar** di posisi yang paling relevan. Format akhir berupa HTML semantik.
*   **CQI Check Otomatis**: Sistem menghitung CQI score: `(semantic_depth × 0.4) + (entity_coverage × 0.3) + (readability × 0.3)`. Jika CQI < 80, status jadi `failed_cqi` dan dikembalikan ke Fase 1 dengan instruksi perbaikan (maksimal 3 kali retry).

> **Mengapa AI 1 dipakai 2 kali?** Fase 1 dan 3 menggunakan model yang sama karena memiliki konteks penuh artikel. AI 2 sengaja berbeda supaya reviewnya objektif. AI 3 adalah model dengan kemampuan instruction-following terbaik untuk injeksi anchor text secara akurat.

---

## 6. Publish & Closed-Loop
*(Konten live, GSC monitor, auto re-optimasi jika turun)*

*   **Render & Publish**: HTML final disimpan, hash SHA-256 dicatat, status `published`. Render Integrity Middleware memvalidasi ukuran DOM (mengembalikan 503 jika rusak agar bot tidak indeks).
*   **Submit ke Google Indexing API**: URL prioritas tertinggi disubmit secara batch. Sitemap diperbarui, canonical dipasang, JSON-LD schema markup diinjeksikan.
*   **GSC Sync Harian & Ranking Monitor**: (1) URL Inspection API untuk coverage state. (2) Search Analytics API untuk klik/impresi/CTR/posisi. (3) SERP snapshot harian untuk tren.
*   **Auto Re-optimasi Jika Ranking Turun**: Jika posisi turun > 5 tempat dalam 30 hari, URL masuk ke `ai_reoptimization_queue` dengan instruksi perbaikan (tambah FAQ, update statistik). Pipeline 4-fase diulang tanpa mengubah URL / anchor text yang sudah ada.

> **Siklus Abadi**: Loop tidak pernah berhenti. Setelah publish, sistem belajar dari data GSC nyata. Konten yang bagus dipertahankan, yang turun di-rescue secara otomatis tanpa intervensi manual.

---

*Dokumen ini adalah referensi hidup — update setiap kali ada perubahan arsitektur.*  
*Versi: 3.1 | Terakhir diperbarui: 2026-06-24*

---

## 9. Buyer Portal & Digital Marketplace

### Filosofi Buyer Portal

```
SEOFAST bukan hanya alat SEO — ini juga platform penjualan produk digital.
Pembeli mendapat panel sendiri yang TERPISAH dari admin/user panel.
Mereka bisa:
  1. Daftar/login menggunakan akun Google (satu klik)
  2. Melihat produk yang tersedia dan membeli
  3. Mengakses produk digital yang sudah dibeli
  4. Melihat riwayat transaksi lengkap
```

### Alur Pembelian Lengkap

```
[Halaman Produk /produk/{slug}]
    ↓ Klik tombol "Beli Sekarang"
[Login/Register Buyer — Google OAuth]
    ↓ Authenticated
[Checkout Form]
    ↓ Tampilkan nomor rekening + kode unik (cth: Rp 297.743)
[Buyer Transfer ke Rekening Admin]
    ↓ Upload bukti atau notifikasi manual
[Admin verifikasi di panel order]
    ↓ Klik "Verify & Grant Access"
[Buyer Portal aktif — produk bisa diakses]
    ↓ Email notifikasi dikirim ke buyer
[Buyer akses produk digital di /buyer/products/{id}]
```

### Tabel Database Buyer

```php
// Migration: buyers (model terpisah dari users)
Schema::create('buyers', function (Blueprint $table) {
    $table->id();
    $table->string('google_id')->nullable()->unique();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('avatar')->nullable();
    $table->string('password')->nullable();        // null jika login Google
    $table->timestamp('email_verified_at')->nullable();
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();

    $table->index('email');
    $table->index('google_id');
});

// Migration: buyer_orders
Schema::create('buyer_orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('buyer_id')->constrained('buyers')->cascadeOnDelete();
    $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
    $table->string('unique_code', 10);             // Kode unik 3 digit di belakang nominal
    $table->decimal('amount', 12, 2);
    $table->decimal('unique_amount', 12, 2);       // amount + kode unik
    $table->enum('status', ['pending', 'paid', 'verified', 'rejected', 'refunded']);
    $table->string('payment_proof')->nullable();   // Path upload bukti transfer
    $table->string('payment_method')->nullable();  // transfer, qris, dll
    $table->text('admin_note')->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->timestamp('verified_at')->nullable();
    $table->foreignId('verified_by')->nullable()->constrained('users');
    $table->timestamps();

    $table->index(['buyer_id', 'status']);
    $table->index(['product_id', 'status']);
    $table->unique(['buyer_id', 'product_id', 'unique_code']);
});

// Migration: buyer_product_accesses
Schema::create('buyer_product_accesses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('buyer_id')->constrained('buyers')->cascadeOnDelete();
    $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
    $table->foreignId('order_id')->constrained('buyer_orders')->cascadeOnDelete();
    $table->timestamp('granted_at');
    $table->timestamp('expires_at')->nullable();   // null = lifetime access
    $table->integer('access_count')->default(0);
    $table->timestamp('last_accessed_at')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->unique(['buyer_id', 'product_id']);
    $table->index(['buyer_id', 'is_active']);
});
```

### Routes Buyer Portal

```php
// routes/web.php — Buyer Routes (guard: 'buyer')
Route::prefix('buyer')->name('buyer.')->group(function () {
    
    // Public — belum login
    Route::middleware('guest:buyer')->group(function () {
        Route::get('/login', [BuyerAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [BuyerAuthController::class, 'login'])->name('login.post');
        Route::get('/register', [BuyerAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [BuyerAuthController::class, 'register'])->name('register.post');
        Route::get('/auth/google', [BuyerAuthController::class, 'googleRedirect'])->name('auth.google');
        Route::get('/auth/google/callback', [BuyerAuthController::class, 'googleCallback'])->name('auth.google.callback');
    });

    // Protected — sudah login sebagai buyer
    Route::middleware('auth:buyer')->group(function () {
        Route::post('/logout', [BuyerAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [BuyerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [BuyerOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/upload-proof', [BuyerOrderController::class, 'uploadProof'])->name('orders.upload_proof');
        Route::get('/products', [BuyerProductController::class, 'index'])->name('products.index');
        Route::get('/products/{access}', [BuyerProductController::class, 'access'])->name('products.access');
        Route::get('/profile', [BuyerProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [BuyerProfileController::class, 'update'])->name('profile.update');
    });
});

// Public product sales pages (SEO-friendly)
Route::get('/produk', [ProductSalesController::class, 'index'])->name('products.catalog');
Route::get('/produk/{slug}', [ProductSalesController::class, 'show'])->name('products.show');
Route::post('/produk/{product}/order', [ProductSalesController::class, 'order'])->name('products.order');
```

### Fitur Halaman Produk (SEO Landing Page)

- URL: `/produk/{slug}` — halaman penjualan yang fully SEO-optimized
- Schema markup: `Product` + `Offer` + `AggregateRating`
- Deskripsi produk bisa di-render dari konten AI (WYSIWYG)
- Penawaran produk lain di bawah (cross-sell section)
- Tombol beli → redirect ke buyer login jika belum login
- Social proof: jumlah pembeli, rating, testimonial

---

## 10. Google OAuth Login — Admin & Buyer

### Dua Konteks OAuth yang Berbeda

```
┌──────────────────────┬──────────────────────────┬───────────────────────────┐
│ Konteks              │ Guard                    │ Model                     │
├──────────────────────┼──────────────────────────┼───────────────────────────┤
│ Admin / user Login │ web (default)            │ User → users.google_id    │
│ Buyer Login          │ buyer                    │ Buyer → buyers.google_id  │
│ GSC Connection       │ web (admin sudah login)  │ userApiCredential       │
└──────────────────────┴──────────────────────────┴───────────────────────────┘
PENTING: Gunakan REDIRECT_URI berbeda untuk setiap konteks!
```

### Setup Laravel Socialite

```bash
composer require laravel/socialite
```

```php
// config/services.php
'google' => [
    'client_id'     => env('GOOGLE_OAUTH_CLIENT_ID'),
    'client_secret' => env('GOOGLE_OAUTH_CLIENT_SECRET'),
    'redirect'      => env('GOOGLE_OAUTH_REDIRECT_URI'),
],
'google_buyer' => [
    'client_id'     => env('GOOGLE_BUYER_CLIENT_ID'),     // Bisa sama atau berbeda
    'client_secret' => env('GOOGLE_BUYER_CLIENT_SECRET'),
    'redirect'      => env('GOOGLE_BUYER_REDIRECT_URI'),
],
```

### Controller: Admin Google OAuth

```php
// app/Http/Controllers/Auth/GoogleAuthController.php
namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'           => $googleUser->getName(),
                'google_id'      => $googleUser->getId(),
                'avatar'         => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ]
        );

        Auth::login($user, remember: true);

        return redirect()->route('dashboard');
    }
}
```

### Controller: Buyer Google OAuth

```php
// app/Http/Controllers/Buyer/BuyerAuthController.php
namespace App\Http\Controllers\Buyer;

use Laravel\Socialite\Facades\Socialite;
use App\Models\Buyer;
use Illuminate\Support\Facades\Auth;

class BuyerAuthController extends Controller
{
    public function googleRedirect()
    {
        return Socialite::driver('google')
            ->with(['redirect_uri' => config('services.google_buyer.redirect')])
            ->redirect();
    }

    public function googleCallback()
    {
        $googleUser = Socialite::driver('google')
            ->with(['redirect_uri' => config('services.google_buyer.redirect')])
            ->user();

        $buyer = Buyer::updateOrCreate(
            ['google_id' => $googleUser->getId()],
            [
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'avatar'            => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ]
        );

        Auth::guard('buyer')->login($buyer, remember: true);

        return redirect()->route('buyer.dashboard');
    }
}
```

### Custom Guard untuk Buyer

```php
// config/auth.php
'guards' => [
    'web' => [
        'driver'   => 'session',
        'provider' => 'users',
    ],
    'buyer' => [
        'driver'   => 'session',
        'provider' => 'buyers',
    ],
],
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model'  => App\Models\User::class,
    ],
    'buyers' => [
        'driver' => 'eloquent',
        'model'  => App\Models\Buyer::class,
    ],
],
```

### ENV Variables untuk OAuth

```env
# Google OAuth — Admin Login
GOOGLE_OAUTH_CLIENT_ID=
GOOGLE_OAUTH_CLIENT_SECRET=
GOOGLE_OAUTH_REDIRECT_URI=https://domain.com/auth/google/callback

# Google OAuth — Buyer Login
GOOGLE_BUYER_CLIENT_ID=                   # Bisa sama dengan admin
GOOGLE_BUYER_CLIENT_SECRET=
GOOGLE_BUYER_REDIRECT_URI=https://domain.com/buyer/auth/google/callback

# Google OAuth — GSC Connection (berbeda scope)
GOOGLE_CLIENT_ID=                         # Khusus GSC + Indexing API scope
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=https://domain.com/gsc/callback
```

---

## 11. Client Management — Super Admin

### Konsep

```
Super Admin mengelola semua user (klien bisnis) yang menggunakan platform SEOFAST.
Ini adalah B2B layer di atas sistem multi-user yang sudah ada.

Hierarki akses:
  Super Admin → Lihat & kelola semua user
  user Admin → Kelola konten di user sendiri
  Buyer        → Akses produk digital yang dibeli
```

### Fitur Client Management

```
/clients                    → List semua klien + statistik usage
/clients/create             → Onboard klien baru (buat user baru)
/clients/{id}               → Detail klien: konten, traffic, billing
/clients/{id}/edit          → Edit konfigurasi: plan, quota, domain
/clients/{id}/suspend       → Suspend akun klien
/clients/{id}/reactivate    → Reaktivasi akun
/clients/{id}/login-as      → Login impersonasi (Super Admin masuk sebagai klien)
/clients/{id}/reset-quota   → Reset monthly URL quota
/clients/{id}/billing       → Riwayat pembayaran klien
```

### Database Client (Extend user)

```php
// Tambah kolom ke tabel users
Schema::table('users', function (Blueprint $table) {
    $table->string('contact_email')->nullable()->after('domain');
    $table->string('contact_phone')->nullable()->after('contact_email');
    $table->string('company_name')->nullable()->after('contact_phone');
    $table->text('notes')->nullable();                    // Catatan internal admin
    $table->timestamp('suspended_at')->nullable();
    $table->string('suspended_reason')->nullable();
    $table->timestamp('contract_start_at')->nullable();
    $table->timestamp('contract_end_at')->nullable();
    $table->decimal('monthly_rate', 12, 2)->nullable();   // Harga langganan
});
```

### Impersonation (Login as Client)

```php
// SuperAdminController.php
public function loginAs(user $user)
{
    // Simpan session super admin
    session(['impersonating_as' => auth()->id()]);
    
    // Ganti ke user admin user
    $userAdmin = $user->users()->where('role', 'admin')->first();
    Auth::login($userAdmin);
    
    return redirect()->route('dashboard')
        ->with('impersonation_notice', "Anda sedang masuk sebagai klien: {$user->name}");
}

public function stopImpersonation()
{
    $originalAdminId = session('impersonating_as');
    session()->forget('impersonating_as');
    
    Auth::loginUsingId($originalAdminId);
    
    return redirect()->route('clients.index');
}
```

---

## 12. System Settings — Tabbed Architecture

### Prinsip Desain Settings

```
Settings dibagi menjadi DUA lapisan:
  1. GLOBAL (system_settings)  → Hanya Super Admin, berlaku untuk seluruh platform
  2. user (user_settings)  → Setiap user bisa set sendiri, berlaku per klien

Tab yang diakses Super Admin: Semua tab (1-9)
Tab yang diakses user Admin: Hanya tab 2 (AI), tab 3 (Payment user), tab 4 (SEO)
```

### Struktur Tab Settings

```
📌 Tab 1: General (Super Admin only)
   ├── Nama platform, tagline
   ├── URL platform, timezone
   ├── Logo upload, favicon upload
   ├── Mainuserce mode ON/OFF
   └── Default language & currency

📌 Tab 2: Authentication & OAuth (Super Admin only)
   ├── Aktifkan Google Login untuk Admin (toggle)
   ├── Aktifkan Google Login untuk Buyer (toggle)
   ├── Google OAuth Client ID (admin)
   ├── Google OAuth Client Secret (admin)
   ├── Google Buyer Client ID
   ├── Google Buyer Client Secret
   ├── Registrasi: open / invite-only / disabled
   ├── Session lifetime (menit)
   └── Require email verification: ON/OFF

📌 Tab 3: AI Configuration (Super Admin + user)
   ├── AI Provider Fase 1: [Gemini / OpenAI / Anthropic / Groq]
   ├── API Key Fase 1
   ├── Model Fase 1: [gemini-2.0-flash / gpt-4o / claude-3-5-sonnet]
   ├── AI Provider Fase 2 (Inquirer)
   ├── API Key Fase 2
   ├── Model Fase 2
   ├── AI Provider Fase 3 (Master Editor)
   ├── API Key Fase 3
   ├── Model Fase 3
   ├── AI untuk Keyword Research
   ├── CQI Threshold (default: 80)
   ├── Max Retry Count (default: 3)
   └── Keyword Research: API vs AI (toggle)

📌 Tab 4: Payments & Billing (Super Admin)
   ├── Midtrans Server Key
   ├── Midtrans Client Key
   ├── Mode: Sandbox / Production
   ├── Mata uang: IDR / USD
   ├── Kode unik range: min 100, max 999
   ├── Auto-verify via Midtrans notif: ON/OFF
   └── Rekening bank tujuan transfer (manual)

📌 Tab 5: SEO & Crawling (Super Admin + user)
   ├── Cloudflare Zone ID
   ├── Cloudflare API Token
   ├── Auto-purge cache saat publish: ON/OFF
   ├── Template default meta title: {keyword} | {site_name}
   ├── Template default meta description
   ├── Robots.txt: global rules (textarea)
   ├── Sitemap: max URL per file (default: 1000)
   └── Default crawl delay (detik)

📌 Tab 6: Email & Notifications (Super Admin)
   ├── SMTP Host
   ├── SMTP Port (465/587)
   ├── SMTP Username
   ├── SMTP Password
   ├── From Name
   ├── From Email
   ├── Template: Order Confirmed (textarea)
   ├── Template: Product Access Granted (textarea)
   ├── Template: Ranking Drop Alert (textarea)
   └── Test email: kirim ke [input email] (button)

📌 Tab 7: Storage & Media (Super Admin)
   ├── Storage driver: local / s3 / r2
   ├── AWS Access Key ID
   ├── AWS Secret Access Key
   ├── AWS Region
   ├── AWS Bucket name
   ├── CDN URL prefix (misal: https://cdn.domain.com)
   ├── Max upload size (MB)
   ├── Allowed file types (jpg, png, webp, pdf)
   └── WebP compression quality (0-100)

📌 Tab 8: Queue & Performance (Super Admin)
   ├── Queue driver: sync / redis / database
   ├── Cache driver: file / redis / memcached
   ├── Session driver: file / redis / database
   ├── PHP-FPM Status (live indicator: ON/OFF)
   ├── Queue Worker Status (live indicator: ON/OFF)
   ├── Pending Jobs count (live)
   ├── Failed Jobs count (live)
   ├── Tombol: Start Worker
   ├── Tombol: Stop Worker
   ├── Tombol: Clear Failed Jobs
   ├── Tombol: Clear Cache
   └── Horizon Dashboard Link

📌 Tab 9: API Integrations (Super Admin + user)
   ├── Unsplash API Key
   ├── Pexels API Key
   ├── Pixabay API Key
   ├── DataForSEO Login
   ├── DataForSEO Password
   ├── Google Analytics Measurement ID
   ├── Facebook Pixel ID
   ├── WhatsApp Business API Token
   └── Custom webhook URL (untuk notifikasi eksternal)
```

### Implementation Notes

```php
// Settings disimpan di dua tabel:
// 1. system_settings  → key-value global (hanya 1 baris per key)
// 2. user_settings  → key-value per user

// Contoh:
// system_settings.google_oauth_enabled = "true"
// system_settings.google_client_id = "xxx"
// user_settings.user_id=1, key=ai_phase1_model, value="gemini-2.0-flash"
// user_settings.user_id=1, key=cqi_threshold, value="80"

// Akses di blade:
// system_setting('google_oauth_enabled')  → helper function
// user_setting('ai_phase1_model')       → helper function (auto-inject user_id)
```

### UI Design Settings Tabs

```
┌─────────────────────────────────────────────────────────────────┐
│ ⚙️  System Settings                                              │
├───────────┬───────────────────────────────────────────────────── │
│ [General] [Auth & OAuth] [AI Config] [Payments] [SEO] ...       │
├───────────┴───────────────────────────────────────────────────── │
│                                                                  │
│  Tab content renders here (AJAX or Alpine.js tab switching)      │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘

Implementasi:
  - Alpine.js untuk tab switching (zero page reload)
  - Setiap tab punya form sendiri dengan action URL masing-masing
  - Toast notification setelah save per tab
  - Tab aktif disimpan di URL hash: /settings#ai-config
```

---

## ENV Variables Lengkap (Update v3.1)

```env
# === APLIKASI ===
APP_NAME="SEOFAST"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=https://seofast.test

# === DATABASE ===
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seofast
DB_USERNAME=root
DB_PASSWORD=

# === GOOGLE OAUTH — Admin ===
GOOGLE_OAUTH_CLIENT_ID=
GOOGLE_OAUTH_CLIENT_SECRET=
GOOGLE_OAUTH_REDIRECT_URI=https://seofast.test/auth/google/callback

# === GOOGLE OAUTH — Buyer ===
GOOGLE_BUYER_CLIENT_ID=
GOOGLE_BUYER_CLIENT_SECRET=
GOOGLE_BUYER_REDIRECT_URI=https://seofast.test/buyer/auth/google/callback

# === GOOGLE GSC + INDEXING API ===
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=https://seofast.test/gsc/callback

# === MIDTRANS ===
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false

# === AI PROVIDERS ===
GEMINI_API_KEY=
OPENAI_API_KEY=
ANTHROPIC_API_KEY=
GROQ_API_KEY=

# === IMAGE SEARCH ===
UNSPLASH_ACCESS_KEY=
PEXELS_API_KEY=
PIXABAY_API_KEY=

# === SERP TRACKING ===
DATAFORSEO_LOGIN=
DATAFORSEO_PASSWORD=

# === STORAGE ===
FILESYSTEM_DISK=local
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=
AWS_URL=

# === REDIS ===
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# === QUEUE ===
QUEUE_CONNECTION=redis

# === CACHE ===
CACHE_STORE=redis

# === EMAIL ===
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@seofast.id
MAIL_FROM_NAME="${APP_NAME}"

# === CLOUDFLARE ===
CLOUDFLARE_ZONE_ID=
CLOUDFLARE_API_TOKEN=
```

---

*Dokumen ini adalah referensi hidup — update setiap kali ada perubahan arsitektur.*  
*Versi: 3.1 | Terakhir diperbarui: 2026-06-24 | Stack: Laravel 13 + MySQL + Socialite*
