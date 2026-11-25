<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $r)
    {
        $c = $r->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($c, $r->boolean('remember'))) {
            $r->session()->regenerate();

            return redirect()->intended($this->home());
        }

        return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
    }

    public function showRegister(Request $r)
    {
        return view('auth.register', ['role' => $r->query('role', 'recipient')]);
    }

    public function register(Request $r)
    {
        $v = $r->validate([
            'role' => 'required|in:'.implode(',', array_keys(User::ROLES)), 'name' => 'required|string|max:80', 'organization' => 'required|string|max:80',
            'phone' => 'nullable|string|max:20', 'email' => 'required|email|unique:users,email', 'password' => 'required|min:6|confirmed',
        ]);
        Auth::login(User::create($v));
        $r->session()->regenerate();

        return redirect($this->home())->with('ok', 'Akun berhasil dibuat. Selamat datang di FoodLoop!');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function home(): string
    {
        return auth()->user()->role === 'provider' ? route('status') : route('map');
    }
}
