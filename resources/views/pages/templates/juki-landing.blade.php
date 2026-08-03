<!-- Juki Website Developer Solo - Animated Landing Page Template -->
@extends('layouts.frontend')

@section('title', 'Juki Website Developer Solo - Jasa Pembuatan Website, Sistem & AI Automation Profesional')
@section('meta_description', 'Juki Website Developer Solo: Ahli pembuatan website company profile, toko online, sistem informasi ERP/CRM, AI automation chatbot, dan jasa backlink pyramid aman. Layanan SEO Surakarta, Jawa Tengah. Konsultasi gratis!')

@section('meta_keywords', 'juki website developer solo, jasa pembuatan website solo, web developer surakarta, pembuatan sistem informasi, ai automation indonesia, jasa seo solo, backlink berkualitas, web programmer solo, digital marketing solo, pembuatan aplikasi web, erp system indonesia, crm customization, chatbot automation, pbn backlink service, google maps optimization, local seo solo, website responsive, landing page conversion, ecommerce development, wordpress developer solo, laravel developer indonesia, custom web application, business automation tools, seo consultant solo, link building service')

@section('og_title', 'Juki Website Developer Solo - Partner Digital Bisnis Anda')
@section('og_description', 'Spesialis pembuatan website, sistem informasi, AI automation, dan strategi SEO backlink untuk pertumbuhan bisnis Anda di Solo dan seluruh Indonesia.')
@section('og_image', asset('assets/og-default.jpg'))
@section('og_type', 'website')

@section('twitter_card', 'summary_large_image')
@section('twitter_title', 'Juki Website Developer Solo - Jasa Web & AI Automation')
@section('twitter_description', 'Transformasi bisnis Anda dengan website profesional, sistem custom, dan AI automation dari expert Solo.')

@section('styles')
<style>
    /* Custom Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-50px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(50px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
    .animate-fade-in-down { animation: fadeInDown 0.8s ease-out forwards; }
    .animate-slide-in-left { animation: slideInLeft 0.8s ease-out forwards; }
    .animate-slide-in-right { animation: slideInRight 0.8s ease-out forwards; }
    .animate-pulse-slow { animation: pulse 3s ease-in-out infinite; }
    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-gradient { 
        background-size: 200% 200%;
        animation: gradientShift 15s ease infinite;
    }
    
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-500 { animation-delay: 0.5s; }
    .delay-600 { animation-delay: 0.6s; }
    
    /* Gradient Text */
    .gradient-text {
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Card Hover Effects */
    .service-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .service-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.25);
    }
    
    /* Button Glow */
    .btn-glow {
        position: relative;
        overflow: hidden;
    }
    
    .btn-glow::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }
    
    .btn-glow:hover::before {
        left: 100%;
    }
    
    /* Map Container */
    .map-container {
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        transition: transform 0.3s ease;
    }
    
    .map-container:hover {
        transform: scale(1.02);
    }
    
    /* Scroll Indicator */
    .scroll-indicator {
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(10px);}
        60% {transform: translateY(5px);}
    }
</style>
@endsection

@section('schema_markup')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Juki Website Developer Solo",
  "url": "{{ config('app.url') }}",
  "description": "Jasa pembuatan website company profile, toko online, sistem informasi ERP/CRM, AI automation chatbot, dan SEO backlink profesional di Solo, Surakarta, Jawa Tengah",
  "telephone": "+62-xxx-xxxx-xxxx",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Surakarta",
    "addressLocality": "Surakarta",
    "addressRegion": "Jawa Tengah",
    "postalCode": "57100",
    "addressCountry": "ID"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "-7.6384539999999985",
    "longitude": "110.8178768"
  },
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    "opens": "09:00",
    "closes": "21:00"
  },
  "priceRange": "$$",
  "sameAs": [
    "https://juki.eu.org/",
    "https://www.google.com/maps/place/Juki+Website+Developer+Solo"
  ],
  "areaServed": {
    "@type": "City",
    "name": "Surakarta"
  },
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "name": "Layanan Digital Marketing & Web Development",
    "itemListElement": [
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "Jasa Pembuatan Website",
          "description": "Website responsive company profile, landing page, toko online dengan optimasi SEO dan Core Web Vitals",
          "serviceType": "Web Development",
          "areaServed": "Solo, Surakarta, Jawa Tengah, Indonesia"
        }
      },
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "Pembuatan Sistem Informasi",
          "description": "Custom web application, ERP system, CRM customization, database management untuk efisiensi bisnis",
          "serviceType": "Software Development",
          "areaServed": "Indonesia"
        }
      },
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "AI Automation",
          "description": "Chatbot automation, business process automation, integrasi AI untuk produktivitas maksimal",
          "serviceType": "Artificial Intelligence Services",
          "areaServed": "Indonesia"
        }
      },
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "Jasa Backlink SEO",
          "description": "Link building service, Backlink Pyramid yang aman, guest post, Google Maps optimization untuk ranking lebih tinggi",
          "serviceType": "SEO Services",
          "areaServed": "Indonesia"
        }
      }
    ]
  }
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ProfessionalService",
  "name": "Juki Website Developer Solo",
  "image": "{{ asset('assets/og-default.jpg') }}",
  "description": "Layanan profesional web developer, programmer, dan SEO specialist di Solo dengan pengalaman bertahun-tahun",
  "offers": [
    {
      "@type": "Offer",
      "name": "Jasa Pembuatan Website Profesional",
      "description": "Website responsif, cepat, SEO-friendly dengan teknologi modern Laravel, WordPress, React",
      "category": "Web Development",
      "providerMobility": "OnSite"
    },
    {
      "@type": "Offer",
      "name": "Pembuatan Sistem Informasi Custom",
      "description": "Sistem ERP, CRM, inventory management, POS system sesuai kebutuhan bisnis Anda",
      "category": "Software Development"
    },
    {
      "@type": "Offer",
      "name": "AI Automation & Chatbot",
      "description": "Otomatisasi proses bisnis dengan kecerdasan buatan, WhatsApp bot, customer service automation",
      "category": "Artificial Intelligence"
    },
    {
      "@type": "Offer",
      "name": "Jasa Backlink & SEO Link Building",
      "description": "Backlink berkualitas tinggi dari authoritative sites untuk meningkatkan domain authority dan ranking Google",
      "category": "Digital Marketing"
    }
  ],
  "knowsAbout": [
    "Web Development",
    "Laravel Framework",
    "WordPress Development",
    "React.js",
    "SEO Optimization",
    "Link Building",
    "AI Automation",
    "Database Design",
    "API Integration",
    "E-commerce Solutions",
    "ERP Systems",
    "CRM Implementation"
  ]
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "{{ config('app.url') }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Juki Website Developer Solo"
    }
  ]
}
</script>
@endsection

@section('content')
{{-- HERO SECTION --}}
<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-purple-900 animate-gradient">
    <!-- Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-float delay-300"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-pink-500/10 rounded-full blur-3xl animate-pulse-slow"></div>
    </div>
    
    <!-- Grid Pattern -->
    <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:50px_50px]"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-blue-300 text-sm font-semibold mb-8 animate-fade-in-down">
            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
            🚀 Ready to Transform Your Business
        </div>
        
        <!-- Main Heading -->
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold font-outfit leading-tight mb-6 animate-fade-in-up">
            <span class="text-white">Juki Website</span><br>
            <span class="gradient-text">Developer Solo</span>
        </h1>
        
        <!-- Subheading -->
        <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto mb-12 leading-relaxed animate-fade-in-up delay-200">
            Mitra digital Anda untuk <strong class="text-white">pembuatan website profesional</strong>, 
            <strong class="text-white">sistem informasi custom</strong>, 
            <strong class="text-white">AI automation</strong>, dan 
            <strong class="text-white">SEO backlink</strong> berkualitas tinggi.
        </p>
        
        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16 animate-fade-in-up delay-300">
            <a href="#contact" class="btn-glow px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 hover:from-blue-400 hover:via-purple-400 hover:to-pink-400 text-white font-bold text-lg shadow-2xl shadow-blue-500/30 transition-all hover:scale-105 active:scale-95">
                📞 Konsultasi Gratis Sekarang
            </a>
            <a href="#services" class="px-8 py-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-semibold text-lg hover:bg-white/20 transition-all hover:scale-105">
                👇 Lihat Layanan Kami
            </a>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator absolute bottom-10 left-1/2 -translate-x-1/2">
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </div>
</section>

{{-- SERVICES SECTION --}}
<section id="services" class="py-24 bg-gradient-to-b from-slate-900 to-slate-800 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>
    <div class="absolute top-20 right-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-20 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-extrabold font-outfit text-white mb-4">
                Layanan <span class="gradient-text">Unggulan</span>
            </h2>
            <p class="text-xl text-blue-200 max-w-2xl mx-auto">
                Solusi digital lengkap untuk mengembangkan bisnis Anda ke level berikutnya
            </p>
        </div>
        
        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Service 1: Website Development --}}
            <div class="service-card group relative p-8 rounded-3xl bg-gradient-to-br from-blue-600/20 to-blue-800/20 backdrop-blur-md border border-blue-500/30 hover:border-blue-400/60 animate-fade-in-up">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-blue-500/30">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold font-outfit text-white mb-4">Pembuatan Website</h3>
                    <ul class="space-y-3 text-blue-100">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Responsive & Mobile-First</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>SEO Optimized Structure</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Fast Loading Speed</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Modern UI/UX Design</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Service 2: System Development --}}
            <div class="service-card group relative p-8 rounded-3xl bg-gradient-to-br from-purple-600/20 to-purple-800/20 backdrop-blur-md border border-purple-500/30 hover:border-purple-400/60 animate-fade-in-up delay-100">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-purple-500/30">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold font-outfit text-white mb-4">Pembuatan Sistem</h3>
                    <ul class="space-y-3 text-purple-100">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Custom Business Logic</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Database Architecture</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>API Integration</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Scalable Infrastructure</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Service 3: AI Automation --}}
            <div class="service-card group relative p-8 rounded-3xl bg-gradient-to-br from-pink-600/20 to-pink-800/20 backdrop-blur-md border border-pink-500/30 hover:border-pink-400/60 animate-fade-in-up delay-200">
                <div class="absolute inset-0 bg-gradient-to-br from-pink-500/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-pink-500/30">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold font-outfit text-white mb-4">AI Automation</h3>
                    <ul class="space-y-3 text-pink-100">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Chatbot Development</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Process Automation</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Data Processing</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Machine Learning</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Service 4: SEO Backlink --}}
            <div class="service-card group relative p-8 rounded-3xl bg-gradient-to-br from-emerald-600/20 to-emerald-800/20 backdrop-blur-md border border-emerald-500/30 hover:border-emerald-400/60 animate-fade-in-up delay-300">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-emerald-500/30">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold font-outfit text-white mb-4">Jasa Backlink</h3>
                    <ul class="space-y-3 text-emerald-100">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>High DA/PA Sites</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>White Hat Techniques</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Relevant Niches</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Monthly Reports</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- WHY CHOOSE US --}}
<section class="py-24 bg-gradient-to-b from-slate-800 to-slate-900 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-purple-500/50 to-transparent"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-extrabold font-outfit text-white mb-4">
                Kenapa Memilih <span class="gradient-text">Kami?</span>
            </h2>
            <p class="text-xl text-blue-200 max-w-2xl mx-auto">
                Lebih dari sekadar developer, kami adalah partner strategis digital Anda di Solo dan seluruh Indonesia
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $reasons = [
                ['⚡ Pengerjaan Cepat', 'Timeline jelas dengan delivery tepat waktu tanpa mengorbankan kualitas', 'blue'],
                ['🎯 Hasil Terukur', 'Tracking progress real-time dan laporan berkala untuk setiap proyek', 'purple'],
                ['💼 Berpengalaman', 'Tim profesional dengan portofolio beragam industri di Surakarta', 'pink'],
                ['🔒 Keamanan Terjamin', 'Best practices security untuk melindungi data bisnis Anda', 'emerald'],
                ['🤝 Support 24/7', 'Dukungan teknis penuh setelah proyek selesai', 'orange'],
                ['💰 Harga Kompetitif', 'Kualitas premium dengan harga yang masuk akal untuk UMKM Solo', 'cyan']
            ];
            @endphp
            
            @foreach($reasons as $index => $reason)
            <div class="group p-6 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:border-{{ $reason[2] }}-500/50 transition-all duration-300 hover:-translate-y-2 animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="text-4xl mb-4">{{ $reason[0] }}</div>
                <h3 class="text-xl font-bold text-white mb-2">{{ $reason[0] }}</h3>
                <p class="text-blue-200">{{ $reason[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SEO CONTENT SECTION with LSI Keywords --}}
<section class="py-20 bg-gradient-to-b from-slate-900 to-black relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent"></div>
    
    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg prose-invert mx-auto">
            <h2 class="text-3xl md:text-4xl font-extrabold font-outfit text-white mb-8 text-center">
                Jasa <span class="gradient-text">Web Developer Profesional</span> di Solo
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/></svg>
                        Layanan Web Development Solo
                    </h3>
                    <p class="text-blue-200 text-sm leading-relaxed">
                        Sebagai <strong>web developer Solo</strong> berpengalaman, kami menyediakan jasa pembuatan website company profile, 
                        <strong>toko online</strong>, landing page conversion-focused, dan custom web application menggunakan 
                        teknologi modern seperti <strong>Laravel</strong>, WordPress, React.js, dan Vue.js. Setiap proyek dioptimalkan 
                        untuk Core Web Vitals, mobile-responsive, dan SEO-friendly structure.
                    </p>
                </div>
                
                <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                        Sistem Informasi & ERP Custom
                    </h3>
                    <p class="text-blue-200 text-sm leading-relaxed">
                        Kami mengembangkan <strong>sistem informasi bisnis</strong> tailored untuk kebutuhan spesifik perusahaan Anda, 
                        termasuk ERP system, CRM customization, inventory management, POS system, HRIS, dan database architecture. 
                        Solusi kami membantu efisiensi operasional bisnis di Surakarta dan seluruh Jawa Tengah.
                    </p>
                </div>
                
                <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-pink-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                        AI Automation & Chatbot Indonesia
                    </h3>
                    <p class="text-blue-200 text-sm leading-relaxed">
                        Tingkatkan produktivitas dengan <strong>AI automation solutions</strong>: WhatsApp chatbot, customer service automation, 
                        business process automation, data processing dengan machine learning, dan integrasi API third-party. 
                        Solusi cerdas untuk transformasi digital bisnis modern.
                    </p>
                </div>
                
                <div class="p-6 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/></svg>
                        Jasa SEO & Link Building Solo
                    </h3>
                    <p class="text-blue-200 text-sm leading-relaxed">
                        Services <strong>SEO specialist Solo</strong>: optimasi on-page, technical SEO audit, keyword research, 
                        <strong>link building service</strong> dengan strategi <strong>Backlink Pyramid</strong> yang aman (Tier 1: High Authority, Tier 2: Social Signals, Tier 3: Web 2.0), 
                        guest post di situs authoritative, niche edit contextual links, Google Maps optimization untuk local SEO, 
                        dan strategi content marketing untuk meningkatkan organic traffic secara berkelanjutan tanpa risiko penalti.
                    </p>
                </div>
                </div>
            </div>
            
            <div class="p-8 rounded-3xl bg-gradient-to-br from-blue-900/30 to-purple-900/30 backdrop-blur-md border border-blue-500/30">
                <h3 class="text-2xl font-bold text-white mb-6">Area Layanan Juki Website Developer</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-blue-200 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Surakarta / Solo
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Jawa Tengah
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Yogyakarta
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Semarang
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Jakarta
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Bandung
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Surabaya
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Seluruh Indonesia
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MAP & CONTACT SECTION --}}
<section id="contact" class="py-24 bg-gradient-to-b from-slate-900 to-black relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-extrabold font-outfit text-white mb-4">
                Lokasi & <span class="gradient-text">Kontak</span>
            </h2>
            <p class="text-xl text-blue-200 max-w-2xl mx-auto">
                Kunjungi kami atau hubungi untuk konsultasi gratis
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {{-- Map --}}
            <div class="map-container animate-fade-in-up">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.4148800710145!2d110.8178768!3d-7.6384539999999985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a3da40ed41c69%3A0x2601c6922ac80dd4!2sJuki%20Website%20Developer%20Solo!5e0!3m2!1sid!2sid!4v1785748798166!5m2!1sid!2sid" 
                    width="100%" 
                    height="500" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Lokasi Juki Website Developer Solo">
                </iframe>
            </div>
            
            {{-- Contact Info --}}
            <div class="flex flex-col justify-center space-y-8 animate-fade-in-up delay-200">
                <div class="p-8 rounded-3xl bg-gradient-to-br from-blue-600/20 to-purple-600/20 backdrop-blur-md border border-blue-500/30">
                    <h3 class="text-2xl font-bold font-outfit text-white mb-6">Hubungi Kami</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-400 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-semibold mb-1">Alamat</h4>
                                <p class="text-blue-200">Surakarta, Jawa Tengah, Indonesia</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-semibold mb-1">Email</h4>
                                <p class="text-blue-200">contact@juki.eu.org</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-pink-500/20 flex items-center justify-center text-pink-400 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-semibold mb-1">Jam Operasional</h4>
                                <p class="text-blue-200">Senin - Jumat: 09:00 - 17:00 WIB</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-white/10">
                        <a href="https://wa.me/6281234567890" target="_blank" class="btn-glow w-full inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white font-bold text-lg shadow-xl shadow-green-500/30 transition-all hover:scale-105">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FINAL CTA --}}
<section class="py-24 bg-black relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(59,130,246,0.15),transparent_70%)]"></div>
    
    <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-6xl font-extrabold font-outfit text-white mb-6">
            Siap <span class="gradient-text">Transformasi</span> Digital Anda?
        </h2>
        <p class="text-xl text-blue-200 mb-12 max-w-2xl mx-auto">
            Jangan biarkan kompetitor mendahului Anda. Mulai proyek Anda hari ini bersama Juki Website Developer Solo.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="https://wa.me/6281234567890" target="_blank" class="btn-glow px-10 py-5 rounded-2xl bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 hover:from-blue-400 hover:via-purple-400 hover:to-pink-400 text-white font-bold text-xl shadow-2xl shadow-blue-500/40 transition-all hover:scale-105 active:scale-95">
                🚀 Mulai Proyek Sekarang
            </a>
            <a href="{{ route('contact.show') }}" class="px-10 py-5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold text-xl hover:bg-white/20 transition-all hover:scale-105">
                📧 Hubungi Kami
            </a>
        </div>
    </div>
</section>

{{-- FOOTER BADGE --}}
<footer class="py-8 bg-black border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p class="text-blue-300 text-sm">
            © {{ date('Y') }} <strong class="text-white">Juki Website Developer Solo</strong>. All rights reserved.
        </p>
    </div>
</footer>
@endsection
