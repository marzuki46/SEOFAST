<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $inquiries = ContactInquiry::latest()
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->paginate(15);

        $stats = [
            'unread' => ContactInquiry::where('status', 'unread')->count(),
            'read' => ContactInquiry::where('status', 'read')->count(),
            'replied' => ContactInquiry::where('status', 'replied')->count(),
        ];

        return view('admin.inquiries.index', compact('inquiries', 'stats', 'status'));
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
}
