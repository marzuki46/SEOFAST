<?php

namespace App\Services;

use App\Models\ContactInquiry;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SpamGuard
{
    protected array $reasons = [];

    public function enabled(): bool
    {
        return (bool) SystemSetting::get('antispam_enabled', true);
    }

    /**
     * Check a submitted inquiry for spam signals.
     *
     * @return array{is_spam: bool, reason: string}
     */
    public function check(Request $request, array $data): array
    {
        if (!$this->enabled()) {
            return ['is_spam' => false, 'reason' => ''];
        }

        $this->reasons = [];

        $this->checkLinks($data['message'] ?? '');
        $this->checkShortMessage($data['message'] ?? '');
        $this->checkBlockedKeywords($data['message'] ?? '', $data['name'] ?? '', $data['subject'] ?? '');
        $this->checkBlockedEmail($data['email'] ?? '');
        $this->checkIdentityRepeat($data);
        $this->checkIpRepeat($request);

        if (count($this->reasons) === 0) {
            return ['is_spam' => false, 'reason' => ''];
        }

        return ['is_spam' => true, 'reason' => implode('; ', array_slice($this->reasons, 0, 3))];
    }

    protected function checkLinks(string $message): void
    {
        $maxLinks = (int) SystemSetting::get('antispam_max_links', 2);

        if ($maxLinks < 0 || trim($message) === '') {
            return;
        }

        $count = preg_match_all('/https?:\/\/[^\s]+/i', $message);

        if ($count > $maxLinks) {
            $this->reasons[] = "Terlalu banyak link ({$count}).";
        }
    }

    protected function checkShortMessage(string $message): void
    {
        $minLength = (int) SystemSetting::get('antispam_min_message_length', 3);

        if ($minLength > 0 && mb_strlen(trim($message)) < $minLength) {
            $this->reasons[] = 'Pesan terlalu pendek.';
        }
    }

    protected function checkBlockedKeywords(string $message, string $name, string $subject): void
    {
        $blockedRaw = (string) SystemSetting::get('antispam_blocked_keywords', '');
        $keywords = $this->splitList($blockedRaw);

        if (count($keywords) === 0) {
            return;
        }

        $haystack = mb_strtolower($message . ' ' . $name . ' ' . $subject);

        foreach ($keywords as $keyword) {
            if (mb_strpos($haystack, mb_strtolower($keyword)) !== false) {
                $this->reasons[] = "Mengandung kata terlarang: {$keyword}.";
                return;
            }
        }
    }

    protected function checkBlockedEmail(string $email): void
    {
        $email = trim($email);

        if ($email === '') {
            return;
        }

        $blockedRaw = (string) SystemSetting::get('antispam_blocked_emails', '');
        $list = $this->splitList($blockedRaw);

        foreach ($list as $entry) {
            $entry = mb_strtolower(trim($entry));

            if ($entry === '') {
                continue;
            }

            $entry = ltrim($entry, '@');

            if (str_starts_with($entry, '*')) {
                // wildcard domain, e.g. *@spamdomain.com or *.spamdomain.com
                $needle = mb_strtolower(ltrim($entry, '*@'));
                if (mb_strpos(mb_strtolower($email), $needle) !== false) {
                    $this->reasons[] = "Email diblokir ({$entry}).";
                    return;
                }
            } elseif (mb_strpos(mb_strtolower($email), $entry) !== false) {
                $this->reasons[] = "Email diblokir ({$entry}).";
                return;
            }
        }
    }

    protected function checkIdentityRepeat(array $data): void
    {
        $windowMinutes = (int) SystemSetting::get('antispam_repeat_window_minutes', 60);
        $maxPerIdentity = (int) SystemSetting::get('antispam_max_per_identity', 3);

        if ($maxPerIdentity <= 0) {
            return;
        }

        $query = ContactInquiry::where('created_at', '>=', now()->subMinutes($windowMinutes))
            ->where('is_spam', false);

        $identity = null;
        if (!empty($data['email'])) {
            $identity = $data['email'];
            $query->where('email', $data['email']);
        } elseif (!empty($data['phone'])) {
            $identity = $data['phone'];
            $query->where('phone', $data['phone']);
        }

        if ($identity === null) {
            return;
        }

        $count = (clone $query)->count();

        if ($count >= $maxPerIdentity) {
            $this->reasons[] = "Terlalu banyak pengiriman dari identitas yang sama.";
        }
    }

    protected function checkIpRepeat(Request $request): void
    {
        $windowMinutes = (int) SystemSetting::get('antispam_repeat_window_minutes', 60);
        $maxPerIp = (int) SystemSetting::get('antispam_max_per_ip', 5);

        if ($maxPerIp <= 0) {
            return;
        }

        $ip = $request->ip();

        if ($ip === null) {
            return;
        }

        $count = ContactInquiry::where('ip_address', $ip)
            ->where('created_at', '>=', now()->subMinutes($windowMinutes))
            ->count();

        if ($count >= $maxPerIp) {
            $this->reasons[] = "Terlalu banyak pengiriman dari IP yang sama.";
        }
    }

    protected function splitList(string $raw): array
    {
        return array_filter(array_map('trim', preg_split('/[\r\n,]+/', $raw)), fn ($v) => $v !== '');
    }
}
