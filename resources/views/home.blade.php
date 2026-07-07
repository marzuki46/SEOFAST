@extends('layouts.frontend')

@php
    use App\Models\SystemSetting;
    $homeTitle = SystemSetting::get('seo_global_meta_title') ?: SystemSetting::get('home_meta_title', 'SEOFAST V3 — The High-Performance SEO Operating System');
    $homeDesc = SystemSetting::get('seo_global_meta_description') ?: SystemSetting::get('home_meta_description', 'Deploy high-performance, SEO-optimized AI content with automated keyword research, Topical Silo building, and real-time Google Search Console synchronization.');
@endphp
@section('title', $homeTitle)
@section('meta_description', $homeDesc)

@section('content')
<!-- Hero Section -->
<section class="relative pt-32 pb-24 overflow-hidden">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-indigo/5 border border-brand-indigo/10 text-brand-indigo text-xs font-semibold uppercase tracking-wider mb-8">
            <span class="w-1.5 h-1.5 rounded-full bg-brand-indigo animate-pulse"></span>
            Version 3.0 Live — Built for Speed
        </div>
        
        <h1 class="font-outfit font-extrabold text-5xl md:text-7xl text-slate-900 tracking-tight leading-none mb-8 max-w-4xl mx-auto">
            Automate Content. <br>
            <span class="bg-gradient-to-r from-brand-indigo via-brand-purple to-brand-violet bg-clip-text text-transparent">Dominate Search.</span>
        </h1>
        
        <p class="text-slate-600 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed mb-12">
            The first closed-loop SEO operating system. Build topical silos, generate high-quality AI content, index URLs automatically, and re-optimize based on real Search Console performance.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="{{ route('buyer.login') }}" class="w-full sm:w-auto text-base font-bold text-white bg-gradient-to-r from-brand-indigo to-brand-purple hover:scale-105 active:scale-95 transition-all px-8 py-4 rounded-2xl shadow-xl shadow-brand-indigo/25">
                Start For Free
            </a>
            <a href="#features" class="w-full sm:w-auto text-base font-bold text-slate-700 bg-slate-100 hover:bg-slate-200/80 border border-slate-200 px-8 py-4 rounded-2xl transition-all">
                Learn More
            </a>
        </div>
        
        <!-- Mockup Panel -->
        <div class="mt-20 relative rounded-2xl overflow-hidden border border-slate-200/80 glass-panel shadow-xl animate-float max-w-5xl mx-auto">
            <div class="h-12 bg-slate-100/50 border-b border-slate-200/80 px-4 flex items-center justify-between">
                <div class="flex gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500/80"></span>
                    <span class="w-3 h-3 rounded-full bg-yellow-500/80"></span>
                    <span class="w-3 h-3 rounded-full bg-green-500/80"></span>
                </div>
                <div class="text-xs text-slate-500 font-mono">seofast.test/admin/dashboard</div>
                <div></div>
            </div>
            <div class="p-4 md:p-8 bg-slate-900 text-left">
                <!-- Inner Dashboard Mockup (Maintained dark color inside mockup for technical UI simulation) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="p-6 rounded-xl border border-white/5 bg-white/5">
                        <div class="text-gray-400 text-xs font-semibold mb-2">Google Indexation Rate</div>
                        <div class="text-3xl font-bold text-white font-outfit">99.4%</div>
                        <div class="text-green-500 text-xs mt-1">▲ +4.2% this month</div>
                    </div>
                    <div class="p-6 rounded-xl border border-white/5 bg-white/5">
                        <div class="text-gray-400 text-xs font-semibold mb-2">AI Content Quality Index</div>
                        <div class="text-3xl font-bold text-white font-outfit">92 / 100</div>
                        <div class="text-green-500 text-xs mt-1">● Optimal E-E-A-T</div>
                    </div>
                    <div class="p-6 rounded-xl border border-white/5 bg-white/5">
                        <div class="text-gray-400 text-xs font-semibold mb-2">Active Topical Silos</div>
                        <div class="text-3xl font-bold text-white font-outfit">18 Active</div>
                        <div class="text-gray-500 text-xs mt-1">Zero orphan pages</div>
                    </div>
                </div>
                <div class="h-48 bg-white/5 rounded-xl border border-white/5 p-6 flex flex-col justify-between">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-white">Closed-Loop Search Performance</span>
                        <span class="text-xs text-gray-400">GSC Sync Log (3:00 AM)</span>
                    </div>
                    <div class="flex items-end justify-between gap-2 h-24">
                        <div class="w-full bg-indigo-500/20 rounded h-12"></div>
                        <div class="w-full bg-indigo-500/30 rounded h-16"></div>
                        <div class="w-full bg-indigo-500/40 rounded h-20"></div>
                        <div class="w-full bg-indigo-500/60 rounded h-24"></div>
                        <div class="w-full bg-gradient-to-t from-brand-indigo to-brand-purple rounded h-32"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Grid -->
<section class="border-y border-slate-200 py-16 bg-slate-100/40">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div>
                <div class="font-outfit font-extrabold text-4xl md:text-5xl text-slate-900 mb-2">10x</div>
                <div class="text-slate-600 text-sm">Faster Indexing Speeds</div>
            </div>
            <div>
                <div class="font-outfit font-extrabold text-4xl md:text-5xl text-slate-900 mb-2">90+</div>
                <div class="text-slate-600 text-sm">Content Quality Score</div>
            </div>
            <div>
                <div class="font-outfit font-extrabold text-4xl md:text-5xl text-slate-900 mb-2">0</div>
                <div class="text-slate-600 text-sm">Orphan Pages Created</div>
            </div>
            <div>
                <div class="font-outfit font-extrabold text-4xl md:text-5xl text-slate-900 mb-2">24/7</div>
                <div class="text-slate-600 text-sm">Automated SEO Audits</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-4">
                Designed to Outrank the Competition
            </h2>
            <p class="text-slate-600 text-lg">
                Traditional platforms write pages and leave them to die. SEOFAST closes the loop by syncing with Google to dynamically improve performance.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="p-8 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50/50 shadow-sm hover:shadow-md hover:border-slate-300 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-600 mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="font-outfit font-semibold text-xl text-slate-900 mb-3">Topical Silo blueprints</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Map search intents into structural hierarchies. Automatically link pillar posts, cluster posts, and sub-clusters to create strong topical authority without orphan pages.
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="p-8 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50/50 shadow-sm hover:shadow-md hover:border-slate-300 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h3 class="font-outfit font-semibold text-xl text-slate-900 mb-3">4-Phase AI Generation</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Leverage multi-agent pipelines (Drafter, Inquirer, Expander, Editor) to generate semantic, rich markdown content that achieves Content Quality Index scores above 80.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="p-8 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50/50 shadow-sm hover:shadow-md hover:border-slate-300 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18v3z" />
                    </svg>
                </div>
                <h3 class="font-outfit font-semibold text-xl text-slate-900 mb-3">Closed-Loop GSC Sync</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Direct connection to Google Search Console. Inspect URL coverage status and automatically push slow-indexing or ranking-dropping content to the re-optimization queue.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Blog Teaser Section -->
<section class="py-24 border-t border-slate-200 bg-slate-100/30">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
            <div>
                <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-4">
                    Latest SEO Insights
                </h2>
                <p class="text-slate-600 text-base max-w-xl">
                    High-quality resources written by our expert copywriters and fully optimized using the SEOFAST content engine.
                </p>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-brand-indigo hover:text-brand-purple flex items-center gap-2 group transition-colors">
                Visit our Blog 
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($recentPosts as $post)
                <article class="flex flex-col bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-slate-300 hover:shadow-md transition-all group">
                    <div class="p-6 flex flex-col flex-1">
                        <!-- Meta -->
                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-4">
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-600 font-semibold uppercase">
                                {{ $post->siloBlueprint->silo_name ?? 'SEO' }}
                            </span>
                            <span>•</span>
                            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</span>
                        </div>
                        
                        <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3 line-clamp-2 group-hover:text-brand-indigo transition-colors">
                            <a href="{{ route('blog.show', $post->slug ?: 'draft') }}">{{ $post->title }}</a>
                        </h3>
                        
                        <!-- Description -->
                        <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-3 flex-1">
                            {{ $post->meta_description }}
                        </p>
                        
                        <!-- Footer Link -->
                        <div class="flex items-center justify-end pt-4 border-t border-slate-100">
                            <a href="{{ route('blog.show', $post->slug ?: 'draft') }}" class="text-xs font-bold text-slate-800 group-hover:text-brand-indigo transition-colors flex items-center gap-1">
                                Read Article 
                                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-slate-500 text-sm">No blog posts available yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="py-24 border-t border-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-4">
                Sleek, Predictable Pricing
            </h2>
            <p class="text-slate-600 text-lg">
                Choose the plan that fits your search ambitions. Scale up seamlessly as you command the SERP.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            <!-- Plan 1 -->
            <div class="p-8 rounded-2xl border border-slate-200 bg-white flex flex-col justify-between hover:border-slate-300 hover:shadow-md transition-all">
                <div>
                    <h3 class="font-outfit font-bold text-lg text-slate-900 mb-2">Starter</h3>
                    <p class="text-slate-500 text-xs mb-6">Perfect for personal blogs and experiments</p>
                    <div class="flex items-baseline gap-1 text-slate-900 mb-8">
                        <span class="text-4xl font-extrabold font-outfit">$49</span>
                        <span class="text-slate-500 text-sm">/ month</span>
                    </div>
                    <ul class="space-y-4 text-sm text-slate-600 mb-8 border-t border-slate-100 pt-6">
                        <li class="flex items-center gap-2">
                            <span class="text-brand-purple">✓</span> 5 Topical Silos
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-brand-purple">✓</span> 50 AI Generated Articles / month
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-brand-purple">✓</span> Standard Google Indexing API Sync
                        </li>
                        <li class="flex items-center gap-2 text-slate-400 line-through">
                            <span>✗</span> Automatic Re-optimization Loop
                        </li>
                    </ul>
                </div>
                <a href="{{ route('buyer.login') }}" class="w-full py-3 rounded-xl border border-slate-200 text-center font-bold text-slate-800 hover:bg-slate-50 bg-white transition-all">
                    Get Started
                </a>
            </div>

            <!-- Plan 2 (Featured) -->
            <div class="p-8 rounded-2xl border border-brand-indigo bg-white/80 shadow-lg shadow-brand-indigo/5 flex flex-col justify-between hover:scale-[1.02] transition-all relative">
                <span class="absolute top-0 right-8 -translate-y-1/2 px-3 py-1 rounded-full bg-brand-indigo text-white font-semibold text-xs tracking-wider uppercase shadow-md">
                    Popular
                </span>
                <div>
                    <h3 class="font-outfit font-bold text-lg text-slate-900 mb-2">Professional</h3>
                    <p class="text-slate-500 text-xs mb-6">For growing businesses and content websites</p>
                    <div class="flex items-baseline gap-1 text-slate-900 mb-8">
                        <span class="text-4xl font-extrabold font-outfit">$149</span>
                        <span class="text-slate-500 text-sm">/ month</span>
                    </div>
                    <ul class="space-y-4 text-sm text-slate-600 mb-8 border-t border-slate-100 pt-6">
                        <li class="flex items-center gap-2">
                            <span class="text-brand-indigo">✓</span> 20 Topical Silos
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-brand-indigo">✓</span> 250 AI Generated Articles / month
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-brand-indigo">✓</span> Real-time Indexing API submit
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-brand-indigo">✓</span> GSC syncing & Re-optimization loop
                        </li>
                    </ul>
                </div>
                <a href="{{ route('buyer.login') }}" class="w-full py-3 rounded-xl bg-gradient-to-r from-brand-indigo to-brand-purple text-center font-bold text-white hover:opacity-90 transition-all shadow-lg shadow-brand-indigo/15">
                    Start Pro Trial
                </a>
            </div>

            <!-- Plan 3 -->
            <div class="p-8 rounded-2xl border border-slate-200 bg-white flex flex-col justify-between hover:border-slate-300 hover:shadow-md transition-all">
                <div>
                    <h3 class="font-outfit font-bold text-lg text-slate-900 mb-2">Enterprise</h3>
                    <p class="text-slate-500 text-xs mb-6">For large portfolios and agencies</p>
                    <div class="flex items-baseline gap-1 text-slate-900 mb-8">
                        <span class="text-4xl font-extrabold font-outfit">$499</span>
                        <span class="text-slate-500 text-sm">/ month</span>
                    </div>
                    <ul class="space-y-4 text-sm text-slate-600 mb-8 border-t border-slate-100 pt-6">
                        <li class="flex items-center gap-2">
                            <span class="text-brand-purple">✓</span> Unlimited Topical Silos
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-brand-purple">✓</span> 1000+ AI Generated Articles / month
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-brand-purple">✓</span> Priority Custom API sync
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-brand-purple">✓</span> Complete E-E-A-T entity matching
                        </li>
                    </ul>
                </div>
                <a href="{{ route('buyer.login') }}" class="w-full py-3 rounded-xl border border-slate-200 text-center font-bold text-slate-800 hover:bg-slate-50 bg-white transition-all">
                    Contact Sales
                </a>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="py-24 border-t border-slate-200">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-4">
                Frequently Asked Questions
            </h2>
            <p class="text-slate-600 text-base">
                Got questions about the SEOFAST closed-loop system? We've got answers.
            </p>
        </div>

        <div class="space-y-6">
            <div class="p-6 rounded-2xl border border-slate-200/80 bg-white shadow-sm hover:shadow transition-shadow">
                <h3 class="font-outfit font-bold text-lg text-slate-900 mb-2">How does the Closed-Loop Sync work?</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    SEOFAST runs daily background workers that pull indexation and search ranking data directly from Google Search Console. If a page's rankings drop or it remains unindexed for 14 days, the system automatically triggers a content audit and queue it for AI re-optimization.
                </p>
            </div>

            <div class="p-6 rounded-2xl border border-slate-200/80 bg-white shadow-sm hover:shadow transition-shadow">
                <h3 class="font-outfit font-bold text-lg text-slate-900 mb-2">What is the Content Quality Index (CQI)?</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    CQI is our internal scoring engine that audits keyword density, entity coverage, semantic depth, readability, and structural elements. It mimics search engine criteria to ensure your article has the highest possibility of ranking before it's published.
                </p>
            </div>

            <div class="p-6 rounded-2xl border border-slate-200/80 bg-white shadow-sm hover:shadow transition-shadow">
                <h3 class="font-outfit font-bold text-lg text-slate-900 mb-2">Is the code fully compatible with MySQL?</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Yes. All database operations, partitions, search log structures, and core SaaS configurations have been fully migrated and optimized for high-performance MySQL databases.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
