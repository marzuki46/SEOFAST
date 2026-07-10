<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketManagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $tickets = SupportTicket::with('buyer')
            ->withCount('replies')
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        $stats = [
            'open' => SupportTicket::where('status', 'open')->count(),
            'wait_response' => SupportTicket::where('status', 'wait_response')->count(),
            'on_progress' => SupportTicket::where('status', 'on_progress')->count(),
            'solved' => SupportTicket::where('status', 'solved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'stats', 'status'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['buyer', 'replies.user', 'replies.buyer', 'closedBy']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string|min:1',
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        if ($ticket->status === 'open' || $ticket->status === 'wait_response') {
            $ticket->update(['status' => 'on_progress']);
        }

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Balasan berhasil dikirim.');
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,wait_response,on_progress,solved,closed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'solved') {
            $data['solved_at'] = now();
        }

        if ($request->status === 'closed') {
            $data['closed_by'] = Auth::id();
        }

        if ($request->filled('admin_notes')) {
            $data['admin_notes'] = $request->admin_notes;
        }

        $ticket->update($data);

        $statusLabels = [
            'open' => 'Open',
            'wait_response' => 'Menunggu Respon',
            'on_progress' => 'On Progress',
            'solved' => 'Solved',
            'closed' => 'Closed',
        ];

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Status ticket diubah ke "' . ($statusLabels[$request->status] ?? $request->status) . '".');
    }
}
