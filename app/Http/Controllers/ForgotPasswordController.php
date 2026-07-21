<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Tautan reset password telah dikirim ke email Anda.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email tidak ditemukan.',
        ], 404);
    }
}
