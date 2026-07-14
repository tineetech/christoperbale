<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ResetPasswordController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect('/login')->withErrors(['email' => 'Link reset password tidak valid.']);
        }

        return view('reset-password', compact('token', 'email'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $beUrl = rtrim(env('BE_URL', 'http://127.0.0.1:8002'), '/');

        $response = Http::post($beUrl . '/api/reset-password', [
            'token'                 => $request->token,
            'email'                 => $request->email,
            'password'              => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset. Silakan login.',
            ]);
        }

        $data = $response->json();
        $message = $data['message'] ?? 'Token reset password tidak valid atau sudah kedaluwarsa.';

        return response()->json([
            'success' => false,
            'message' => $message,
        ], 400);
    }
}
