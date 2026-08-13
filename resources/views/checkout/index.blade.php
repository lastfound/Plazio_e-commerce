@extends('layouts.app')

@section('title', 'Checkout Transparan - Plazio.id')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2">
            <i data-lucide="shield-check" class="w-6 h-6 text-emerald-600"></i>
            One-Click Checkout Transparan
        </h1>
        <p class="text-xs text-slate-500">Bebas biaya tersembunyi — harga total final telah dihitung sejak di halaman produk.</p>
    </div>

    @if($activeTrackingLink)
        <div class="bg-blue-50 border border-blue-200 p-3 rounded-xl flex items-center gap-3 text-xs text-blue-900">
            <i data-lucide="link" class="w-5 h-5 text-blue-600 flex-shrink-0"></i>
            <div>
                <p class="font-bold">Terhubung Kode Referral Iklan Seller: [{{ $activeTrackingLink->code }}]</p>
                <p class="text-[11px] text-blue-700">Kampanye: {{ $activeTrackingLink->name }} ({{ strtoupper($activeTrackingLink->channel) }}). Konversi otomatis tercatat untuk dashboard toko {{ $activeTrackingLink->store->name }}.</p>
            </div>
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        @csrf

        <!-- Shipping & Recipient Details (7 Cols) -->
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i data-lucide="map-pin" class="w-4 h-4 text-emerald-600"></i>
                Alamat Pengiriman & Penerima
            </h3>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Penerima</label>
                    <input type="text" name="recipient_name" value="{{ old('recipient_name', Auth::check() ? Auth::user()->name : 'Nawaf Pratama') }}" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="recipient_phone" value="{{ old('recipient_phone', Auth::check() ? Auth::user()->phone : '082199887766') }}" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                    <textarea name="shipping_address" rows="3" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('shipping_address', Auth::check() ? Auth::user()->address : 'Jl. Kebon Jeruk No. 12, Jakarta Barat') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Ekspedisi Kurir</label>
                    <select name="courier" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="J&T Express - Reguler (Rp 15.000)">J&T Express - Reguler (Rp 15.000)</option>
                        <option value="JNE Reguler (Rp 15.000)">JNE Reguler (Rp 15.000)</option>
                        <option value="SiCepat REG (Rp 15.000)">SiCepat REG (Rp 15.000)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Order Summary & Escrow Protection (5 Cols) -->
        <div class="lg:col-span-5 space-y-4">
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i data-lucide="receipt" class="w-4 h-4 text-emerald-600"></i>
                    Ringkasan Transaksi
                </h3>

                <div class="space-y-3 divide-y divide-slate-100 max-h-48 overflow-y-auto pr-1">
                    @foreach($cart as $item)
                        <div class="pt-2 first:pt-0 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2 min-w-0 pr-2">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0">
                                <span class="truncate text-slate-800 font-medium">{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                            </div>
                            <span class="font-semibold text-slate-900 flex-shrink-0">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-slate-200 pt-3 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal Produk:</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Ongkos Kirim:</span>
                        <span>Rp {{ number_format($shippingFee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Layanan Plazio:</span>
                        <span>Rp {{ number_format($platformFee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-extrabold text-base text-slate-950 pt-2 border-t border-slate-200">
                        <span>Total Pembayaran:</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-600/20 transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    Bayar & Buat Pesanan
                </button>
            </div>

            <div class="bg-slate-900 text-white p-4 rounded-xl space-y-1 text-xs">
                <span class="font-bold text-emerald-400 flex items-center gap-1">
                    <i data-lucide="shield" class="w-4 h-4"></i> Garansi Escrow 100%
                </span>
                <p class="text-slate-300 text-[11px]">
                    Dana Anda disimpan aman oleh Plazio sampai barang tiba dengan selamat di tangan Anda.
                </p>
            </div>

        </div>

    </form>

</div>
@endsection
