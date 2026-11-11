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
    public function test_services_index_page_returns_ok_and_contains_single_h1(): void
    {
        $response = $this->get('/id/jasa');
        $response->assertStatus(200);

        $content = $response->getContent();
        // Cek hanya boleh ada 1 tag <h1>
        $h1Count = preg_match_all('/<h1\b[^>]*>(.*?)<\/h1>/is', $content, $matches);
        $this->assertEquals(1, $h1Count, 'Halaman /id/jasa harus tepat memiliki satu H1');

        // Cek teks CTA standar di footer
        $this->assertStringContainsString('Punya proyek dalam pikiran?', $content);
        $this->assertStringContainsString('Mari bahas bagaimana saya dapat membantu Anda dalam pengembangan web, SEO, otomasi, atau AI.', $content);
        $this->assertStringContainsString('Hubungi Saya', $content);
        $this->assertStringContainsString('WhatsApp Saya', $content);
    }

    public function test_all_categorized_service_landing_pages_accessible(): void
    {
        $slugs = [
            'id/jasa/website',
            'id/jasa/website/company-profile',
            'id/jasa/website/landing-page',
            'id/jasa/website/toko-online',
            'id/jasa/website/wordpress',
            'id/jasa/aplikasi-web',
            'id/jasa/maintenance-website',
            'id/jasa/perbaikan-website',
            'id/jasa/website/solo',
            'id/jasa/seo',
            'id/jasa/seo-lokal',
            'id/jasa/google-maps-embed',
            'id/jasa/otomasi',
            'id/jasa/otomasi/n8n',
            'id/jasa/ai',
            'id/jasa/ai/content-engine',
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
}
