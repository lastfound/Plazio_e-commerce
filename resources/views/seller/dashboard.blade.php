@extends('layouts.app')

@section('title', 'Dashboard Toko - ' . $store->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

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
            <a href="{{ route('marketplace.store', $store->slug) }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded-xl transition-colors flex items-center gap-1.5 border border-slate-200">
                <i data-lucide="external-link" class="w-4 h-4 text-slate-600"></i>
                Lihat Storefront Mandiri
            </a>
            <a href="{{ route('seller.tracking-links') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors flex items-center gap-1.5 shadow-md shadow-emerald-600/20">
                <i data-lucide="link" class="w-4 h-4"></i>
                Generator Tracking Link Ads
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

    <!-- Active Tracking Links USP Box -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                    <i data-lucide="link-2" class="w-4 h-4 text-emerald-600"></i>
                    Tracking Link Iklan Teraktif (USP Feature)
                </h3>
                <p class="text-xs text-slate-500">Hasil pelacakan iklan mandiri tanpa perlu integrasi OAuth API eksternal yang rumit.</p>
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

    <!-- Recent Orders Box -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                <i data-lucide="package" class="w-4 h-4 text-emerald-600"></i>
                Pesanan Terbaru Masuk
            </h3>
            <a href="{{ route('seller.orders') }}" class="text-xs font-bold text-emerald-600 hover:underline">Lihat Semua Pesanan &rarr;</a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="text-center py-6 text-xs text-slate-500">
                Belum ada pesanan masuk.
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($recentOrders as $order)
                    <div class="py-3 flex flex-wrap items-center justify-between gap-3 text-xs">
                        <div>
                            <span class="font-mono font-bold text-slate-900">{{ $order->order_number }}</span>
                            <span class="text-slate-400 mx-1">•</span>
                            <span class="text-slate-600">{{ $order->buyer->name }}</span>
                            <span class="text-slate-400 mx-1">•</span>
                            <span class="text-slate-500">{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-slate-900">Rp {{ number_format($order->total_product_amount, 0, ',', '.') }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
