@extends('layouts.app')

@section('title', 'Manajemen Pesanan Masuk - ' . $store->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2">
            <i data-lucide="package" class="w-6 h-6 text-emerald-600"></i>
            Pesanan Masuk (Order Processing)
        </h1>
        <p class="text-xs text-slate-500">Proses pengiriman dan masukkan nomor resi untuk memperbarui status pesanan pembeli.</p>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
            <p class="text-xs text-slate-500">Belum ada pesanan masuk.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    
                    <div class="flex flex-wrap items-center justify-between border-b border-slate-100 pb-3 gap-2">
                        <div class="flex items-center gap-3">
                            <span class="font-mono font-extrabold text-sm text-slate-900">{{ $order->order_number }}</span>
                            <span class="text-slate-400">•</span>
                            <span class="text-xs text-slate-600 font-medium">Pembeli: <strong>{{ $order->buyer->name }}</strong> ({{ $order->recipient_phone }})</span>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($order->trackingLink)
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 flex items-center gap-1">
                                    <i data-lucide="link" class="w-3 h-3"></i> Conv: {{ $order->trackingLink->code }}
                                </span>
                            @endif

                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $order->status === 'paid' || $order->status === 'processing' ? 'bg-amber-100 text-amber-800' : '' }}
                                {{ $order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status === 'disputed' ? 'bg-rose-100 text-rose-800' : '' }}
                            ">
                                Status: {{ $order->status }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                        <div class="md:col-span-7 space-y-2">
                            <div class="space-y-1">
                                @foreach($order->items as $item)
                                    <div class="flex justify-between text-xs text-slate-800 font-medium">
                                        <span>• {{ $item->product_name }} (x{{ $item->quantity }})</span>
                                        <span class="font-mono">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-[11px] text-slate-500 pt-1">
                                Alamat: {{ $order->shipping_address }} | Kurir: {{ $order->shipping_courier }}
                            </p>
                        </div>

                        <div class="md:col-span-5 bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3 text-xs">
                            <div class="flex justify-between items-center font-bold text-slate-900">
                                <span>Nilai Produk:</span>
                                <span>Rp {{ number_format($order->total_product_amount, 0, ',', '.') }}</span>
                            </div>

                            @if($order->status !== 'completed')
                                <form action="{{ route('seller.orders.update', $order->id) }}" method="POST" class="space-y-2 pt-2 border-t border-slate-200">
                                    @csrf
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Update Status & Nomor Resi</label>
                                        <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Masukkan Nomor Resi Ekspidisi..." class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-mono">
                                    </div>
                                    <div class="flex gap-2">
                                        <select name="status" class="flex-1 px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dikirim (Input Resi)</option>
                                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white font-bold text-xs rounded-lg hover:bg-emerald-700">Simpan</button>
                                    </div>
                                </form>
                            @else
                                <div class="p-2 bg-emerald-100/60 border border-emerald-200 rounded text-emerald-950 font-semibold text-[11px] text-center">
                                    Escrow Terlepas: Dana Rp {{ number_format($order->total_product_amount, 0, ',', '.') }} telah masuk saldo toko Anda.
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
