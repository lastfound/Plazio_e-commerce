@extends('layouts.app')

@section('title', 'Pencairan Dana (Instant Payout) - ' . $store->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="{ modalOpen: false }">

    <div class="border-b border-slate-200 pb-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2">
                <i data-lucide="zap" class="w-6 h-6 text-amber-500"></i>
                Pencairan Dana Instant (Payout System)
            </h1>
            <p class="text-xs text-slate-500">Pencairan cepat langsung ke rekening bank Anda setelah transaksi terkonfirmasi selesai tanpa perlu menunggu berhari-hari.</p>
        </div>

        <button @click="modalOpen = true" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2">
            <i data-lucide="arrow-up-right" class="w-4 h-4"></i> Tarik Dana Sekarang
        </button>
    </div>

    <!-- Balance Card -->
    <div class="bg-gradient-to-r from-slate-900 to-emerald-950 text-white p-6 rounded-2xl border border-slate-800 shadow-md flex flex-wrap items-center justify-between gap-6">
        <div>
            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Saldo Siap Dicairkan</span>
            <div class="text-3xl font-extrabold text-white mt-1">Rp {{ number_format($store->balance, 0, ',', '.') }}</div>
            <p class="text-xs text-emerald-400 font-semibold mt-1">✓ Instant Payout Active (Pro Tier)</p>
        </div>

        <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/10 text-xs space-y-1 max-w-sm">
            <span class="font-bold text-amber-400 flex items-center gap-1">
                <i data-lucide="clock" class="w-4 h-4"></i> Solusi Arus Kas Seller
            </span>
            <p class="text-slate-300 text-[11px]">
                Fitur Instant Payout mentransfer dana dalam 5 menit setelah pembeli mengklik "Pesanan Diterima".
            </p>
        </div>
    </div>

    <!-- History Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3">Riwayat Penarikan Dana</h3>

        @if($payouts->isEmpty())
            <div class="text-center py-8 text-xs text-slate-500">
                Belum ada riwayat penarikan dana.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="p-3">Kode Referensi</th>
                            <th class="p-3">Jenis Payout</th>
                            <th class="p-3">Jumlah (Rp)</th>
                            <th class="p-3">Rekening Tujuan</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @foreach($payouts as $po)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $po->reference_code }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $po->payout_speed === 'instant' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-slate-100 text-slate-800' }}">
                                        {{ $po->payout_speed }}
                                    </span>
                                </td>
                                <td class="p-3 font-bold text-emerald-700">Rp {{ number_format($po->amount, 0, ',', '.') }}</td>
                                <td class="p-3 text-slate-600">{{ $po->bank_name }} - {{ $po->account_number }} (a.n {{ $po->account_name }})</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                        {{ $po->status }}
                                    </span>
                                </td>
                                <td class="p-3 text-slate-500 text-[11px]">{{ $po->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Payout Modal -->
    <div x-show="modalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="modalOpen = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900">Form Penarikan Dana</h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('seller.payouts.request') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Penarikan (Rp)</label>
                    <input type="number" name="amount" max="{{ $store->balance }}" value="{{ min(500000, $store->balance) }}" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Maksimal saldo: Rp {{ number_format($store->balance, 0, ',', '.') }}</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kecepatan Payout</label>
                    <select name="payout_speed" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="instant">Instant Payout (Transfer &lt; 5 Menit)</option>
                        <option value="regular">Reguler Payout (1x24 Jam)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Bank</label>
                    <select name="bank_name" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                        <option value="BCA">BCA (Bank Central Asia)</option>
                        <option value="Mandiri">Bank Mandiri</option>
                        <option value="BRI">Bank BRI</option>
                        <option value="BNI">Bank BNI</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Rekening</label>
                        <input type="text" name="account_number" value="8820192812" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Atas Nama</label>
                        <input type="text" name="account_name" value="{{ $store->name }}" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" @click="modalOpen = false" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md">Proses Transfer</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
