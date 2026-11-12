<?php

namespace Tests\Unit;

use App\Services\HtmlSanitizer;
use App\Services\ImageService;
use PHPUnit\Framework\TestCase;

class SecurityServicesTest extends TestCase
{
    public function test_html_sanitizer_removes_active_content_and_unsafe_urls(): void
    {
        $html = '<p onclick="alert(1)">Safe<script>alert(2)</script>'
            .'<a href="javascript:alert(3)" target="_blank">link</a>'
            .'<img src="https://cdn.example.com/image.jpg" onerror="alert(4)"></p>';

        $clean = (new HtmlSanitizer)->sanitize($html);

        $this->assertStringContainsString('Safe', $clean);
        $this->assertStringContainsString('https://cdn.example.com/image.jpg', $clean);
        $this->assertStringContainsString('noopener noreferrer', $clean);
        $this->assertStringNotContainsStringIgnoringCase('<script', $clean);
        $this->assertStringNotContainsStringIgnoringCase('onclick', $clean);
        $this->assertStringNotContainsStringIgnoringCase('onerror', $clean);
        $this->assertStringNotContainsStringIgnoringCase('javascript:', $clean);
    }

    public function test_remote_image_url_rejects_local_and_private_targets(): void
    {
        $service = new ImageService;

        $this->assertFalse($service->isSafeRemoteUrl('file:///etc/passwd'));
        $this->assertFalse($service->isSafeRemoteUrl('http://localhost/image.jpg'));
        $this->assertFalse($service->isSafeRemoteUrl('http://127.0.0.1/image.jpg'));
        $this->assertFalse($service->isSafeRemoteUrl('http://169.254.169.254/latest/meta-data'));
        $this->assertFalse($service->isSafeRemoteUrl('http://10.0.0.10/image.jpg'));
    }
}
