<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register'); // pastikan file-nya ada di resources/views/auth/register.blade.php
    }

    public function register(Request $request)
    {
        // Validasi data input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Redirect ke dashboard atau halaman lain
        return redirect()->route('login')->with('success', 'Registrasi berhasil!');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                $kriteria = Kriteria::all()->count();
                $alternatif = Alternatif::all()->count();
                $userCount = User::where('role', 'user')->count();

                if ($kriteria == 0) {
                    $kriteria=0;
                }

                if ($alternatif == 0) {
                    $alternatif=0;
                }

                if($userCount == 0) {
                    $userCount=0;
                }
                return redirect()->intended('/admin');
            } elseif ($user->role === 'user') {
                return redirect()->intended('/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Role tidak dikenali.']);
            }
        }

        return redirect()->back()
            ->with('error', 'Email atau password salah.')
            ->withInput();
    }

    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $kriteria = Kriteria::all()->count();
            $alternatif = Alternatif::all()->count();
            $userCount = User::where('role', 'user')->count();

            if ($kriteria == 0) {
                $kriteria=0;
            }

            if ($alternatif == 0) {
                $alternatif=0;
            }

            if($userCount == 0) {
                $userCount=0;
            }
            return view('Admin.homepage', compact('kriteria', 'alternatif', 'userCount'));
        } elseif ($user->role === 'user') {
            return view('dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
