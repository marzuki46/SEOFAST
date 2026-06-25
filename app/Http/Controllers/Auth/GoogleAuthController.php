<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal login menggunakan Google.']);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Update google id and avatar if logging in for the first time with Google
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            }

            Auth::login($user, remember: true);
            return redirect()->intended(route('dashboard'));
        }

        return redirect()->route('login')->withErrors(['email' => 'Email belum terdaftar di sistem admin.']);
    }
}
