<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BuyerTicketController extends Controller
{
    public function index()
    {
        $buyer = Auth::guard('buyer')->user();
        $tickets = SupportTicket::where('buyer_id', $buyer->id)
            ->withCount('replies')
            ->latest()
            ->paginate(10);

        return view('buyer.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('buyer.tickets.create');
    }

    public function store(Request $request)
    {
        $buyer = Auth::guard('buyer')->user();

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'category' => 'nullable|string|max:50',
            'priority' => 'required|in:low,medium,high,urgent',
            'message' => 'required|string|min:10',
        ], [
            'subject.required' => 'Subjek harus diisi.',
            'message.required' => 'Pesan harus diisi.',
            'message.min' => 'Pesan minimal 10 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $ticket = SupportTicket::create([
            'buyer_id' => $buyer->id,
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'message' => $request->message,
            'status' => 'open',
        ]);

        return redirect()->route('buyer.tickets.show', $ticket)
            ->with('success', 'Ticket #' . $ticket->ticket_number . ' berhasil dibuat. Tim kami akan merespon segera.');
    }

    public function show(SupportTicket $ticket)
    {
        $buyer = Auth::guard('buyer')->user();

        if ($ticket->buyer_id !== $buyer->id) {
            abort(403);
        }

        $replies = $ticket->replies()->with(['user', 'buyer'])->oldest()->get();

        return view('buyer.tickets.show', compact('ticket', 'replies'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $buyer = Auth::guard('buyer')->user();

        if ($ticket->buyer_id !== $buyer->id) {
            abort(403);
        }

        if (in_array($ticket->status, ['solved', 'closed'])) {
            return redirect()->back()->with('error', 'Ticket sudah selesai/closed. Silakan buka ticket baru jika ada kendala lain.');
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:1',
        ], [
            'message.required' => 'Pesan harus diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'buyer_id' => $buyer->id,
            'message' => $request->message,
        ]);

        $ticket->update(['status' => 'wait_response']);

        return redirect()->route('buyer.tickets.show', $ticket)
            ->with('success', 'Balasan berhasil dikirim.');
    }
}
