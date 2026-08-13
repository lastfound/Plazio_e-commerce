<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Plazio - Marketplace Alternative & Standalone Storefront')</title>
    <meta name="description" content="Platform Marketplace Hybrid dengan Komisi Rendah 3-5%, Bebas Biaya Layanan Tersembunyi, dan Tracking Link Iklan Mandiri untuk Seller UMKM Indonesia.">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">
    
    <!-- Top Announcement Bar -->
    <div class="bg-slate-900 text-slate-200 text-xs py-2 px-4">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-2">
                <span class="bg-emerald-500 text-slate-950 font-bold px-2 py-0.5 rounded-full text-[10px] uppercase tracking-wider">USP Platform</span>
                <span>Komisi Rendah (3-5%) • Estimasi Harga Transparan Sejak Halaman Produk • SLA Dispute Cepat</span>
            </div>
            <!-- Quick Role Switcher for Demo -->
            <div class="flex items-center gap-2 text-xs">
                <span class="text-slate-400">Mode Demo:</span>
                <a href="{{ route('auth.switch-role', 'buyer') }}" class="px-2 py-0.5 rounded text-xs {{ optional(Auth::user())->role === 'buyer' ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-800 hover:bg-slate-700 text-slate-300' }}">Buyer</a>
                <a href="{{ route('auth.switch-role', 'seller') }}" class="px-2 py-0.5 rounded text-xs {{ optional(Auth::user())->role === 'seller' ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-800 hover:bg-slate-700 text-slate-300' }}">Seller</a>
                <a href="{{ route('auth.switch-role', 'admin') }}" class="px-2 py-0.5 rounded text-xs {{ optional(Auth::user())->role === 'admin' ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-800 hover:bg-slate-700 text-slate-300' }}">Admin</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                
                <!-- Brand Logo -->
                <a href="{{ route('marketplace.index') }}" class="flex items-center gap-2.5 text-xl font-bold tracking-tight text-slate-900 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center flex-shrink-0 items-center justify-center text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    </div>
                    <span>Plazio<span class="text-emerald-600">.id</span></span>
                </a>

                <!-- Search Input with Auto-Relevance Focus -->
                <form action="{{ route('marketplace.index') }}" method="GET" class="flex-1 max-w-xl hidden md:block">
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk lokal, skincare, sneakers, atau nama toko..." class="w-full pl-10 pr-24 py-2 bg-slate-100 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3"></i>
                        <button type="submit" class="absolute right-1.5 top-1.5 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors">
                            Cari
                        </button>
                    </div>
                </form>

                <!-- Right Nav Links -->
                <div class="flex items-center gap-4">
                    
                    @if(Auth::check() && Auth::user()->isSeller())
                        <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            Dashboard Toko
                        </a>
                    @endif

                    @if(Auth::check() && Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-purple-700 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors">
                            <i data-lucide="shield-alert" class="w-4 h-4"></i>
                            Admin CS Panel
                        </a>
                    @endif

                    <!-- Buyer Orders Link -->
                    <a href="{{ route('buyer.orders') }}" class="text-xs font-medium text-slate-600 hover:text-slate-900 hidden sm:flex items-center gap-1">
                        <i data-lucide="package" class="w-4 h-4"></i>
                        Pesanan Saya
                    </a>

                    <!-- Shopping Cart Counter -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition-colors">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        @php $cartCount = count(session('cart', [])); @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-emerald-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Profile Dropdown -->
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-full hover:bg-slate-100 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-slate-800 text-white font-semibold text-xs flex items-center justify-center uppercase">
                                    {{ substr(Auth::user()->name, 0, 2) }}
                                </div>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-lg py-1 z-50">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-xs font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-slate-500 capitalize">Role: {{ Auth::user()->role }}</p>
                                </div>
                                <a href="{{ route('buyer.orders') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">Riwayat Pesanan</a>
                                @if(Auth::user()->isSeller())
                                    <a href="{{ route('seller.tracking-links') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">Tracking Link Ads</a>
                                @endif
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('auth.switch-role', 'buyer') }}" class="text-xs font-semibold px-3 py-1.5 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors">Masuk</a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- Global Alert / Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl flex items-center gap-2 shadow-sm">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-xl flex items-center gap-2 shadow-sm">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('active_tracking_code'))
            <div class="p-3 bg-blue-50 border border-blue-200 text-blue-800 text-xs rounded-xl flex items-center justify-between shadow-sm my-2">
                <div class="flex items-center gap-2">
                    <i data-lucide="link-2" class="w-4 h-4 text-blue-600"></i>
                    <span>Sesi Terhubung Iklan Penjual: Kode Tracking active <strong>[{{ session('active_tracking_code') }}]</strong>. Konversi akan otomatis tercatat untuk statistik seller.</span>
                </div>
                <span class="text-[10px] bg-blue-200 text-blue-900 font-bold px-2 py-0.5 rounded">Active Referral</span>
            </div>
        @endif
    </div>

    <!-- Main Dynamic Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-xs py-12 mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2 text-white text-lg font-bold mb-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    </div>
                    <span>Plazio.id</span>
                </div>
                <p class="text-slate-400 leading-relaxed text-xs">
                    Platform Marketplace Hybrid Indonesia. Komisi adil (3-5%), transparansi total biaya sejak di halaman produk, dan fitur Tracking Link khusus seller.
                </p>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Fitur Unggulan</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-white transition-colors">Tracking Link Toko & Produk (USP)</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">One-Click Checkout Transparan</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Sistem Escrow & Instant Payout</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Eskalasi CS Human SLA</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Untuk Penjual</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('seller.dashboard') }}" class="hover:text-white transition-colors">Dashboard Seller</a></li>
                    <li><a href="{{ route('seller.tracking-links') }}" class="hover:text-white transition-colors">Buat Tracking Link Meta/TikTok</a></li>
                    <li><a href="{{ route('seller.payouts') }}" class="hover:text-white transition-colors">Pencairan Dana Cepat</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Badge UMKM & Verified Store</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Transparansi Layanan</h4>
                <p class="text-xs text-slate-400 leading-relaxed mb-3">
                    Plazio menjamin tidak ada biaya tersembunyi. Semua estimasi biaya ongkir & layanan 2rb ditampilkan sejak awal.
                </p>
                <div class="flex gap-2">
                    <span class="px-2.5 py-1 bg-slate-800 text-emerald-400 border border-slate-700 rounded text-[11px] font-mono">Commission: 3.5%</span>
                    <span class="px-2.5 py-1 bg-slate-800 text-blue-400 border border-slate-700 rounded text-[11px] font-mono">Service Fee: Rp 2.000</span>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pt-6 border-t border-slate-800 text-center text-slate-500">
            <p>&copy; 2026 Plazio.id — Platform E-Commerce & Storefront Mandiri Terpercaya.</p>
        </div>
    </footer>

</body>
</html>
