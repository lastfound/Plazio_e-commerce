@extends('layouts.app')

@section('title', 'Plazio.id - Marketplace Multi-Seller & Toko Mandiri Lokal')

@section('content')

<!-- Hero Banner with USP Highlights -->
<div class="bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-950 text-white py-12 px-4 sm:px-6 lg:px-8 border-b border-slate-800 relative overflow-hidden">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
        
        <div class="lg:col-span-7 space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-full text-xs font-semibold">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>Solusi Bebas Biaya Layanan Melambung 21%</span>
            </div>
            
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                Belanja Produk Lokal <span class="text-emerald-400">Tanpa Biaya Tersembunyi</span>
            </h1>
            
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl">
                Plazio adalah marketplace hybrid alternatif yang adil bagi seller UMKM dengan komisi rendah (3-5%), dan memberi pembeli kepastian harga total (termasuk estimasi ongkir & fee) langsung dari halaman produk.
            </p>

            <div class="pt-2 flex flex-wrap gap-4 text-xs font-medium text-slate-300">
                <div class="flex items-center gap-2 bg-slate-800/80 px-3.5 py-2 rounded-xl border border-slate-700">
                    <i data-lucide="calculator" class="w-4 h-4 text-emerald-400"></i>
                    <span>Harga Total Transparan</span>
                </div>
                <div class="flex items-center gap-2 bg-slate-800/80 px-3.5 py-2 rounded-xl border border-slate-700">
                    <i data-lucide="award" class="w-4 h-4 text-emerald-400"></i>
                    <span>Verified Purchase Only</span>
                </div>
                <div class="flex items-center gap-2 bg-slate-800/80 px-3.5 py-2 rounded-xl border border-slate-700">
                    <i data-lucide="link" class="w-4 h-4 text-emerald-400"></i>
                    <span>Store Tracking Link Ready</span>
                </div>
            </div>
        </div>

        <!-- Highlight Card for Seller / Featured Store -->
        <div class="lg:col-span-5 bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-6 text-white space-y-4">
            <div class="flex justify-between items-center border-b border-white/10 pb-3">
                <span class="text-xs font-semibold text-emerald-400 uppercase tracking-wider">Storefront Mandiri Seller</span>
                <span class="text-[11px] bg-emerald-500/20 text-emerald-300 px-2 py-0.5 rounded">Hybrid Mode</span>
            </div>
            
            <div class="space-y-2">
                <h3 class="font-bold text-lg">Punya Brand Sendiri?</h3>
                <p class="text-xs text-slate-300">
                    Dapatkan halaman toko pribadi (<code class="bg-slate-900/60 px-1.5 py-0.5 rounded text-emerald-300 font-mono text-[11px]">plazio.id/toko/namatoko</code>) lengkap dengan generator link tracking untuk iklan Meta, TikTok & Google Anda.
                </p>
            </div>

            <div class="pt-2 flex gap-2">
                <a href="{{ route('seller.tracking-links') }}" class="flex-1 text-center py-2.5 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-xs rounded-xl transition-all shadow-lg shadow-emerald-500/20">
                    Coba Generator Tracking Link
                </a>
                <a href="{{ route('marketplace.store', 'truetoskin') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-medium text-xs rounded-xl transition-all border border-white/20">
                    Lihat Demo Toko
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Main Catalog Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Categories Pills Bar -->
    <div class="flex items-center gap-2 overflow-x-auto pb-4 scrollbar-none mb-8">
        <a href="{{ route('marketplace.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors border {{ !request('category') ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
            Semua Kategori
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('marketplace.index', array_merge(request()->query(), ['category' => $cat->slug])) }}" class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors border flex items-center gap-2 {{ request('category') === $cat->slug ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                <i data-lucide="{{ $cat->icon ?: 'tag' }}" class="w-3.5 h-3.5"></i>
                <span>{{ $cat->name }}</span>
                <span class="px-1.5 py-0.2 text-[10px] rounded-full {{ request('category') === $cat->slug ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $cat->products_count }}</span>
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Filter Sidebar -->
        <div class="lg:col-span-3 space-y-6">
            <form action="{{ route('marketplace.index') }}" method="GET" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-5">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                        <i data-lucide="sliders-horizontal" class="w-4 h-4 text-emerald-600"></i>
                        Filter Relevan Organik
                    </h3>
                    <a href="{{ route('marketplace.index') }}" class="text-[11px] text-slate-500 hover:text-rose-600">Reset</a>
                </div>

                <!-- Local UMKM Toggle -->
                <div class="bg-emerald-50/70 border border-emerald-100 p-3 rounded-xl space-y-1">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="local_umkm" value="1" {{ request('local_umkm') === '1' ? 'checked' : '' }} onchange="this.form.submit()" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <span class="text-xs font-bold text-emerald-950 flex items-center gap-1">
                            <i data-lucide="store" class="w-3.5 h-3.5 text-emerald-600"></i>
                            Produk UMKM & Brand Lokal
                        </span>
                    </label>
                    <p class="text-[11px] text-emerald-800 pl-6 leading-tight">Utamakan produk buatan produsen lokal tanah air.</p>
                </div>

                <!-- Price Filter -->
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-slate-700">Rentang Harga (Rp)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <!-- City / Location Filter -->
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-slate-700">Lokasi Penjual</label>
                    <select name="city" onchange="this.form.submit()" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="">Semua Lokasi</option>
                        <option value="Bandung" {{ request('city') === 'Bandung' ? 'selected' : '' }}>Bandung</option>
                        <option value="Klaten" {{ request('city') === 'Klaten' ? 'selected' : '' }}>Klaten</option>
                        <option value="Malang" {{ request('city') === 'Malang' ? 'selected' : '' }}>Malang</option>
                        <option value="Jakarta" {{ request('city') === 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                    </select>
                </div>

                <!-- Sorting -->
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-slate-700">Urutkan</label>
                    <select name="sort" onchange="this.form.submit()" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="top_sales" {{ request('sort') === 'top_sales' ? 'selected' : '' }}>Penjualan Terbanyak</option>
                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-colors">
                    Terapkan Filter
                </button>
            </form>

            <!-- Featured Verified Stores Card -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="badge-check" class="w-4 h-4 text-emerald-600"></i>
                    Toko Terverifikasi
                </h4>
                <div class="space-y-3">
                    @foreach($featuredStores as $store)
                        <a href="{{ route('marketplace.store', $store->slug) }}" class="flex items-center gap-3 group p-2 rounded-xl hover:bg-slate-50 transition-colors border border-slate-100">
                            <img src="{{ $store->logo }}" alt="{{ $store->name }}" class="w-9 h-9 rounded-lg object-cover border border-slate-200 flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-900 truncate group-hover:text-emerald-600 transition-colors flex items-center gap-1">
                                    {{ $store->name }}
                                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-blue-500 flex-shrink-0"></i>
                                </p>
                                <p class="text-[10px] text-slate-500">{{ $store->city }} • {{ $store->products_count }} Produk</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right Product Grid -->
        <div class="lg:col-span-9 space-y-6">
            
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Katalog Produk Organik</h2>
                    <p class="text-xs text-slate-500">Menampilkan {{ $products->total() }} produk tanpa manipulasi iklan berbayar sponsor</p>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 space-y-3">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                        <i data-lucide="search-x" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-bold text-slate-800">Tidak ada produk ditemukan</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">Coba ubah kata kunci atau hapus beberapa filter untuk menemukan produk lokal lainnya.</p>
                    <a href="{{ route('marketplace.index') }}" class="inline-block px-4 py-2 bg-emerald-600 text-white text-xs font-semibold rounded-xl">Lihat Semua Produk</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl border border-slate-200/90 hover:border-emerald-500/50 shadow-sm hover:shadow-md transition-all group flex flex-col overflow-hidden relative">
                            
                            <!-- Badges Top Right & Left -->
                            <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                                @if($product->is_local_umkm)
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold umkm-badge flex items-center gap-1 shadow-sm">
                                        <i data-lucide="flag" class="w-3 h-3"></i>
                                        Brand Lokal
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('marketplace.product', $product->slug) }}" class="block aspect-square relative overflow-hidden bg-slate-100">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </a>

                            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                                
                                <div>
                                    <div class="flex items-center gap-1 text-[11px] text-slate-500 mb-1">
                                        <a href="{{ route('marketplace.store', $product->store->slug) }}" class="font-semibold text-slate-700 hover:text-emerald-600 truncate flex items-center gap-1">
                                            {{ $product->store->name }}
                                            <i data-lucide="check-circle-2" class="w-3 h-3 text-blue-500 flex-shrink-0"></i>
                                        </a>
                                        <span>•</span>
                                        <span>{{ $product->store->city }}</span>
                                    </div>

                                    <a href="{{ route('marketplace.product', $product->slug) }}" class="font-bold text-sm text-slate-900 group-hover:text-emerald-600 transition-colors line-clamp-2 leading-snug">
                                        {{ $product->name }}
                                    </a>
                                </div>

                                <!-- Rating & Sales -->
                                <div class="flex items-center gap-2 text-xs">
                                    <div class="flex items-center text-amber-500 font-bold gap-1">
                                        <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                                        <span>{{ number_format($product->rating, 1) }}</span>
                                    </div>
                                    <span class="text-slate-300">|</span>
                                    <span class="text-slate-500 text-[11px]">Terjual {{ $product->sales_count }}</span>
                                    <span class="text-slate-300">|</span>
                                    <span class="text-emerald-700 text-[10px] bg-emerald-50 px-1.5 py-0.5 rounded font-medium">Verified</span>
                                </div>

                                <!-- Transparent Price Card (USP Solution for Hidden Fees) -->
                                <div class="bg-slate-50 border border-slate-200/80 p-2.5 rounded-xl space-y-1.5">
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-slate-500 text-[10px]">Harga Produk:</span>
                                        <div class="text-right">
                                            @if($product->discount_price)
                                                <span class="text-[10px] text-slate-400 line-through mr-1">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                                <span class="text-sm font-extrabold text-slate-900">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-sm font-extrabold text-slate-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Transparent Total Fee Estimate -->
                                    <div class="flex items-center justify-between text-[11px] pt-1 border-t border-slate-200/60 text-emerald-800 font-semibold">
                                        <span class="flex items-center gap-1 text-[10px]">
                                            <i data-lucide="info" class="w-3 h-3 text-emerald-600"></i>
                                            Estimasi Total Akhir:
                                        </span>
                                        <span>Rp {{ number_format($product->effective_price + 15000 + 2000, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 pt-1">
                                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                                            <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                                            Beli Langsung
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-6">
                    {{ $products->links() }}
                </div>
            @endif

        </div>

    </div>

</div>

@endsection
