<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\User;
use App\Services\IndexNowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IndexNowAndRssTest extends TestCase
{
    use RefreshDatabase;

    public function test_rss_feed_returns_valid_xml(): void
    {
        $response = $this->get('/feed');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/rss+xml; charset=utf-8');

        $content = $response->getContent();
        $this->assertStringContainsString('<rss version="2.0"', $content);
        $this->assertStringContainsString('<channel>', $content);
        $this->assertStringContainsString('https://pubsubhubbub.appspot.com/', $content);

        // Alias /rss.xml
        $responseAlias = $this->get('/rss.xml');
        $responseAlias->assertStatus(200);
    }

    public function test_indexnow_key_txt_route_returns_exact_key(): void
    {
        $service = app(IndexNowService::class);
        $key = $service->getKey();

        $response = $this->get('/' . $key . '.txt');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $this->assertEquals($key, trim($response->getContent()));

        // Non-matching key should 404
        $this->get('/invalid-key.txt')->assertStatus(404);
    }

    public function test_indexnow_service_submits_payload(): void
    {
        Http::fake([
            'https://api.indexnow.org/*' => Http::response(['success' => true], 200),
            'https://www.bing.com/*' => Http::response(['success' => true], 200),
            'https://www.google.com/*' => Http::response('OK', 200),
            'https://pubsubhubbub.appspot.com/*' => Http::response('OK', 204),
        ]);

        $service = app(IndexNowService::class);
        $result = $service->submit(['https://juki.eu.org/blog/test-article', 'https://juki.eu.org/jasa']);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals(2, $result['submitted']);
    }

    public function test_admin_can_trigger_indexnow_ping(): void
    {
        Http::fake([
            'https://api.indexnow.org/*' => Http::response(['success' => true], 200),
            'https://www.bing.com/*' => Http::response(['success' => true], 200),
            'https://www.google.com/*' => Http::response('OK', 200),
            'https://pubsubhubbub.appspot.com/*' => Http::response('OK', 204),
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/content/indexnow-ping');
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
