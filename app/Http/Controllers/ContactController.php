<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        ContactInquiry::create($validated);

        $adminEmail = SystemSetting::get('contact_inquiry_email', '');

        $phone = $validated['phone'] ?? '-';

        if ($adminEmail) {
            try {
                Mail::raw(
                    "Name: {$validated['name']}\n" .
                    "Email: {$validated['email']}\n" .
                    "Phone: {$phone}\n" .
                    "Subject: {$validated['subject']}\n\n" .
                    "Message:\n{$validated['message']}",
                    function ($message) use ($validated, $adminEmail) {
                        $message->to($adminEmail)
                            ->subject('[Inquiry] ' . $validated['subject']);
                    }
                );
            } catch (\Exception $e) {
                // If mail fails, silently continue — inquiry is still logged to WA
            }
        }

        $whatsapp = SystemSetting::get('contact_inquiry_whatsapp', '');
        if ($whatsapp) {
            $waText = "Halo! Ada inquiry baru:%0A%0A" .
                "*Nama:* {$validated['name']}%0A" .
                "*Email:* {$validated['email']}%0A" .
                "*Phone:* {$phone}%0A" .
                "*Subject:* {$validated['subject']}%0A" .
                "*Message:* {$validated['message']}";
            $waUrl = "https://wa.me/{$whatsapp}?text={$waText}";
            return redirect()->away($waUrl);
        }

        return redirect()->route('contact.show')
            ->with('success', 'Terima kasih! Pesan Anda telah terkirim. Saya akan menghubungi Anda segera.');
    }
}
