<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $beUrl = rtrim(env('BE_URL', 'http://127.0.0.1:8002'), '/');

        $response = Http::post($beUrl . '/api/register', [
            'nama'      => $request->name,
            'email'     => $request->email,
            'password'  => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'full_name' => $request->name,
            'phone'     => null,
            'gender'    => null,
        ]);

        if ($response->successful()) {
            return redirect('/login')->with('success', 'Registrasi berhasil! Silakan masuk.');
        }

        $errors = $response->json();

        if (isset($errors['errors'])) {
            return back()->withErrors($errors['errors'])->withInput();
        }

        if (isset($errors['message'])) {
            return back()->withErrors(['email' => $errors['message']])->withInput();
        }

        return back()->withErrors(['email' => 'Registrasi gagal. Silakan coba lagi.'])->withInput();
    }
}
