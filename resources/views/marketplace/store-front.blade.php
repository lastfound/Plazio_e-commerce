@extends('layouts.app')

@section('title', $store->name . ' - Storefront Mandiri Plazio')

@section('content')

<!-- Store Banner Hero -->
<div class="relative bg-slate-900 text-white min-h-[220px] flex items-end">
    <img src="{{ $store->banner }}" alt="{{ $store->name }} Banner" class="absolute inset-0 w-full h-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/50 to-transparent"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10 w-full flex flex-col md:flex-row md:items-end justify-between gap-6">
        
        <div class="flex items-center gap-4">
            <img src="{{ $store->logo }}" alt="{{ $store->name }} Logo" class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-xl flex-shrink-0">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-extrabold tracking-tight text-white">{{ $store->name }}</h1>
                    @if($store->is_verified)
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-blue-400" title="Toko Terverifikasi"></i>
                    @endif
                    @if($store->is_local_umkm)
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold umkm-badge">UMKM Lokal</span>
                    @endif
                </div>
                <p class="text-xs text-slate-300 font-medium mt-1">{{ $store->tagline }}</p>
                <div class="flex items-center gap-4 text-xs text-slate-400 mt-2">
                    <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-400"></i> {{ $store->city }}</span>
                    <span class="flex items-center gap-1"><i data-lucide="star" class="w-3.5 h-3.5 text-amber-400 fill-amber-400"></i> {{ number_format($store->rating, 2) }} / 5.0</span>
                    <span class="flex items-center gap-1"><i data-lucide="box" class="w-3.5 h-3.5 text-blue-400"></i> {{ $store->products->count() }} Produk</span>
                </div>
            </div>
        </div>

        <div class="bg-white/10 backdrop-blur-md border border-white/20 p-3 rounded-xl text-xs space-y-1 max-w-sm">
            <span class="font-bold text-emerald-400 flex items-center gap-1">
                <i data-lucide="store" class="w-4 h-4"></i> Standalone Storefront Tool
            </span>
            <p class="text-[11px] text-slate-200">
                Toko ini dapat diakses langsung oleh pembeli dari Iklan Meta/TikTok seller tanpa riset algoritma marketplace mengunci data pelanggan.
            </p>
        </div>

    </div>
</div>

<!-- Store Products Catalog -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Katalog Produk {{ $store->name }}</h2>
            <p class="text-xs text-slate-500">Semua produk resmi dikirim langsung dari {{ $store->city }}</p>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
            <p class="text-slate-500 text-sm">Toko ini belum menambahkan produk.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all group flex flex-col overflow-hidden">
                    <a href="{{ route('marketplace.product', $product->slug) }}" class="block aspect-square relative overflow-hidden bg-slate-100">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </a>

                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                        <div>
                            <span class="text-[10px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded">{{ $product->category->name }}</span>
                            <a href="{{ route('marketplace.product', $product->slug) }}" class="font-bold text-sm text-slate-900 group-hover:text-emerald-600 transition-colors line-clamp-2 mt-1">
                                {{ $product->name }}
                            </a>
                        </div>

                        <!-- Price & Transparent Total -->
                        <div class="space-y-1">
                            <div class="text-base font-extrabold text-slate-900">
                                @if($product->discount_price)
                                    <span class="text-xs text-slate-400 line-through mr-1">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span>Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                                @else
                                    <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <div class="text-[10px] text-slate-500 flex items-center gap-1">
                                <i data-lucide="info" class="w-3 h-3 text-emerald-600"></i>
                                Estimasi Checkout: Rp {{ number_format($product->effective_price + 17000, 0, ',', '.') }}
                            </div>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1">
                                <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                                Beli Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-6">
            {{ $products->links() }}
        </div>
    @endif

</div>

@endsection
