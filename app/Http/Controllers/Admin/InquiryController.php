<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\SystemSetting;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $spam = $request->boolean('spam');

        $inquiries = ContactInquiry::latest()
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($request->boolean('spam'), fn($q) => $q->spam(), fn($q) => $q->notSpam())
            ->when($request->filled('q'), fn($q) => $q->where(function ($w) use ($request) {
                $term = '%' . $request->q . '%';
                $w->where('name', 'like', $term)
                  ->orWhere('email', 'like', $term)
                  ->orWhere('phone', 'like', $term)
                  ->orWhere('subject', 'like', $term)
                  ->orWhere('message', 'like', $term);
            }))
            ->paginate(15);

        $stats = [
            'unread' => ContactInquiry::notSpam()->where('status', 'unread')->count(),
            'read' => ContactInquiry::notSpam()->where('status', 'read')->count(),
            'replied' => ContactInquiry::notSpam()->where('status', 'replied')->count(),
            'spam' => ContactInquiry::spam()->count(),
        ];

        return view('admin.inquiries.index', compact('inquiries', 'stats', 'status', 'spam'));
    }

    public function show(ContactInquiry $inquiry)
    {
        if ($inquiry->status === 'unread') {
            $inquiry->update(['status' => 'read']);
        }

        $inquiry->load('repliedBy');

        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function markReplied(Request $request, ContactInquiry $inquiry)
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $inquiry->update([
            'status' => 'replied',
            'admin_note' => $request->admin_note,
            'replied_at' => now(),
            'replied_by' => Auth::id(),
        ]);

        return redirect()->route('admin.inquiries.show', $inquiry)
            ->with('success', 'Inquiry ditandai sudah dibalas.');
    }

    public function toggleSpam(Request $request, ContactInquiry $inquiry)
    {
        $inquiry->update([
            'is_spam' => !$inquiry->is_spam,
        ]);

        return redirect()->back()
            ->with('success', $inquiry->is_spam ? 'Inquiry ditandai sebagai spam.' : 'Inquiry dikeluarkan dari spam.');
    }

    public function destroy(Request $request, ContactInquiry $inquiry)
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index', [
            'status' => $request->get('status', 'all'),
            'spam' => $request->boolean('spam') ? '1' : null,
        ])->with('success', 'Inquiry dihapus.');
    }

    public function sendWhatsapp(Request $request, ContactInquiry $inquiry)
    {
        $result = app(FonnteService::class)->send(
            $this->resolveTarget(),
            $this->formatInquiryMessage($inquiry)
        );

        return redirect()->back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:contact_inquiries,id',
            'action' => 'required|in:delete,whatsapp,spam,unspam',
        ]);

        $inquiries = ContactInquiry::whereIn('id', $request->ids)->get();

        return match ($request->action) {
            'delete' => $this->bulkDelete($inquiries),
            'whatsapp' => $this->bulkWhatsapp($inquiries),
            'spam' => $this->bulkMarkSpam($inquiries, true),
            'unspam' => $this->bulkMarkSpam($inquiries, false),
        };
    }

    protected function bulkDelete($inquiries)
    {
        $count = ContactInquiry::whereIn('id', $inquiries->pluck('id'))->delete();

        return redirect()->back()->with('success', "{$count} inquiry dihapus.");
    }

    protected function bulkMarkSpam($inquiries, bool $spam)
    {
        $count = ContactInquiry::whereIn('id', $inquiries->pluck('id'))
            ->update(['is_spam' => $spam]);

        return redirect()->back()->with('success', "{$count} inquiry di" . ($spam ? 'tandai' : 'keluarkan') . " sebagai spam.");
    }

    protected function bulkWhatsapp($inquiries)
    {
        $fonnte = app(FonnteService::class);
        $target = $this->resolveTarget();

        if (!$fonnte->isConfigured()) {
            return redirect()->back()->with('error', 'Token Fonnte belum dikonfigurasi. Isi di Settings → WhatsApp & Anti-Spam.');
        }

        $sent = 0;
        $failed = 0;
        $lastError = '';

        foreach ($inquiries as $inquiry) {
            $result = $fonnte->send($target, $this->formatInquiryMessage($inquiry));

            if ($result['success']) {
                $sent++;
            } else {
                $failed++;
                $lastError = $result['message'];
            }
        }

        $message = "{$sent} pesan terkirim ke WhatsApp.";
        if ($failed > 0) {
            $message .= " {$failed} gagal. " . $lastError;
        }

        return redirect()->back()->with($failed > 0 ? 'error' : 'success', $message);
    }

    protected function resolveTarget(): string
    {
        return (string) SystemSetting::get('whatsapp_fonnte_target', SystemSetting::get('contact_inquiry_whatsapp', ''));
    }

    protected function formatInquiryMessage(ContactInquiry $inquiry): string
    {
        $lines = [
            'Halo! Ada inquiry baru:',
            '',
            '*Nama:* ' . $inquiry->name,
            '*Email:* ' . ($inquiry->email ?: '-'),
            '*WhatsApp:* ' . ($inquiry->phone ?: '-'),
            '*Subjek:* ' . $inquiry->subject,
        ];

        if ($inquiry->source) {
            $lines[] = '*Sumber:* ' . $inquiry->sourceLabel();
        }

        $lines[] = '*Kebutuhan:* ' . $inquiry->message;
        $lines[] = '';
        $lines[] = 'Dikirim ' . $inquiry->created_at->format('d M Y H:i') . ' WIB via ' . config('app.url');

        return implode("\n", $lines);
    }
}
