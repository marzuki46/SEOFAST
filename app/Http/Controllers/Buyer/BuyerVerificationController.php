<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class BuyerVerificationController extends Controller
{
    public function show(Request $request)
    {
        return $request->user('buyer')->hasVerifiedEmail()
            ? redirect()->route('buyer.dashboard')
            : view('buyer.auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('buyer.dashboard')->with('success', 'Email berhasil diverifikasi!');
    }

    public function send(Request $request)
    {
        $request->user('buyer')->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    }
}
