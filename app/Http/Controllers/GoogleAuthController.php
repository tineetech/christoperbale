<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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
            return redirect('/login')->withErrors(['email' => 'Gagal autentikasi dengan Google.']);
        }

        $pengguna = Pengguna::where('email', $googleUser->getEmail())->first();

        if (!$pengguna) {
            // Register baru
            $pengguna = Pengguna::create([
                'nama'          => $googleUser->getName(),
                'email'         => $googleUser->getEmail(),
                'photo_profile' => $googleUser->getAvatar(),
                'password'      => Hash::make(Str::random(16)),
                'role_id'       => 1,
            ]);
        }

        Auth::login($pengguna);
        request()->session()->regenerate();

        return redirect()->intended('/dashboard')->with('success', 'Login berhasil');
    }
}
