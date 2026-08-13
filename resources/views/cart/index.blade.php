@extends('layouts.app')

@section('title', 'Keranjang Belanja - Plazio.id')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2">
            <i data-lucide="shopping-cart" class="w-6 h-6 text-emerald-600"></i>
            Keranjang Belanja
        </h1>
        <a href="{{ route('marketplace.index') }}" class="text-xs text-emerald-600 hover:underline flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Lanjut Belanja
        </a>
    </div>

    @if(empty($cart))
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 space-y-4">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                <i data-lucide="shopping-cart-x" class="w-8 h-8"></i>
            </div>
            <h2 class="text-lg font-bold text-slate-800">Keranjang Belanja Masih Kosong</h2>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Jelajahi berbagai produk lokal berkualitas dan tambahkan ke keranjang Anda.</p>
            <a href="{{ route('marketplace.index') }}" class="inline-block px-6 py-2.5 bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20">
                Lihat Katalog Produk
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Items List (8 Cols) -->
            <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-sm">
                <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3">Daftar Produk ({{ count($cart) }} Item)</h3>

                <div class="divide-y divide-slate-100">
                    @foreach($cart as $id => $item)
                        <div class="py-4 first:pt-0 flex items-center gap-4">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                            
                            <div class="flex-1 min-w-0">
                                <span class="text-[10px] font-semibold text-slate-500">{{ $item['store_name'] }}</span>
                                <h4 class="font-bold text-xs text-slate-900 truncate">{{ $item['name'] }}</h4>
                                <p class="text-xs font-extrabold text-emerald-700 mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>

                            <!-- Qty & Remove -->
                            <div class="flex items-center gap-3">
                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $id }}">
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" onchange="this.form.submit()" class="w-12 text-center text-xs font-bold bg-transparent py-1 border-none focus:outline-none">
                                </form>

                                <a href="{{ route('cart.remove', $id) }}" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Transparent Price Summary (4 Cols) -->
            <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3">Ringkasan Biaya Transparan</h3>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Total Harga Barang:</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Estimasi Ongkir (Flat):</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($shippingFee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Layanan Plazio:</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($platformFee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-extrabold text-sm text-emerald-950 pt-3 border-t border-slate-200">
                        <span>Total Akhir:</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}" class="block w-full text-center py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-600/20 transition-colors">
                    Lanjut Ke Checkout (Direct)
                </a>
            </div>

        </div>
    @endif

</div>
@endsection
