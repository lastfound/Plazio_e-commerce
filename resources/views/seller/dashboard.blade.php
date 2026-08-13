@extends('layouts.app')

@section('title', 'Dashboard Toko - ' . $store->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{ copied: false }">

    <!-- Seller Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <img src="{{ $store->logo }}" alt="{{ $store->name }}" class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-sm flex-shrink-0">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-extrabold text-slate-900">{{ $store->name }}</h1>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 uppercase">Tier: {{ $store->subscription_tier }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">{{ $store->tagline ?: 'Storefront Mandiri Plazio' }} • {{ $store->city }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('seller.products') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors flex items-center gap-1.5 shadow-md shadow-emerald-600/20">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                Tambah Barang Baru
            </a>
            <a href="{{ route('marketplace.store', $store->slug) }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded-xl transition-colors flex items-center gap-1.5 border border-slate-200">
                <i data-lucide="external-link" class="w-4 h-4 text-slate-600"></i>
                Lihat Toko Mandiri
            </a>
        </div>
    </div>

    <!-- Storefront Link Sharing Card (Solves User Request #2) -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 text-white p-6 rounded-2xl border border-slate-800 shadow-md flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/20 text-emerald-300 rounded-full text-xs font-bold">
                <i data-lucide="share-2" class="w-4 h-4"></i> Laman Toko Mandiri Seller
            </div>
            <h2 class="text-lg font-extrabold">Link Toko Anda: <code class="text-emerald-400 font-mono text-sm px-2 py-0.5 bg-slate-950/60 rounded border border-emerald-500/30">{{ url('/toko/' . $store->slug) }}</code></h2>
            <p class="text-xs text-slate-300 max-w-2xl leading-relaxed">
                Pembeli yang membuka link ini akan masuk ke laman khusus yang hanya menampilkan produk-produk dari toko Anda. Bebas dipakai untuk WhatsApp, Instagram Bio, FB Ads, maupun TikTok.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" @click="navigator.clipboard.writeText('{{ url('/toko/' . $store->slug) }}'); copied = true; setTimeout(() => copied = false, 2000)" class="px-5 py-3 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="copy" class="w-4 h-4"></i>
                Salin Link Toko Saya
            </button>
            <a href="{{ route('seller.tracking-links') }}" class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs rounded-xl transition-all border border-white/20 flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="link-2" class="w-4 h-4"></i>
                Link Ads Meta/TikTok
            </a>
        </div>
    </div>

    <!-- Seller Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500 text-xs font-medium">
                <span>Total Omzet Toko</span>
                <i data-lucide="wallet" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <div class="text-2xl font-extrabold text-slate-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <p class="text-[11px] text-emerald-700 font-semibold flex items-center gap-1">
                <i data-lucide="percent" class="w-3 h-3"></i> Komisi platform hanya 3.5%
            </p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500 text-xs font-medium">
                <span>Saldo Payout Siap Cair</span>
                <i data-lucide="banknote" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <div class="text-2xl font-extrabold text-slate-900">Rp {{ number_format($store->balance, 0, ',', '.') }}</div>
            <a href="{{ route('seller.payouts') }}" class="text-[11px] text-emerald-600 font-bold hover:underline inline-block">
                Cairkan Dana Instant &rarr;
            </a>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500 text-xs font-medium">
                <span>Total Klik Link Iklan</span>
                <i data-lucide="mouse-pointer" class="w-4 h-4 text-blue-600"></i>
            </div>
            <div class="text-2xl font-extrabold text-slate-900">{{ number_format($totalClicks) }}</div>
            <p class="text-[11px] text-slate-500">Dari kampanye Meta, TikTok & Google</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500 text-xs font-medium">
                <span>Conversion Rate Iklan</span>
                <i data-lucide="trending-up" class="w-4 h-4 text-purple-600"></i>
            </div>
            <div class="text-2xl font-extrabold text-purple-900">{{ $overallConversionRate }}%</div>
            <p class="text-[11px] text-purple-700 font-semibold">{{ $totalConversions }} transaksi berhasil</p>
        </div>

    </div>

    <!-- Recent Added Products Showcase (Solves User Request #1) -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                    <i data-lucide="box" class="w-4 h-4 text-emerald-600"></i>
                    Produk Terbaru Ditambahkan (Tayang di Homepage)
                </h3>
                <p class="text-xs text-slate-500">Produk yang Anda tambahkan di sini otomatis langsung muncul di Katalog Utama Platform.</p>
            </div>
            <a href="{{ route('seller.products') }}" class="text-xs font-bold text-emerald-600 hover:underline">Kelola Semua Produk &rarr;</a>
        </div>

        @if($recentProducts->isEmpty())
            <div class="text-center py-6 text-xs text-slate-500">
                Belum ada produk. Klik "Tambah Barang Baru" di atas untuk menambah produk.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($recentProducts as $p)
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-2 flex flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $p->image }}" alt="{{ $p->name }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                            <div class="min-w-0">
                                <h4 class="font-bold text-xs text-slate-900 truncate">{{ $p->name }}</h4>
                                <p class="text-[11px] font-extrabold text-emerald-700">Rp {{ number_format($p->effective_price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[10px] pt-2 border-t border-slate-200/80">
                            <span class="text-emerald-700 font-semibold flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> Tampil di Homepage
                            </span>
                            <a href="{{ route('marketplace.product', $p->slug) }}" target="_blank" class="text-slate-600 hover:text-emerald-600 font-bold">
                                Preview &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Active Tracking Links USP Box -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                    <i data-lucide="link-2" class="w-4 h-4 text-emerald-600"></i>
                    Tracking Link Iklan Teraktif
                </h3>
                <p class="text-xs text-slate-500">Hasil pelacakan iklan mandiri tanpa integrasi API iklan eksternal yang rumit.</p>
            </div>
            <a href="{{ route('seller.tracking-links') }}" class="text-xs font-bold text-emerald-600 hover:underline">Kelola Semua Link &rarr;</a>
        </div>

        @if($trackingLinks->isEmpty())
            <div class="text-center py-6 text-xs text-slate-500">
                Belum ada link iklan yang dibuat.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="p-3">Nama Kampanye Iklan</th>
                            <th class="p-3">Saluran (Channel)</th>
                            <th class="p-3">Jumlah Klik</th>
                            <th class="p-3">Konversi Transaksi</th>
                            <th class="p-3">Conv. Rate</th>
                            <th class="p-3">Omzet Iklan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800 font-medium">
                        @foreach($trackingLinks as $link)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-bold text-slate-900">{{ $link->name }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-800 uppercase">{{ $link->channel }}</span>
                                </td>
                                <td class="p-3 font-mono">{{ number_format($link->clicks_count) }}</td>
                                <td class="p-3 font-mono text-emerald-700 font-bold">{{ number_format($link->conversions_count) }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">{{ $link->conversion_rate }}%</span>
                                </td>
                                <td class="p-3 font-bold text-slate-900">Rp {{ number_format($link->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Toast Copy Notification -->
    <div x-show="copied" x-transition class="fixed bottom-6 right-6 bg-slate-900 text-white px-4 py-2.5 rounded-xl shadow-xl text-xs font-semibold z-50 flex items-center gap-2 border border-slate-700">
        <i data-lucide="check" class="w-4 h-4 text-emerald-400"></i>
        <span>Link Laman Toko Mandiri berhasil disalin ke clipboard!</span>
    </div>

</div>
@endsection
