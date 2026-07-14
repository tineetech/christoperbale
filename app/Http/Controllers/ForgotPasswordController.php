<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $beUrl = rtrim(env('BE_URL', 'http://127.0.0.1:8002'), '/');

        $response = Http::post($beUrl . '/api/forgot-password', [
            'email' => $request->email,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json([
                'success' => true,
                'message' => $data['message'] ?? 'Tautan reset password telah dikirim ke email Anda. Silakan cek inbox utama/kotak spam.',
            ]);
        }

        $data = $response->json();
        $message = $data['message'] ?? 'Email tidak ditemukan.';

        return response()->json([
            'success' => false,
            'message' => $message,
        ], 404);
    }
}
