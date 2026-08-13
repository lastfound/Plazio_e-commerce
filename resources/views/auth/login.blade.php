@extends('layouts.app')

@section('title', 'Masuk Akun - Plazio.id')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        
        <div class="text-center space-y-2">
            <h1 class="text-2xl font-extrabold text-slate-900">Masuk Akun Plazio</h1>
            <p class="text-xs text-slate-500">Gunakan akun demo di bawah untuk mencoba berbagai peran (Buyer, Seller, Admin).</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', 'buyer@gmail.com') }}" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" value="password" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors">
                Masuk
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center space-y-2 text-xs">
            <p class="font-semibold text-slate-700">Quick Demo Switcher:</p>
            <div class="flex justify-center gap-2">
                <a href="{{ route('auth.switch-role', 'buyer') }}" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-800 text-xs font-medium">Sebagai Buyer</a>
                <a href="{{ route('auth.switch-role', 'seller') }}" class="px-3 py-1 bg-emerald-100 hover:bg-emerald-200 rounded-lg text-emerald-900 text-xs font-medium">Sebagai Seller</a>
                <a href="{{ route('auth.switch-role', 'admin') }}" class="px-3 py-1 bg-purple-100 hover:bg-purple-200 rounded-lg text-purple-900 text-xs font-medium">Sebagai Admin</a>
            </div>
        </div>

    </div>
</div>
@endsection
