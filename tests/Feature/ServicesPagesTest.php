<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\JukiServicesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JukiServicesSeeder::class);
    }

    public function test_legacy_id_jasa_redirects_permanently_to_jasa(): void
    {
        $response = $this->get('/id/jasa');
        $response->assertStatus(301);
        $response->assertRedirect('/jasa');

        $responseSub = $this->get('/id/jasa/website');
        $responseSub->assertStatus(301);
        $responseSub->assertRedirect('/jasa/website');
    }

    public function test_services_index_page_returns_ok_and_contains_single_h1(): void
    {
        $response = $this->get('/jasa');
        $response->assertStatus(200);

        $content = $response->getContent();
        // Cek hanya boleh ada 1 tag <h1>
        $h1Count = preg_match_all('/<h1\b[^>]*>(.*?)<\/h1>/is', $content, $matches);
        $this->assertEquals(1, $h1Count, 'Halaman /jasa harus tepat memiliki satu H1');

        // Cek teks CTA standar di footer
        $this->assertStringContainsString('Punya proyek dalam pikiran?', $content);
        $this->assertStringContainsString('Mari bahas bagaimana saya dapat membantu Anda dalam pengembangan web, SEO, otomasi, atau AI.', $content);
        $this->assertStringContainsString('Hubungi Saya', $content);
        $this->assertStringContainsString('WhatsApp Saya', $content);
    }

    public function test_all_categorized_service_landing_pages_accessible(): void
    {
        $slugs = [
            'jasa/website',
            'jasa/website/company-profile',
            'jasa/website/landing-page',
            'jasa/website/toko-online',
            'jasa/website/wordpress',
            'jasa/aplikasi-web',
            'jasa/maintenance-website',
            'jasa/perbaikan-website',
            'jasa/website/solo',
            'jasa/seo',
            'jasa/seo-lokal',
            'jasa/google-maps-embed',
            'jasa/otomasi',
            'jasa/otomasi/n8n',
            'jasa/ai',
            'jasa/ai/content-engine',
        ];

        foreach ($slugs as $slug) {
            $response = $this->get('/' . $slug);
            $response->assertStatus(200);

            $content = $response->getContent();
            // Setiap landing page wajib tepat 1 H1
            $h1Count = preg_match_all('/<h1\b[^>]*>(.*?)<\/h1>/is', $content, $matches);
            $this->assertEquals(1, $h1Count, "Landing page {$slug} harus tepat memiliki satu H1");

            // Cek CTA closing section
            $this->assertStringContainsString('Punya proyek dalam pikiran?', $content);
            $this->assertStringContainsString('WhatsApp Saya', $content);
        }
    }

    public function test_primary_service_pages_are_local_landing_pages_with_seo_depth(): void
    {
        $expectations = [
            'jasa/seo' => 'Jasa SEO Solo',
            'jasa/otomasi' => 'Jasa Otomasi Bisnis di Solo',
            'jasa/ai' => 'Jasa Integrasi AI untuk Bisnis di Solo',
        ];

        foreach ($expectations as $slug => $titleFragment) {
            $response = $this->get('/' . $slug);
            $response->assertOk();

            $content = $response->getContent();
            $visible = preg_replace('/<style\b[^>]*>.*?<\/style>|<script\b[^>]*>.*?<\/script>/is', ' ', $content);
            $visible = html_entity_decode(strip_tags($visible));
            preg_match_all('/\b[\p{L}\p{N}][\p{L}\p{N}-]*\b/u', $visible, $words);

            $this->assertGreaterThanOrEqual(1500, count($words[0]), "Landing page {$slug} harus memiliki minimal 1.500 kata terlihat");
            $this->assertStringContainsString($titleFragment, $content);
            $this->assertStringContainsString('"@type":"Service"', $content);
            $this->assertStringContainsString('"@type":"BreadcrumbList"', $content);
            $this->assertStringContainsString('"@type":"FAQPage"', $content);
            $this->assertStringContainsString('/jasa/website', $content);
            $this->assertStringContainsString('/contact', $content);
        }
    }
}
