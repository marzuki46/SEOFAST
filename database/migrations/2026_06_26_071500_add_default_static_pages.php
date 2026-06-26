<?php
 
use Illuminate\Database\Migrations\Migration;
use App\Models\Page;
 
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert Terms of Service
        if (!Page::where('slug', 'terms-of-service')->exists()) {
            Page::create([
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'meta_title' => 'Terms of Service - SEOFAST',
                'meta_description' => 'Read our terms and conditions for using the SEOFAST website and platform.',
                'html_content' => '<div class="py-20 bg-slate-50 min-h-screen">
    <div class="mx-auto max-w-4xl bg-white p-10 md:p-16 rounded-3xl border border-slate-200/80 shadow-sm">
        <h1 class="text-4xl font-extrabold font-outfit text-slate-900 tracking-tight mb-8">Terms of Service</h1>
        <div class="prose prose-slate max-w-none space-y-6 text-slate-600 leading-relaxed">
            <p>Welcome to SEOFAST. These terms and conditions outline the rules and regulations for the use of SEOFAST\'s Website.</p>
            
            <h2 class="text-2xl font-bold font-outfit text-slate-800 mt-8 mb-4">1. Acceptance of Terms</h2>
            <p>By accessing this website, we assume you accept these terms and conditions in full. Do not continue to use SEOFAST if you do not agree to take all of the terms and conditions stated on this page.</p>
            
            <h2 class="text-2xl font-bold font-outfit text-slate-800 mt-8 mb-4">2. Intellectual Property Rights</h2>
            <p>Unless otherwise stated, SEOFAST and/or its licensors own the intellectual property rights for all material on SEOFAST. All intellectual property rights are reserved. You may access this from SEOFAST for your own personal use subjected to restrictions set in these terms and conditions.</p>
            
            <h2 class="text-2xl font-bold font-outfit text-slate-800 mt-8 mb-4">3. User Restrictions</h2>
            <p>You are specifically restricted from all of the following:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Publishing any website material in any other media.</li>
                <li>Selling, sublicensing, and/or otherwise commercializing any website material.</li>
                <li>Publicly performing and/or showing any website material.</li>
                <li>Using this website in any way that is or may be damaging to this website.</li>
            </ul>
 
            <h2 class="text-2xl font-bold font-outfit text-slate-800 mt-8 mb-4">4. Limitation of Liability</h2>
            <p>In no event shall SEOFAST, nor any of its officers, directors, and employees, be held liable for anything arising out of or in any way connected with your use of this website whether such liability is under contract. SEOFAST, including its officers, directors, and employees shall not be held liable for any indirect, consequential, or special liability arising out of or in any way related to your use of this website.</p>
 
            <h2 class="text-2xl font-bold font-outfit text-slate-800 mt-8 mb-4">5. Governing Law</h2>
            <p>These terms will be governed by and interpreted in accordance with the laws of the jurisdiction in which we operate, and you submit to the non-exclusive jurisdiction of the state and federal courts for the resolution of any disputes.</p>
        </div>
    </div>
</div>',
                'css_content' => '',
                'is_published' => true,
                'is_homepage' => false
            ]);
        }
 
        // Insert Privacy Policy
        if (!Page::where('slug', 'privacy-policy')->exists()) {
            Page::create([
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'meta_title' => 'Privacy Policy - SEOFAST',
                'meta_description' => 'Learn how SEOFAST collects, uses, and protects your personal information.',
                'html_content' => '<div class="py-20 bg-slate-50 min-h-screen">
    <div class="mx-auto max-w-4xl bg-white p-10 md:p-16 rounded-3xl border border-slate-200/80 shadow-sm">
        <h1 class="text-4xl font-extrabold font-outfit text-slate-900 tracking-tight mb-8">Privacy Policy</h1>
        <div class="prose prose-slate max-w-none space-y-6 text-slate-600 leading-relaxed">
            <p>At SEOFAST, accessible from our website, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by SEOFAST and how we use it.</p>
            
            <h2 class="text-2xl font-bold font-outfit text-slate-800 mt-8 mb-4">1. Information We Collect</h2>
            <p>We collect several different types of information for various purposes to provide and improve our Service to you. This may include personal data such as email address, usage data, and cookies.</p>
            
            <h2 class="text-2xl font-bold font-outfit text-slate-800 mt-8 mb-4">2. How We Use Your Information</h2>
            <p>We use the collected data for various purposes, including to:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Provide and maintain our Service.</li>
                <li>Notify you about changes to our Service.</li>
                <li>Provide customer support.</li>
                <li>Monitor the usage of our Service and detect, prevent, and address technical issues.</li>
            </ul>
            
            <h2 class="text-2xl font-bold font-outfit text-slate-800 mt-8 mb-4">3. Log Files</h2>
            <p>SEOFAST follows a standard procedure of using log files. These files log visitors when they visit websites. All hosting companies do this and a part of hosting services\' analytics. The information collected by log files include internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the number of clicks.</p>
 
            <h2 class="text-2xl font-bold font-outfit text-slate-800 mt-8 mb-4">4. Cookies and Web Beacons</h2>
            <p>Like any other website, SEOFAST uses \'cookies\'. These cookies are used to store information including visitors\' preferences, and the pages on the website that the visitor accessed or visited. The information is used to optimize the users\' experience by customizing our web page content based on visitors\' browser type and/or other information.</p>
 
            <h2 class="text-2xl font-bold font-outfit text-slate-800 mt-8 mb-4">5. Consent</h2>
            <p>By using our website, you hereby consent to our Privacy Policy and agree to its terms.</p>
        </div>
    </div>
</div>',
                'css_content' => '',
                'is_published' => true,
                'is_homepage' => false
            ]);
        }
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Page::whereIn('slug', ['terms-of-service', 'privacy-policy'])->delete();
    }
};
