@extends('layouts.app')

@section('title', 'Generator Tracking Link Iklan - Plazio.id')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{ copied: false, modalOpen: false }">

    <!-- Header & Concept explanation -->
    <div class="bg-gradient-to-r from-slate-900 to-emerald-950 text-white p-6 rounded-2xl border border-slate-800 shadow-md flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/20 text-emerald-300 rounded-full text-xs font-bold">
                <i data-lucide="link" class="w-4 h-4"></i> Fitur Unggulan USP (Tracking Link Toko & Produk)
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight">Generator Link Toko Mandiri & Iklan</h1>
            <p class="text-xs text-slate-300 max-w-2xl leading-relaxed">
                Buat link unik dengan kode referral untuk dipasang di Meta Ads, TikTok Ads, atau Bio Sosmed Anda. Platform secara otomatis mencatat jumlah <strong>klik, konversi transaksi, dan omzet</strong> tanpa perlu integrasi API iklan eksternal.
            </p>
        </div>

        <button @click="modalOpen = true" class="px-5 py-3 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            Generate Link Iklan Baru
        </button>
    </div>

    <!-- Analytics Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-500">Total Klik Link Terlacak</span>
            <div class="text-2xl font-extrabold text-slate-900">{{ number_format($totalClicks) }} Klik</div>
            <p class="text-[11px] text-slate-400">Total kunjungan calon pembeli dari link iklan</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-500">Total Konversi Transaksi</span>
            <div class="text-2xl font-extrabold text-emerald-700">{{ number_format($totalConversions) }} Order</div>
            <p class="text-[11px] text-emerald-800 font-semibold">
                Conv Rate: {{ $totalClicks > 0 ? round(($totalConversions / $totalClicks) * 100, 2) : 0 }}%
            </p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-500">Total Omzet dari Iklan Mandiri</span>
            <div class="text-2xl font-extrabold text-purple-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <p class="text-[11px] text-purple-700 font-semibold">Pendapatan kotor kampanye iklan</p>
        </div>
    </div>

    <!-- Tracking Links List Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                <i data-lucide="list" class="w-4 h-4 text-emerald-600"></i>
                Daftar Link Tracking Iklan Aktif
            </h3>
            <span class="text-xs text-slate-500">{{ $links->count() }} Link Terdaftar</span>
        </div>

        @if($links->isEmpty())
            <div class="text-center py-10 space-y-3">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <i data-lucide="link-2-off" class="w-6 h-6"></i>
                </div>
                <p class="text-xs text-slate-500 font-medium">Belum ada link iklan. Klik tombol "Generate Link Iklan Baru" di atas untuk memulai.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="p-3">Nama Kampanye</th>
                            <th class="p-3">Channel / Platform</th>
                            <th class="p-3">Target</th>
                            <th class="p-3">URL Link Tracking (Salin & Pasang)</th>
                            <th class="p-3">Klik</th>
                            <th class="p-3">Konversi</th>
                            <th class="p-3">Conv Rate</th>
                            <th class="p-3">Total Omzet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @foreach($links as $link)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-3 font-bold text-slate-900">{{ $link->name }}</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase border
                                        {{ $link->channel === 'meta_ads' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                        {{ $link->channel === 'tiktok_ads' ? 'bg-slate-900 text-white border-slate-900' : '' }}
                                        {{ $link->channel === 'google_ads' ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                                        {{ !in_array($link->channel, ['meta_ads','tiktok_ads','google_ads']) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                    ">
                                        {{ str_replace('_', ' ', $link->channel) }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    @if($link->target_type === 'product' && $link->product)
                                        <span class="text-slate-600 truncate max-w-[120px] block" title="{{ $link->product->name }}">Produk: {{ $link->product->name }}</span>
                                    @else
                                        <span class="text-emerald-700 font-semibold">Storefront Toko</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <div class="flex items-center gap-2 max-w-xs">
                                        <input type="text" readonly value="{{ $link->full_url }}" class="w-full px-2 py-1 bg-slate-100 border border-slate-200 rounded text-[11px] font-mono text-slate-700 truncate">
                                        <button type="button" @click="navigator.clipboard.writeText('{{ $link->full_url }}'); copied = true; setTimeout(() => copied = false, 2000)" class="px-2 py-1 bg-slate-800 hover:bg-slate-900 text-white rounded text-[10px] font-semibold flex-shrink-0">
                                            Copy
                                        </button>
                                    </div>
                                </td>
                                <td class="p-3 font-mono font-semibold">{{ number_format($link->clicks_count) }}</td>
                                <td class="p-3 font-mono font-bold text-emerald-700">{{ number_format($link->conversions_count) }}</td>
                                <td class="p-3 font-bold">
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-purple-50 text-purple-700 border border-purple-200">{{ $link->conversion_rate }}%</span>
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
        <span>Link tracking berhasil disalin ke clipboard! Siap ditempel di Iklan.</span>
    </div>

    <!-- Modal Generate New Tracking Link -->
    <div x-show="modalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="modalOpen = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 border border-slate-200">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                    <i data-lucide="link-2" class="w-4 h-4 text-emerald-600"></i>
                    Buat Link Tracking Iklan Baru
                </h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('seller.tracking-links.store') }}" method="POST" class="space-y-4" x-data="{ target: 'store' }">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Kampanye / Iklan</label>
                    <input type="text" name="name" placeholder="Misal: Iklan FB Sepatu Promo 8.8" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Saluran Iklan / Platform (Channel)</label>
                    <select name="channel" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="meta_ads">Meta Ads (Facebook & Instagram)</option>
                        <option value="tiktok_ads">TikTok Ads / TikTok Bio Link</option>
                        <option value="google_ads">Google Search / Display Ads</option>
                        <option value="instagram">Instagram Bio / Story Link</option>
                        <option value="other">Saluran Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Target Halaman Landing</label>
                    <select name="target_type" x-model="target" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="store">Storefront Utama Toko (/toko/{{ $store->slug }})</option>
                        <option value="product">Halaman Produk Spesifik (/p/...) </option>
                    </select>
                </div>

                <div x-show="target === 'product'">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Produk Target</label>
                    <select name="product_id" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} - Rp {{ number_format($p->effective_price, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" @click="modalOpen = false" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md">Generate Link</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
