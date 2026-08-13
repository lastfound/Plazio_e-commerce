@extends('layouts.app')

@section('title', 'Admin Panel CS & Platform - Plazio.id')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{ resolveModal: false, activeDisputeId: null }">

    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-950 via-slate-900 to-slate-950 text-white p-6 rounded-2xl border border-purple-900/50 shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-xs font-bold mb-2">
                <i data-lucide="shield-alert" class="w-4 h-4"></i> Admin CS & Escrow Resolution Queue
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight">Admin Escalation Center</h1>
            <p class="text-xs text-slate-300">Menangani sengketa pembeli/penjual dengan SLA respon cepat & verifikasi seller lokal.</p>
        </div>
    </div>

    <!-- Admin Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-500">Gross GMV Platform</span>
            <div class="text-2xl font-extrabold text-slate-900">Rp {{ number_format($platformGrossGMV, 0, ',', '.') }}</div>
            <p class="text-[11px] text-slate-400">Total nilai transaksi marketplace</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-500">Pendapatan Komisi Plazio</span>
            <div class="text-2xl font-extrabold text-emerald-700">Rp {{ number_format($platformCommissions, 0, ',', '.') }}</div>
            <p class="text-[11px] text-emerald-800 font-semibold">Komisi rendah 3.5% + fee 2rb</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-500">Total Toko Penjual</span>
            <div class="text-2xl font-extrabold text-purple-900">{{ $totalStores }} Toko</div>
            <p class="text-[11px] text-purple-700 font-semibold">Terdaftar & Terverifikasi</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-500">Antrean Dispute SLA CS</span>
            <div class="text-2xl font-extrabold text-rose-600">{{ $disputes->where('status', 'open')->count() }} Case</div>
            <p class="text-[11px] text-rose-700 font-semibold">Perlu penanganan manusia</p>
        </div>
    </div>

    <!-- Dispute Escalation Queue Table (Solves MD Pain Point: Resolusi sengketa lambat) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                    <i data-lucide="life-buoy" class="w-4 h-4 text-rose-600"></i>
                    Antrean Eskalasi Dispute Human CS (SLA SLA 2-3 Jam)
                </h3>
                <p class="text-xs text-slate-500">Eskalasi otomatis dari bot ke CS Manusia saat pembeli mengajukan komplain sengketa.</p>
            </div>
        </div>

        @if($disputes->isEmpty())
            <div class="text-center py-8 text-xs text-slate-500">
                Tidak ada sengketa komplain aktif.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="p-3">Order #</th>
                            <th class="p-3">Pembeli</th>
                            <th class="p-3">Toko Seller</th>
                            <th class="p-3">Alasan Komplain</th>
                            <th class="p-3">Detail Masalah</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Aksi Admin CS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @foreach($disputes as $d)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $d->order->order_number }}</td>
                                <td class="p-3">{{ $d->user->name }}</td>
                                <td class="p-3 font-semibold text-emerald-800">{{ $d->order->store->name }}</td>
                                <td class="p-3 font-bold text-rose-700">{{ $d->reason }}</td>
                                <td class="p-3 text-slate-600 max-w-xs">{{ $d->details }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        {{ $d->status === 'open' ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-emerald-100 text-emerald-800' }}
                                    ">
                                        {{ $d->status }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    @if($d->status === 'open')
                                        <button @click="resolveModal = true; activeDisputeId = {{ $d->id }}" class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white font-bold text-[11px] rounded-lg">
                                            Selesaikan Dispute
                                        </button>
                                    @else
                                        <span class="text-slate-400 text-[11px]">Selesai: {{ $d->resolution_notes }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Dispute Resolution Modal -->
    <div x-show="resolveModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="resolveModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900">Keputusan Resolusi Admin CS</h3>
                <button @click="resolveModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form :action="'/admin/disputes/' + activeDisputeId + '/resolve'" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Keputusan Akhir Escrow</label>
                    <select name="status" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold">
                        <option value="resolved_buyer">Pengembalian Dana ke Pembeli (Refund Buyer)</option>
                        <option value="resolved_seller">Lepaskan Dana ke Toko Seller (Release Seller)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Keputusan Admin</label>
                    <textarea name="notes" rows="3" required placeholder="Tulis alasan keputusan berdasarkan bukti..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" @click="resolveModal = false" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md">Eksekusi Keputusan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
