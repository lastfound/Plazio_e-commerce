@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya - Plazio.id')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="{ reviewModal: false, disputeModal: false, activeOrderId: null, activeProductId: null }">

    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2">
            <i data-lucide="package-check" class="w-6 h-6 text-emerald-600"></i>
            Riwayat Pesanan Saya
        </h1>
        <p class="text-xs text-slate-500">Pantau status pesanan, konfirmasi penerimaan barang untuk melepaskan escrow, atau berikan ulasan terverifikasi.</p>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 space-y-3">
            <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                <i data-lucide="package-open" class="w-7 h-7"></i>
            </div>
            <h3 class="font-bold text-slate-800">Belum ada transaksi pesanan</h3>
            <p class="text-xs text-slate-500">Anda belum pernah melakukan pembelian produk di Plazio.</p>
            <a href="{{ route('marketplace.index') }}" class="inline-block px-5 py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl">Mulai Belanja</a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    
                    <!-- Header status -->
                    <div class="flex flex-wrap items-center justify-between border-b border-slate-100 pb-3 gap-2 text-xs">
                        <div class="flex items-center gap-2">
                            <i data-lucide="store" class="w-4 h-4 text-emerald-600"></i>
                            <a href="{{ route('marketplace.store', $order->store->slug) }}" class="font-bold text-slate-900 hover:underline">{{ $order->store->name }}</a>
                            <span class="text-slate-300">•</span>
                            <span class="font-mono text-slate-500">{{ $order->order_number }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 text-[11px]">{{ $order->created_at->format('d M Y H:i') }}</span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $order->status === 'paid' || $order->status === 'processing' ? 'bg-amber-100 text-amber-800' : '' }}
                                {{ $order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status === 'disputed' ? 'bg-rose-100 text-rose-800 border border-rose-200' : '' }}
                            ">
                                {{ $order->status === 'completed' ? 'Selesai' : ($order->status === 'disputed' ? 'Sengketa CS' : $order->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Items list & details -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                        <div class="md:col-span-8 space-y-3">
                            @foreach($order->items as $item)
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->product ? $item->product->image : 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=300&q=80' }}" alt="{{ $item->product_name }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-xs text-slate-900 truncate">{{ $item->product_name }}</h4>
                                        <p class="text-[11px] text-slate-500">{{ $item->quantity }} barang x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="font-bold text-xs text-slate-900 font-mono">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach

                            @if($order->tracking_number)
                                <div class="bg-blue-50 border border-blue-200 p-2.5 rounded-xl text-xs text-blue-900 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5 font-medium">
                                        <i data-lucide="truck" class="w-4 h-4 text-blue-600"></i>
                                        Resi: <strong>{{ $order->tracking_number }}</strong> ({{ $order->shipping_courier }})
                                    </span>
                                    <span class="text-[10px] bg-blue-200 text-blue-900 px-2 py-0.5 rounded font-bold">Dalam Pengiriman</span>
                                </div>
                            @endif
                        </div>

                        <!-- Price Breakdown & Actions -->
                        <div class="md:col-span-4 bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3 text-xs">
                            <div class="space-y-1">
                                <div class="flex justify-between text-slate-500 text-[11px]">
                                    <span>Biaya Layanan:</span>
                                    <span>Rp {{ number_format($order->platform_fee, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-slate-500 text-[11px]">
                                    <span>Ongkir:</span>
                                    <span>Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between font-extrabold text-sm text-slate-900 pt-1 border-t border-slate-200">
                                    <span>Total Bayar:</span>
                                    <span>Rp {{ number_format($order->total_paid_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Escrow Action Buttons -->
                            @if($order->status !== 'completed' && $order->status !== 'disputed')
                                <form action="{{ route('buyer.orders.confirm', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Konfirmasi bahwa paket barang telah sampai dalam kondisi baik?')" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-colors flex items-center justify-center gap-1">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                        Pesanan Diterima (Lepas Escrow)
                                    </button>
                                </form>

                                <button @click="disputeModal = true; activeOrderId = {{ $order->id }}" class="w-full py-1.5 bg-slate-200 hover:bg-rose-100 hover:text-rose-700 text-slate-700 font-semibold text-[11px] rounded-lg transition-colors">
                                    Ada Masalah / Ajukan Komplain SLA CS
                                </button>
                            @elseif($order->status === 'completed')
                                <div class="space-y-2">
                                    <div class="p-2 bg-emerald-100/60 border border-emerald-200 text-emerald-900 rounded text-[11px] text-center font-semibold">
                                        ✓ Escrow Selesai (Dana Terkirim ke Seller)
                                    </div>
                                    @foreach($order->items as $item)
                                        <button @click="reviewModal = true; activeOrderId = {{ $order->id }}; activeProductId = {{ $item->product_id }}" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-colors">
                                            Beri Ulasan Verified Purchase
                                        </button>
                                    @endforeach
                                </div>
                            @elseif($order->status === 'disputed')
                                <div class="p-3 bg-rose-50 border border-rose-200 text-rose-900 rounded-xl space-y-1 text-xs">
                                    <span class="font-bold flex items-center gap-1">
                                        <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i> SLA CS Escalated
                                    </span>
                                    <p class="text-[11px] text-rose-800">
                                        Komplain dalam penanganan CS Manusia. Dana escrow ditahan sampai ada keputusan admin.
                                    </p>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    <!-- Review Modal (Verified Purchase) -->
    <div x-show="reviewModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="reviewModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                    <i data-lucide="star" class="w-4 h-4 text-amber-500"></i>
                    Beri Ulasan Verified Purchase
                </h3>
                <button @click="reviewModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form :action="'/buyer/orders/' + activeOrderId + '/review'" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="product_id" :value="activeProductId">

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Rating Bintang (1 - 5)</label>
                    <select name="rating" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-amber-600">
                        <option value="5">⭐⭐⭐⭐⭐ 5 - Sangat Memuaskan</option>
                        <option value="4">⭐⭐⭐⭐ 4 - Bagus</option>
                        <option value="3">⭐⭐⭐ 3 - Cukup</option>
                        <option value="2">⭐⭐ 2 - Kurang</option>
                        <option value="1">⭐ 1 - Kecewa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ulasan Pengalaman Pembelian</label>
                    <textarea name="comment" rows="3" required placeholder="Tuliskan ulasan sejujurnya mengenai kualitas barang, respon seller, dan kecepatan kirim..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" @click="reviewModal = false" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md">Kirim Ulasan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Dispute Modal (SLA CS Escalation) -->
    <div x-show="disputeModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="disputeModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                    <i data-lucide="shield-alert" class="w-4 h-4 text-rose-600"></i>
                    Eskalasi Komplain ke CS Manusia Plazio
                </h3>
                <button @click="disputeModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form :action="'/buyer/orders/' + activeOrderId + '/dispute'" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan Komplain</label>
                    <select name="reason" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                        <option value="Barang Tidak Sesuai Deskripsi / Palsu">Barang Tidak Sesuai Deskripsi / Palsu</option>
                        <option value="Barang Rusak Saat Pengiriman">Barang Rusak Saat Pengiriman</option>
                        <option value="Jumlah Barang Kurang / Salah Kirim">Jumlah Barang Kurang / Salah Kirim</option>
                        <option value="Paket Tidak Berkelanjutan Dikirim Seller">Paket Tidak Berkelanjutan Dikirim Seller</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Detail Kendala Transaksi</label>
                    <textarea name="details" rows="3" required placeholder="Jelaskan detail masalah yang Anda alami..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>

                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-[11px] text-amber-900">
                    Sistem Plazio langsung meng-eskalasi masalah ke CS Manusia dengan SLA respon maksimal 2-3 jam kerja.
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" @click="disputeModal = false" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md">Ajukan Komplain CS</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
