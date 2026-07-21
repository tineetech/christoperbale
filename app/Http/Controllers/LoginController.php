<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password]) ||
            Auth::attempt(['nama' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Login berhasil');
        }

        return back()->with('error', 'Email/nama atau password salah')->onlyInput('email');
    }
}
