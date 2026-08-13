@extends('layouts.app')

@section('title', $product->name . ' - Plazio.id')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Breadcrumb Nav -->
    <nav class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('marketplace.index') }}" class="hover:text-slate-900">Home</a>
        <span>/</span>
        <a href="{{ route('marketplace.index', ['category' => $product->category->slug]) }}" class="hover:text-slate-900">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-slate-900 font-medium truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <!-- Product Details Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

        <!-- Product Image Gallery (Left 5 Cols) -->
        <div class="lg:col-span-5 space-y-4 sticky top-24">
            <div class="aspect-square rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-sm relative">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @if($product->is_local_umkm)
                    <span class="absolute top-4 left-4 px-3 py-1 bg-emerald-600 text-white font-bold text-xs rounded-lg shadow-md flex items-center gap-1.5">
                        <i data-lucide="flag" class="w-3.5 h-3.5"></i>
                        Brand & UMKM Lokal
                    </span>
                @endif
            </div>
            
            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl flex items-center gap-3">
                <i data-lucide="shield-check" class="w-6 h-6 text-emerald-600 flex-shrink-0"></i>
                <div class="text-xs text-emerald-900">
                    <p class="font-bold">Transaksi Dilindungi Escrow Plazio</p>
                    <p class="text-[11px] text-emerald-700">Dana pembayaran ditahan aman oleh platform dan baru diteruskan ke seller setelah Anda mengonfirmasi pesanan diterima.</p>
                </div>
            </div>
        </div>

        <!-- Product Details & Specs (Middle 4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <div>
                <!-- Store Badge -->
                <a href="{{ route('marketplace.store', $product->store->slug) }}" class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 hover:border-emerald-500 transition-colors mb-3">
                    <i data-lucide="store" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>{{ $product->store->name }}</span>
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-blue-500"></i>
                </a>

                <h1 class="text-2xl font-extrabold text-slate-900 leading-tight mb-2">{{ $product->name }}</h1>

                <!-- Rating & Sales -->
                <div class="flex items-center gap-3 text-xs">
                    <div class="flex items-center text-amber-500 font-bold gap-1">
                        <i data-lucide="star" class="w-4 h-4 fill-amber-400 text-amber-400"></i>
                        <span class="text-sm">{{ number_format($product->rating, 1) }}</span>
                    </div>
                    <span class="text-slate-300">|</span>
                    <span class="text-slate-600 font-medium">{{ $product->reviews_count }} Ulasan Ulasan Verified</span>
                    <span class="text-slate-300">|</span>
                    <span class="text-slate-600 font-medium">Terjual {{ $product->sales_count }}</span>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-2 border-t border-slate-200 pt-4">
                <h3 class="font-bold text-sm text-slate-900">Deskripsi Produk</h3>
                <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
            </div>

            <!-- Specifications Table -->
            @if(!empty($product->specs))
                <div class="space-y-2 border-t border-slate-200 pt-4">
                    <h3 class="font-bold text-sm text-slate-900">Spesifikasi & Informasi Detail</h3>
                    <div class="bg-slate-50 rounded-xl border border-slate-200 p-3 divide-y divide-slate-200/80">
                        @foreach($product->specs as $key => $val)
                            <div class="flex justify-between py-1.5 text-xs">
                                <span class="text-slate-500 font-medium">{{ $key }}</span>
                                <span class="text-slate-900 font-semibold">{{ $val }}</span>
                            </div>
                        @endforeach
                        <div class="flex justify-between py-1.5 text-xs">
                            <span class="text-slate-500 font-medium">Berat Pengiriman</span>
                            <span class="text-slate-900 font-semibold">{{ $product->weight_grams }} gram</span>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- Right Side: Transparent Price & Checkout Box (3 Cols) -->
        <div class="lg:col-span-3 bg-white p-6 rounded-2xl border-2 border-emerald-500/30 shadow-md space-y-6 sticky top-24">
            
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Transparansi Harga Total</span>
                
                <div class="pt-1">
                    @if($product->discount_price)
                        <div class="text-xs text-slate-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <div class="text-2xl font-extrabold text-slate-900">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                    @else
                        <div class="text-2xl font-extrabold text-slate-900">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    @endif
                </div>
            </div>

            <!-- Fee Breakdown List (Solves MD Pain Point: Biaya Tersembunyi) -->
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-2 text-xs">
                <div class="font-bold text-slate-900 text-[11px] border-b border-slate-200 pb-1.5 flex items-center justify-between">
                    <span>Rincian Estimasi Total</span>
                    <span class="text-[10px] text-emerald-600 bg-emerald-100 px-1.5 py-0.5 rounded">Bebas Pop-up Fee</span>
                </div>
                <div class="flex justify-between text-slate-600 text-[11px]">
                    <span>Harga Barang:</span>
                    <span>Rp {{ number_format($product->effective_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-600 text-[11px]">
                    <span>Estimasi Ongkir (Reguler):</span>
                    <span>Rp 15.000</span>
                </div>
                <div class="flex justify-between text-slate-600 text-[11px]">
                    <span>Biaya Layanan Transparan:</span>
                    <span>Rp 2.000</span>
                </div>
                <div class="flex justify-between font-bold text-emerald-900 text-xs pt-1.5 border-t border-slate-200">
                    <span>Total Pembayaran Akhir:</span>
                    <span>Rp {{ number_format($estimatedTotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Form Add to Cart & Buy -->
            <form action="{{ route('cart.add') }}" method="POST" class="space-y-3" x-data="{ qty: 1 }">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="flex items-center justify-between text-xs">
                    <span class="font-medium text-slate-700">Jumlah Pasang/Unit:</span>
                    <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                        <button type="button" @click="if(qty > 1) qty--" class="px-2.5 py-1 text-slate-600 hover:bg-slate-200">-</button>
                        <input type="number" name="quantity" x-model="qty" readonly class="w-10 text-center text-xs font-bold bg-transparent border-none focus:outline-none">
                        <button type="button" @click="qty++" class="px-2.5 py-1 text-slate-600 hover:bg-slate-200">+</button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    Beli & Checkout Sekarang
                </button>
            </form>

            <!-- Store Information Mini Card -->
            <div class="pt-4 border-t border-slate-200 space-y-3">
                <div class="flex items-center gap-3">
                    <img src="{{ $product->store->logo }}" alt="{{ $product->store->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200">
                    <div>
                        <a href="{{ route('marketplace.store', $product->store->slug) }}" class="font-bold text-xs text-slate-900 hover:text-emerald-600 flex items-center gap-1">
                            {{ $product->store->name }}
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-blue-500"></i>
                        </a>
                        <p class="text-[11px] text-slate-500">{{ $product->store->city }} • Rating {{ $product->store->rating }}</p>
                    </div>
                </div>
                <a href="{{ route('marketplace.store', $product->store->slug) }}" class="block text-center py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded-xl transition-colors">
                    Kunjungi Toko Mandiri Seller
                </a>
            </div>

        </div>

    </div>

    <!-- Verified Purchase Reviews Section -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 space-y-6">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-5 h-5 text-emerald-600"></i>
                    Ulasan Pembeli Terverifikasi (Verified Purchase Only)
                </h3>
                <p class="text-xs text-slate-500">Mencegah ulasan palsu atau manipulatif — hanya pembeli yang telah menyelesaikan pesanan yang berhak memberikan ulasan.</p>
            </div>
            <div class="text-right">
                <span class="text-2xl font-extrabold text-amber-500">{{ number_format($product->rating, 1) }}</span>
                <span class="text-xs text-slate-400">/ 5.0</span>
            </div>
        </div>

        @if($product->reviews->isEmpty())
            <div class="text-center py-8 text-slate-500 text-xs">
                Belum ada ulasan untuk produk ini. Pembeli pertama yang menyelesaikan transaksi akan menjadi Verified Reviewer pertama!
            </div>
        @else
            <div class="space-y-4 divide-y divide-slate-100">
                @foreach($product->reviews as $review)
                    <div class="pt-4 first:pt-0 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-slate-800 text-white font-bold text-[10px] flex items-center justify-center uppercase">
                                    {{ substr($review->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-900">{{ $review->user->name }}</span>
                                    @if($review->is_verified_purchase)
                                        <span class="ml-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i data-lucide="check" class="w-3 h-3"></i> Verified Purchase
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-[11px] text-slate-400">{{ $review->created_at->format('d M Y') }}</span>
                        </div>

                        <div class="flex items-center text-amber-400 gap-0.5">
                            @for($i=1; $i<=5; $i++)
                                <i data-lucide="star" class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-amber-400 text-amber-400' : 'text-slate-200' }}"></i>
                            @endfor
                        </div>

                        <p class="text-xs text-slate-700 leading-relaxed">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</div>
@endsection
