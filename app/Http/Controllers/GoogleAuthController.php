<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

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

        $beUrl = rtrim(env('BE_URL', 'http://127.0.0.1:8002'), '/');

        $response = Http::post($beUrl . '/api/auth/google', [
            'email' => $googleUser->getEmail(),
            'nama'  => $googleUser->getName(),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return view('auth-callback', [
                'token'   => $data['token'],
                'message' => $data['message'] ?? 'Login berhasil',
            ]);
        }

        $data = $response->json();
        $message = $data['message'] ?? 'Gagal autentikasi dengan Google.';

        return redirect('/login')->withErrors(['email' => $message]);
    }
}
