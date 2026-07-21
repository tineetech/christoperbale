<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function index(Request $request, string $token)
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect('/login')->withErrors(['email' => 'Link reset password tidak valid.']);
        }

        $user = Pengguna::where('email', $email)->first();

        if (!$user || !Password::broker()->getRepository()->exists($user, $token)) {
            return redirect('/login')->withErrors(['email' => 'Token reset password tidak valid atau sudah kedaluwarsa.']);
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

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset. Silakan login.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Token reset password tidak valid atau sudah kedaluwarsa.',
        ], 400);
    }
}
