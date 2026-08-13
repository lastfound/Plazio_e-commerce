<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isSeller()) {
                return redirect()->route('seller.dashboard');
            }
            return redirect()->route('marketplace.index');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    public function switchRole($role)
    {
        $user = User::where('role', $role)->first();
        if ($user) {
            Auth::login($user);
            if ($role === 'seller') {
                return redirect()->route('seller.dashboard')->with('success', 'Beralih ke mode Seller (' . $user->name . ')');
            } elseif ($role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Beralih ke mode Admin Plazio');
            }
            return redirect()->route('marketplace.index')->with('success', 'Beralih ke mode Buyer (' . $user->name . ')');
        }
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('marketplace.index');
    }
}
