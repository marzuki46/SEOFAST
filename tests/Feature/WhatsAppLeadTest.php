<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use App\Models\SystemSetting;
use App\Http\Middleware\ForceHttps;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppLeadTest extends TestCase
{
    use RefreshDatabase;

    protected array $validPayload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ForceHttps::class);

        $this->validPayload = [
            'name' => 'Juki',
            'phone' => '082213028718',
            'subject' => 'Paket Website',
            'message' => 'Saya butuh website company profile untuk toko saya.',
            'source' => 'contact',
        ];
    }

    protected function lead(array $overrides = []): \Illuminate\Testing\TestResponse
    {
        return $this->post('/lead', array_merge($this->validPayload, $overrides));
    }

    public function test_honeypot_is_rejected_silently(): void
    {
        $response = $this->lead(['website' => 'http://bot.example.com']);

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_inquiries', 0);
    }

    public function test_valid_lead_is_stored_and_forwarded_to_whatsapp(): void
    {
        SystemSetting::set('contact_inquiry_whatsapp', '6282213028718', 'general', 'string');

        $response = $this->lead();

        $response->assertRedirectContains('https://wa.me/6282213028718');

        $this->assertDatabaseHas('contact_inquiries', [
            'name' => 'Juki',
            'source' => 'contact',
            'is_spam' => false,
        ]);
    }

    public function test_spam_lead_is_stored_but_not_forwarded(): void
    {
        SystemSetting::set('contact_inquiry_whatsapp', '6282213028718', 'general', 'string');
        SystemSetting::set('antispam_enabled', true, 'whatsapp', 'boolean');
        SystemSetting::set('antispam_blocked_keywords', 'beli followers', 'whatsapp', 'string');

        $response = $this->lead([
            'message' => 'Promo jasa seo murah beli followers sekarang juga.',
        ]);

        $response->assertSessionHas('success');
        $response->assertDontSee('wa.me');

        $this->assertDatabaseHas('contact_inquiries', [
            'name' => 'Juki',
            'source' => 'contact',
            'is_spam' => true,
        ]);
    }

    public function test_enterprise_source_requires_email(): void
    {
        $response = $this->lead([
            'source' => 'enterprise',
            'subject' => 'ERP',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('contact_inquiries', 0);
    }

    public function test_invalid_source_is_rejected(): void
    {
        $response = $this->lead(['source' => 'hacked']);

        $response->assertSessionHasErrors('source');
    }

    public function test_lead_without_whatsapp_setting_still_stored(): void
    {
        $response = $this->lead();

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_inquiries', [
            'name' => 'Juki',
            'source' => 'contact',
            'is_spam' => false,
        ]);
    }
}
