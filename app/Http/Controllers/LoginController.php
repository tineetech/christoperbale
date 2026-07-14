<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $beUrl = rtrim(env('BE_URL', 'http://127.0.0.1:8002'), '/');

        $response = Http::post($beUrl . '/api/login', [
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json([
                'success' => true,
                'token'   => $data['token'],
                'message' => $data['message'] ?? 'Login berhasil',
            ]);
        }

        $data = $response->json();
        $message = $data['message'] ?? 'Email atau password salah';

        return response()->json([
            'success' => false,
            'message' => $message,
        ], 401);
    }
}
