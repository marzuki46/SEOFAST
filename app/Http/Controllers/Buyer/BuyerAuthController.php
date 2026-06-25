<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class BuyerAuthController extends Controller
{
    public function showLogin()
    {
        return view('buyer.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('buyer')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('buyer.dashboard'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function showRegister()
    {
        return view('buyer.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:buyers,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $buyer = Buyer::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('buyer')->login($buyer);

        return redirect()->route('buyer.dashboard');
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('buyer.login')->withErrors(['google' => 'Gagal login dengan Google. Silakan coba lagi.']);
        }

        $buyer = Buyer::updateOrCreate(
            ['google_id' => $googleUser->getId()],
            [
                'name'                 => $googleUser->getName(),
                'email'                => $googleUser->getEmail(),
                'avatar'               => $googleUser->getAvatar(),
                'email_verified_at'    => now(),
            ]
        );

        // If email already exists but no google_id, link account
        if (!$buyer->wasRecentlyCreated && !$buyer->google_id) {
            $buyer->update(['google_id' => $googleUser->getId(), 'avatar' => $googleUser->getAvatar()]);
        }

        Auth::guard('buyer')->login($buyer, remember: true);

        return redirect()->intended(route('buyer.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('buyer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('buyer.login')->with('success', 'Anda berhasil keluar.');
    }
}
