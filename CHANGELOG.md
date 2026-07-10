# Changelog — SEOFAST Framework

## v3.2 (Current) — Homepage Redesign & Competitor Analysis

**Release date:** July 10, 2026

### Added
- Competitor Content Analysis: `/admin/competitor-analysis` — AI-powered analysis of keyword landscape, competitor content gaps, and content recommendations using 9Router LLM (wisanka)
- Early-bird pricing system: `development_price` column on products, helpers (`isDevelopment()`, `displayPrice()`) with strikethrough original price + "Early Bird" badge
- Homepage redesign: showcase all products with best-seller spotlight, latest & trending blog posts, B2B section (Tiga Pilar Bisnis)
- Product cards: fallback SVG placeholder, feature tags (up to 4 + overflow count), "Lihat Detail" + "Beli" buttons, JSON-LD schema, clickable titles
- Blog cards: fallback SVG placeholder with alt text
- Quick Tools card on admin dashboard for Competitor Analysis

### Changed
- Homepage template: `hero-centered.blade.php` now renders featured products with development pricing
- Product detail page tabs: removed `x-transition`, added `min-h-[300px]`, `x-cloak` CSS for stability
- B2B icons centered with `mx-auto`, gradients via inline `style` (bypasses Tailwind purge)
- Asset loading: replaced `@vite()` with manual `manifest.json` reading in both `frontend.blade.php` and `app.blade.php`
- `APP_ENV=production`

### Fixed
- CSS loading broken on all pages — `@vite()` failed in production; fixed by reading manifest directly

---

## v3.1 — Content Framework & Tone Options

**Release date:** July 6, 2026

### Added
- Content frameworks: AIDA, PAS, How-To, Listicle + E-E-A-T enforcement in AI pipeline
- Tone options: Formal, Friendly, Persuasive, Authoritative, Conversational
- Anchor keywords in Phase 1 + fallback anchors
- Separate Phase 7 for SEO Meta + Embeddings (previously lumped with Phase 6)

### Fixed
- Multiple H1 per article → single H1 enforcement
- AI hallucination artifacts & code block stripping from final content
- 522 timeout — limit 50 concurrent + database indexes for faster queries
- Infinite loop on phase_1→phase_2 transition (data reset)
- Infinite loop on phase_6 with try-catch wrapper + guard clause
- Content style: 3-5 sentences per paragraph, no auto-lists, tables for data
- Brand injection across all phases
- Strip AI-generated info boxes, add table CSS styling
- Explicit Bahasa Indonesia instruction in all phase prompts
- Phase 3 minimum 10 critical questions + language-aware fallback template
- Duplicate slug handling in data migration cleanup
- Release session lock in UserController index
- Unified `Content::whereSlug()` scope with JSON_EXTRACT
- Change `rendered_html_path` column to LONGTEXT
- Various SEO indexing improvements & install wizard

### Chore
- Frontend assets rebuild

---

## v3.0 — SEO Operating System (Major Release)

**Release date:** June 2026

### Added

#### AI Content Engine
- 4-phase multi-agent pipeline: Drafter → Inquirer/Critic → Expander → Master Editor
- Multi-provider AI support: OpenAI, Google Gemini, Anthropic Claude, DeepSeek
- Smart fallback across providers when one is down
- CQI (Content Quality Index) scoring engine — threshold ≥ 80
- AiRecoveryManager for pipeline failure recovery
- Automatic image selection + WebP conversion (Phase 5)

#### Topical Map / Silo Architect
- Visual node graph with Drawflow UI
- AI generates keyword clusters: Pillar → Cluster → Sub-cluster (hierarchy_level)
- KGR (Keyword Golden Ratio) scoring for keyword selection
- Search volume tracking per keyword
- Denormalized counters: total_contents, published_contents
- Lock/unlock silo after content creation

#### Deterministic Internal Linking
- AI-mapped link relationships (Pillar→Cluster→Sub-cluster)
- Zero orphan page enforcement
- Mandatory anchor text injection during rendering
- Visual link graph overlay on node graph
- Max 5 links per source content
- AI-generated anchor keywords with fallback
- `is_injected_successfully` tracking

#### Google Search Console Integration
- OAuth2 authentication with CSRF state token
- URL Inspection API (2000 req/day)
- Search Analytics API (clicks, impressions, CTR, position)
- Google Indexing API via Service Account (200 req/day)
- Auto token refresh 5 minutes before expiry
- Encrypted credential storage
- Coverage state tracking: Submitted/Indexed, Crawled-Not-Indexed, etc.
- Scheduled daily sync: URL Inspection @ 03:00, Search Analytics @ 04:00

#### SEO Audit & Quality Tools
- URL Audit — comprehensive per-URL SEO check
- Broken Link Scanner — scan & manage broken links
- Duplicate Content Detection — AI cosine similarity via vector embeddings
- Readability Scoring — compute scores for all content
- 404 Error Tracker — track, redirect, or clear errors
- Redirect Manager — full CRUD for URL redirects
- Canonical Mapping Service

#### SERP Rank Tracker
- Daily SERP position snapshots
- Ranking trend analysis over time
- Historical position tracking per keyword
- SERP feature detection: Featured Snippet, PAA, Image Pack, Video Carousel, AI Overview
- Multi-device (desktop/mobile) & multi-country tracking
- Content Freshness Engine (weekly re-evaluation)
- Crawl Priority Scoring

#### Auto-Reoptimization Loop
- Ranking dropped >5 positions after 30 days
- CQI degraded detection
- Not indexed after 14 days
- CTR below niche average
- Manual trigger via admin
- Full 4-phase pipeline re-run with E-E-A-T improvements

#### Schema Markup & Structured Data
- JSON-LD injection per content/page
- Schema types: Article, FAQPage, HowTo, BreadcrumbList, Product, LocalBusiness, WebPage
- WebSite schema with Sitelinks Search Box
- Organization schema (auto-generated from settings)
- Rich Results validation tracking

#### WordPress Import/Export
- Full WXR 1.2 import → Silo Blueprints
- Yoast SEO meta (title, description) preservation
- Media library import with WebP conversion
- Featured image import
- Export to standard WXR 1.2 format

#### Digital Marketplace
- Product catalog with SEO-friendly URLs (`/produk/{slug}`)
- Product landing pages with Product + Offer + AggregateRating schema
- Pre-order system with launch management
- Midtrans payment integration (Snap API)
- Unique payment code system
- Transfer verification with proof upload
- Product access control (grant/revoke)
- Order management with verify/reject workflow

#### Buyer Portal
- Separate authentication guard (`buyer`) independent from admin
- Google OAuth login (one-click)
- Email/password registration with email verification
- Dashboard with order history
- Product access page (digital downloads)
- Support ticket system (create, reply, track status)

#### Page Builder & Templates
- 6 template variants: default, hero-centered, hero-cta, hero-image, hero-split, hero-video
- Visual Page Builder with folder organization
- Custom meta title/description per page
- Homepage selection system
- Custom CSS injection per page

#### SEO Infrastructure
- Dynamic sitemap.xml with priority scores
- Sitemap pagination
- Dynamic robots.txt from crawl_budget_rules
- Ghost publish routes (`/g/{slug}`) with noindex
- Hreflang tags (ID + EN multi-language)
- Canonical URL management

#### Multi-Language
- Indonesian (default) + English (`/en/` prefix)
- Auto hreflang tags (ID, EN, x-default)
- Multi-language content generation
- Language-aware fallback templates

#### Admin & Management
- Dashboard with cache/queue controls
- System Settings (tabbed): General, Permalinks, Footer, Auth/OAuth, Email SMTP, Storage/S3, Payment Gateway
- SEO Settings: author/bio, title templates, tracking codes, verification
- Client management with impersonation, plan/quota/domain
- Menu Management with nested dropdowns
- Infrastructure Dashboard: queue, cache, workers
- Contact Inquiries management
- Support Tickets management
- Billing/Invoices management
- Media Library

#### Installation Wizard
- Welcome page → Database config → Admin account → Migration → Complete

---

## v2.x Series — Foundation Phase

**Release period:** March–May 2026

### Added
- AI content generation engine (single-phase)
- Basic silo structure management
- Google Search Console legacy Webmasters API integration
- Basic SEO settings & meta management
- Media library with automatic WebP conversion
- Admin dashboard & system settings
- Dynamic sitemap.xml & robots.txt
- Basic page management with templates
- Contact form & inquiry system
- Basic blog with categories

### Fixed
- Various edge cases in content generation
- SEO meta rendering issues
- Database migration compatibility

---

## v1.0 — Initial Release

**Release date:** March 2026

- Initial Laravel application scaffold
- Core authentication & authorization
- Basic content management system
- SEO settings foundation
