<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Models\SystemSetting;
use App\Services\SpamGuard;
use Illuminate\Http\Request;

class WhatsAppLeadController extends Controller
{
    protected array $sources = [
        'contact'    => 'Form Kontak',
        'chat'       => 'Live Chat WhatsApp',
        'enterprise' => 'Paket Custom / Enterprise',
    ];

    public function store(Request $request)
    {
        $source = in_array($request->input('source'), array_keys($this->sources), true)
            ? $request->input('source')
            : 'contact';

        $rules = [
            'name'    => ['required', 'string', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'alur'    => ['nullable', 'string', 'max:255'],
            'source'  => ['required', 'string', 'in:contact,chat,enterprise'],
            'website' => ['nullable', 'string', 'max:255'],
        ];

        $rules['email'] = $source === 'enterprise'
            ? ['required', 'email', 'max:255']
            : ['nullable', 'email', 'max:255'];

        $validated = $request->validate($rules);

        // Honeypot: kalau field tersembunyi ini terisi, berarti bot.
        // Diam-diam dianggap sukses tanpa menyimpan apa pun.
        if (!empty($validated['website'])) {
            return redirect()->back()->with('success', 'Terima kasih! Pesan Anda telah terkirim.');
        }

        $subject = trim((string) ($validated['subject'] ?? ''));
        $alur    = trim((string) ($validated['alur'] ?? ''));
        $message = trim((string) ($validated['message'] ?? ''));

        if ($source === 'enterprise') {
            $subject = $subject !== '' ? "Paket Custom / Enterprise — {$subject}" : 'Paket Custom / Enterprise';
        } elseif ($subject === '') {
            $subject = 'Konsultasi Website';
        }

        if ($alur !== '') {
            $message .= "\nAlur: {$alur}";
        }

        // Anti-spam: spam tetap disimpan (is_spam=true) tapi tidak dibuka ke WA pemilik.
        $spam = app(SpamGuard::class)->check($request, $validated);

        ContactInquiry::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'] ?? '',
            'phone'      => $validated['phone'] ?? null,
            'subject'    => $subject,
            'message'    => $message,
            'source'     => $source,
            'ip_address' => $request->ip(),
            'url'        => $request->headers->get('referer'),
            'is_spam'    => $spam['is_spam'],
        ]);

        if ($spam['is_spam']) {
            return redirect()->back()->with('success', 'Terima kasih! Pesan Anda telah terkirim. Saya akan menghubungi Anda segera.');
        }

        $whatsapp = (string) SystemSetting::get('contact_inquiry_whatsapp', '');

        if ($whatsapp === '') {
            return redirect()->back()->with('success', 'Terima kasih! Pesan Anda telah terkirim. Saya akan menghubungi Anda segera.');
        }

        $waLines = [
            "Halo! Ada inquiry baru via {$this->sources[$source]}:",
            '',
            '*Nama:* ' . $validated['name'],
            '*Email:* ' . ($validated['email'] ?? '-'),
            '*WhatsApp:* ' . ($validated['phone'] ?? '-'),
            '*Subjek / Paket:* ' . $subject,
        ];

        if ($alur !== '') {
            $waLines[] = '*Alur:* ' . $alur;
        }

        $waLines[] = '*Kebutuhan:* ' . $message;
        $waLines[] = '';
        $waLines[] = 'Terima kasih.';

        $waUrl = 'https://wa.me/' . $whatsapp . '?text=' . rawurlencode(implode("\n", $waLines));

        return redirect()->away($waUrl);
    }
}
