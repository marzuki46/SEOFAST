<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\SiloBlueprint;
use App\Models\Content;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BlogMockDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Tenant
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'seofast-hq'],
            [
                'name' => 'SEOFAST HQ',
                'domain' => 'seofast.test',
                'subscription_plan' => 'enterprise',
                'ai_credit_balance' => 50000,
                'monthly_url_quota' => 1000,
                'monthly_url_used' => 25,
                'is_active' => true,
            ]
        );

        // Assign existing users to this tenant if needed
        User::whereIn('email', ['admin@seofast.test', 'ohmjuki@gmail.com'])
            ->update(['tenant_id' => $tenant->id]);

        // 2. Create Categories (Silo Blueprints)
        $categories = [
            [
                'name' => 'Technical SEO',
                'keyword' => 'technical seo guide',
                'language' => 'en',
                'country' => 'US',
            ],
            [
                'name' => 'AI Content Automation',
                'keyword' => 'ai seo writing automation',
                'language' => 'en',
                'country' => 'US',
            ],
            [
                'name' => 'Link Building Strategies',
                'keyword' => 'backlink building techniques',
                'language' => 'en',
                'country' => 'US',
            ],
            [
                'name' => 'On-Page Optimization',
                'keyword' => 'on page seo guidelines',
                'language' => 'en',
                'country' => 'US',
            ],
        ];

        $silos = [];
        foreach ($categories as $cat) {
            $silos[$cat['name']] = SiloBlueprint::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'silo_name' => $cat['name'],
                ],
                [
                    'seed_keyword' => $cat['keyword'],
                    'target_language' => $cat['language'],
                    'target_country' => $cat['country'],
                    'total_contents' => 3,
                    'published_contents' => 3,
                    'is_locked' => true,
                ]
            );
        }

        // Helper function for body texts
        $postsData = [
            'Technical SEO' => [
                [
                    'title' => 'The Ultimate 2026 Technical SEO Checklist',
                    'keyword' => 'technical seo checklist 2026',
                    'level' => 'pillar',
                    'volume' => 1200,
                    'kgr' => 0.12,
                    'body' => "## Introduction to Technical SEO\n\nTechnical SEO is the process of optimizing your website for the crawling and indexing phase. With search engines becoming smarter, technical soundness is no longer optional—it is the foundation of your entire search strategy.\n\nIn this guide, we will cover the key pillars of modern technical SEO:\n\n1. **Website Speed & Core Web Vitals** (LCP, FID, CLS, and the new INP metric)\n2. **Mobile Friendliness** & Responsive layouts\n3. **Crawlability & Indexation** (Robots.txt and XML sitemap optimization)\n4. **Structured Data (Schema.org)** implementation\n5. **Secure Connection (HTTPS)**\n\n### Why Website Speed Matters\n\nGoogle has explicitly stated that page speed is a ranking factor. A site that loads in under 1.5 seconds will experience significantly lower bounce rates and higher conversion rates. Using modern hosting, compressing images, and minimizing CSS/JS files are critical steps to take immediately.\n\n```json\n{\n  \"@context\": \"https://schema.org\",\n  \"@type\": \"TechArticle\",\n  \"headline\": \"The Ultimate 2026 Technical SEO Checklist\",\n  \"description\": \"Comprehensive guide to technical SEO requirements for 2026.\"\n}\n```\n\n### Crawl Budget Optimization\n\nCrawl budget is the number of pages search engine bots will crawl on your site during a specific timeframe. You can preserve crawl budget by disallowing low-value pages in your `robots.txt` file and using `noindex` tags for dynamic search result pages.",
                ],
                [
                    'title' => 'How to Optimize Your Core Web Vitals for Mobile',
                    'keyword' => 'optimize core web vitals mobile',
                    'level' => 'cluster',
                    'volume' => 850,
                    'kgr' => 0.22,
                    'body' => "## What are Core Web Vitals?\n\nCore Web Vitals are a set of real-world experience metrics that Google uses to evaluate a page's user experience. On mobile devices, network latencies make these vitals even more critical to optimize.\n\n### The Three Key Metrics\n\n- **Largest Contentful Paint (LCP)**: Measures loading performance. Aim for **2.5 seconds** or faster.\n- **Interaction to Next Paint (INP)**: Measures responsiveness. Aim for **200 milliseconds** or less.\n- **Cumulative Layout Shift (CLS)**: Measures visual stability. Aim for a score of **0.1** or less.\n\n### Steps to Optimize Vitals on Mobile\n\n1. **Optimize Images**: Use modern formats like WebP or AVIF. Implement lazy loading for below-the-fold images.\n2. **Eliminate Render-Blocking Resources**: Defer non-critical Javascript and inline critical CSS.\n3. **Use a CDN**: Deliver assets from edge servers close to the user.\n4. **Optimize Server Response Time**: Implement robust caching mechanisms.",
                ],
            ],
            'AI Content Automation' => [
                [
                    'title' => 'How to Automate Blog Content Generation with Claude and ChatGPT',
                    'keyword' => 'automate blog content generation ai',
                    'level' => 'pillar',
                    'volume' => 2400,
                    'kgr' => 0.05,
                    'body' => "## The Evolution of AI Content\n\nAI content generation is no longer about spinning low-quality text. Today, LLMs like Claude 3.5 Sonnet and GPT-4o are capable of writing deep, analytical, and highly readable content that mirrors human expertise.\n\n### The 4-Phase Generation Pipeline\n\nTo ensure maximum quality, we implement a closed-loop multi-agent AI system:\n\n1. **Phase 1: The Drafter** – Creates the initial structure and outlines.\n2. **Phase 2: The Inquirer** – Evaluates the draft for technical accuracy and depth.\n3. **Phase 3: The Expander** – Fills in knowledge gaps, details, and incorporates semantic keywords.\n4. **Phase 4: The Editor** – Polishes grammar, readability, and formats the output into clean HTML/Markdown.\n\n### Balancing AI Speed with Content Quality\n\nAlways ensure that your content scores high on the Content Quality Index (CQI). Avoid raw AI generation without human-in-the-loop validation or detailed prompt conditioning. You want your article to demonstrate genuine E-E-A-T (Experience, Expertise, Authoritativeness, Trustworthiness).",
                ],
                [
                    'title' => 'Best Prompts for Writing Long-Form SEO Articles',
                    'keyword' => 'ai prompts long form seo articles',
                    'level' => 'cluster',
                    'volume' => 950,
                    'kgr' => 0.18,
                    'body' => "## Crafting the Perfect AI Prompt\n\nGreat output requires great input. If you give a generic prompt like 'write an article about SEO', the AI will output generic, boilerplate text that will never rank. Here are some advanced prompt templates for long-form content:\n\n### The System Directive Template\n\n```text\nAct as a professional technical copywriter with 10+ years of experience. \nYour target audience is experienced web developers and digital marketers.\nUse a professional, objective tone. Include practical code snippets where relevant.\n```\n\n### Incorporating Keyword Directives\n\nWhen directing the AI, supply a list of semantic variations and entities that must be covered. This ensures search engines can map the article to a wider topical graph.",
                ],
            ],
            'Link Building Strategies' => [
                [
                    'title' => 'Top 10 White-Hat Link Building Strategies that Work in 2026',
                    'keyword' => 'white hat link building strategies 2026',
                    'level' => 'pillar',
                    'volume' => 3100,
                    'kgr' => 0.04,
                    'body' => "## The State of Link Building\n\nBacklinks remain one of the top three ranking signals in search algorithms. However, search engines are increasingly sophisticated at ignoring spammy or paid links. In 2026, building links requires a high-value, relationship-focused approach.\n\n### Modern White-Hat Tactics\n\n1. **Digital PR**: Creating data-backed studies that journalists want to reference.\n2. **Skyscraper Technique 2.0**: Finding popular but outdated resources and creating something significantly more comprehensive.\n3. **Linkable Assets**: Building interactive tools, calculators, or infographics.\n4. **Broken Link Building**: Helping webmasters find broken links on their site and suggesting your content as a replacement.\n\n### The Importance of Relevance\n\nA single link from a highly relevant, authoritative website in your niche is worth more than 100 low-quality links from unrelated directories. Focus on editorial links that drive actual referral traffic.",
                ],
            ],
            'On-Page Optimization' => [
                [
                    'title' => 'Complete Guide to On-Page SEO: Title Tags, Headings, and Alt Text',
                    'keyword' => 'complete guide on page seo',
                    'level' => 'pillar',
                    'volume' => 4500,
                    'kgr' => 0.08,
                    'body' => "## Introduction to On-Page SEO\n\nOn-page SEO refers to the optimization of individual web pages to rank higher and earn more relevant traffic in search engines. Unlike off-page signals, you have 100% control over your on-page elements.\n\n### Key On-Page Elements to Optimize\n\n- **Title Tags**: Keep it under 60-70 characters. Place your target keyword near the beginning.\n- **Meta Descriptions**: Keep it under 155-160 characters. Write a compelling call-to-action.\n- **Header Hierarchy (H1, H2, H3)**: Use a single H1 per page. Use H2 and H3 tags to organize your sections logically.\n- **Image Alt Attributes**: Describe the image clearly for accessibility, incorporating keywords naturally when relevant.\n- **URL Structure**: Make URLs short, readable, and keyword-rich.\n\n### Content Quality and Depth\n\nIt's not just about tags; the content itself must satisfy search intent. Make sure your articles answer user queries comprehensively, use clear formatting, bullet points, and high-quality images.",
                ],
            ],
        ];

        foreach ($postsData as $siloName => $posts) {
            $silo = $silos[$siloName];

            foreach ($posts as $post) {
                Content::firstOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'slug' => Str::slug($post['title']),
                    ],
                    [
                        'silo_blueprint_id' => $silo->id,
                        'target_keyword' => $post['keyword'],
                        'meta_title' => $post['title'],
                        'meta_description' => substr(strip_tags(str_replace("\n", ' ', $post['body'])), 0, 150) . '...',
                        'hierarchy_level' => $post['level'],
                        'search_volume' => $post['volume'],
                        'kgr_score' => $post['kgr'],
                        'cqi_score' => rand(82, 96),
                        'semantic_depth_score' => rand(80, 95),
                        'entity_coverage_score' => rand(78, 92),
                        'readability_score' => rand(70, 88),
                        'body_raw' => $post['body'],
                        'status' => 'published',
                        'published_at' => Carbon::now()->subDays(rand(1, 60)),
                        'gsc_coverage_state' => 'Submitted and indexed',
                        'current_serp_position' => rand(1, 15),
                    ]
                );
            }
        }
    }
}
