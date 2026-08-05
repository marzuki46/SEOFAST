<?php

namespace Tests\Unit;

use App\Models\ContactInquiry;
use App\Models\SystemSetting;
use App\Services\SpamGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SpamGuardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SystemSetting::set('antispam_enabled', true, 'whatsapp', 'boolean');
        SystemSetting::set('antispam_max_links', 2, 'whatsapp', 'integer');
        SystemSetting::set('antispam_min_message_length', 3, 'whatsapp', 'integer');
        SystemSetting::set('antispam_blocked_keywords', '', 'whatsapp', 'string');
        SystemSetting::set('antispam_blocked_emails', '', 'whatsapp', 'string');
        SystemSetting::set('antispam_repeat_window_minutes', 60, 'whatsapp', 'integer');
        SystemSetting::set('antispam_max_per_identity', 3, 'whatsapp', 'integer');
        SystemSetting::set('antispam_max_per_ip', 5, 'whatsapp', 'integer');
    }

    protected function check(array $data, array $server = []): array
    {
        $request = Request::create('/lead', 'POST', $data, [], [], $server);

        return app(SpamGuard::class)->check($request, $data);
    }

    public function test_clean_message_is_not_spam(): void
    {
        $result = $this->check([
            'name' => 'Juki',
            'email' => 'juki@example.com',
            'subject' => 'Konsultasi Website',
            'message' => 'Saya butuh website company profile.',
        ]);

        $this->assertFalse($result['is_spam']);
        $this->assertSame('', $result['reason']);
    }

    public function test_flags_message_with_too_many_links(): void
    {
        $result = $this->check([
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'subject' => 'Promo',
            'message' => 'Halo https://a.com https://b.com https://c.com murah',
        ]);

        $this->assertTrue($result['is_spam']);
        $this->assertStringContainsString('link', $result['reason']);
    }

    public function test_flags_short_message(): void
    {
        $result = $this->check([
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'subject' => 'Promo',
            'message' => 'ok',
        ]);

        $this->assertTrue($result['is_spam']);
        $this->assertStringContainsString('pendek', $result['reason']);
    }

    public function test_flags_blocked_keyword(): void
    {
        SystemSetting::set('antispam_blocked_keywords', "iklan\nbeli followers", 'whatsapp', 'string');

        $result = $this->check([
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'subject' => 'Promo',
            'message' => 'Jasa seo murah beli followers sekarang.',
        ]);

        $this->assertTrue($result['is_spam']);
        $this->assertStringContainsString('terlarang', $result['reason']);
    }

    public function test_flags_blocked_email_domain(): void
    {
        SystemSetting::set('antispam_blocked_emails', 'spamdomain.com', 'whatsapp', 'string');

        $result = $this->check([
            'name' => 'Bot',
            'email' => 'anyone@spamdomain.com',
            'subject' => 'Promo',
            'message' => 'Konten biasa yang panjang dan tidak mencurigakan.',
        ]);

        $this->assertTrue($result['is_spam']);
        $this->assertStringContainsString('diblokir', $result['reason']);
    }

    public function test_flags_identity_repeat(): void
    {
        ContactInquiry::create([
            'name' => 'A',
            'email' => 'repeat@example.com',
            'subject' => 'x',
            'message' => 'Pesan pertama',
            'is_spam' => false,
        ]);
        ContactInquiry::create([
            'name' => 'A',
            'email' => 'repeat@example.com',
            'subject' => 'x',
            'message' => 'Pesan kedua',
            'is_spam' => false,
        ]);
        ContactInquiry::create([
            'name' => 'A',
            'email' => 'repeat@example.com',
            'subject' => 'x',
            'message' => 'Pesan ketiga',
            'is_spam' => false,
        ]);

        $result = $this->check([
            'name' => 'A',
            'email' => 'repeat@example.com',
            'subject' => 'x',
            'message' => 'Pesan keempat yang cukup panjang',
        ]);

        $this->assertTrue($result['is_spam']);
        $this->assertStringContainsString('identitas', $result['reason']);
    }

    public function test_disabled_guard_never_flags(): void
    {
        SystemSetting::set('antispam_enabled', false, 'whatsapp', 'boolean');

        $result = $this->check([
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'subject' => 'Promo',
            'message' => 'Halo https://a.com https://b.com https://c.com',
        ]);

        $this->assertFalse($result['is_spam']);
    }
}
