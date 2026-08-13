@extends('layouts.app')

@section('title', 'Manajemen Produk Toko - ' . $store->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="{ modalOpen: false }">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2">
                <i data-lucide="box" class="w-6 h-6 text-emerald-600"></i>
                Katalog Produk Toko
            </h1>
            <p class="text-xs text-slate-500">Kelola daftar produk, stok, harga transparan, dan badge UMKM Lokal.</p>
        </div>

        <button @click="modalOpen = true" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Produk Baru
        </button>
    </div>

    @if($products->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
            <p class="text-xs text-slate-500">Belum ada produk terdaftar di katalog Anda.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="p-3">Produk</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Harga Normal</th>
                        <th class="p-3">Harga Diskon</th>
                        <th class="p-3">Stok</th>
                        <th class="p-3">Badge UMKM</th>
                        <th class="p-3">Penjualan</th>
                        <th class="p-3">Komisi Platform</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @foreach($products as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 flex items-center gap-3">
                                <img src="{{ $p->image }}" alt="{{ $p->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                                <span class="font-bold text-slate-900 line-clamp-1 max-w-xs">{{ $p->name }}</span>
                            </td>
                            <td class="p-3 text-slate-600">{{ $p->category->name }}</td>
                            <td class="p-3 font-mono">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                            <td class="p-3 font-mono font-semibold text-emerald-700">
                                {{ $p->discount_price ? 'Rp ' . number_format($p->discount_price, 0, ',', '.') : '-' }}
                            </td>
                            <td class="p-3 font-mono">{{ $p->stock }} unit</td>
                            <td class="p-3">
                                @if($p->is_local_umkm)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold umkm-badge">Lokal UMKM</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="p-3 font-mono font-bold">{{ number_format($p->sales_count) }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded font-mono text-[10px] font-bold">{{ $p->platform_commission_percent }}%</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Add Product Modal -->
    <div x-show="modalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="modalOpen = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-200">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900">Tambah Produk Baru</h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('seller.products.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Produk</label>
                    <input type="text" name="name" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                        <select name="category_id" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Stok Awal</label>
                        <input type="number" name="stock" value="50" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Normal (Rp)</label>
                        <input type="number" name="price" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Diskon (Opsional)</label>
                        <input type="number" name="discount_price" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">URL Gambar Produk</label>
                    <input type="url" name="image" placeholder="https://..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Ringkas</label>
                    <textarea name="description" rows="3" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_local_umkm" value="1" checked id="umkm_check" class="w-4 h-4 text-emerald-600 rounded">
                    <label for="umkm_check" class="text-xs font-semibold text-slate-700">Tampilkan Badge Produk Lokal & UMKM</label>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" @click="modalOpen = false" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md">Simpan Produk</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
