<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:pengguna,email',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = Pengguna::create([
            'nama'      => $request->name,
            'full_name' => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => 1,
        ]);

        event(new Registered($user));

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan masuk.');
    }
}
