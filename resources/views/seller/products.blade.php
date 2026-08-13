@extends('layouts.app')

@section('title', 'Manajemen Produk Toko - ' . $store->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="{ modalOpen: false, linkModalOpen: false, activeLink: '', copied: false }">

    <!-- Top Banner: Storefront Link Sharing Box (Solves user request #2) -->
    <div class="bg-gradient-to-r from-slate-900 to-emerald-950 text-white p-6 rounded-2xl border border-slate-800 shadow-md flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1.5">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/20 text-emerald-300 rounded-full text-xs font-bold">
                <i data-lucide="store" class="w-4 h-4"></i> Laman Toko Mandiri Seller
            </div>
            <h2 class="text-xl font-extrabold tracking-tight">Laman Produk Toko Anda: <span class="text-emerald-400">{{ url('/toko/' . $store->slug) }}</span></h2>
            <p class="text-xs text-slate-300">
                Bagikan link ini ke pelanggan Anda. Halaman toko mandiri ini khusus menampilkan produk-produk milik Anda saja.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" @click="navigator.clipboard.writeText('{{ url('/toko/' . $store->slug) }}'); copied = true; setTimeout(() => copied = false, 2000)" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-xs rounded-xl shadow-md transition-colors flex items-center gap-2">
                <i data-lucide="copy" class="w-4 h-4"></i>
                Salin Link Toko
            </button>
            <a href="{{ route('marketplace.store', $store->slug) }}" target="_blank" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs rounded-xl transition-colors flex items-center gap-2 border border-white/20">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                Buka Toko
            </a>
        </div>
    </div>

    <!-- Header & Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2">
                <i data-lucide="box" class="w-6 h-6 text-emerald-600"></i>
                Katalog Produk Toko
            </h1>
            <p class="text-xs text-slate-500">Semua produk yang Anda tambahkan di sini akan <strong>LANGSUNG TAMPIL di Halaman Utama (Homepage)</strong> platform.</p>
        </div>

        <button @click="modalOpen = true" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-600/20 flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4.5 h-4.5"></i> Tambah Produk Baru
        </button>
    </div>

    <!-- Products Table -->
    @if($products->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 space-y-4">
            <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                <i data-lucide="package-plus" class="w-7 h-7"></i>
            </div>
            <h3 class="font-bold text-slate-800">Belum Ada Produk Ditambahkan</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">Klik tombol "Tambah Produk Baru" untuk memasukkan barang dagangan Anda. Produk akan langsung muncul di Halaman Utama marketplace.</p>
            <button @click="modalOpen = true" class="px-5 py-2.5 bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md">
                Tambah Produk Pertama
            </button>
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
                        <th class="p-3">Status Homepage</th>
                        <th class="p-3">Link Produk Khusus</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @foreach($products as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 flex items-center gap-3">
                                <img src="{{ $p->image }}" alt="{{ $p->name }}" class="w-11 h-11 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                                <div>
                                    <a href="{{ route('marketplace.product', $p->slug) }}" target="_blank" class="font-bold text-slate-900 hover:text-emerald-600 line-clamp-1 max-w-xs block">
                                        {{ $p->name }}
                                    </a>
                                    <span class="text-[10px] text-slate-400">ID: #{{ $p->id }} • Terjual {{ $p->sales_count }}</span>
                                </div>
                            </td>
                            <td class="p-3 text-slate-600">{{ $p->category->name }}</td>
                            <td class="p-3 font-mono">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                            <td class="p-3 font-mono font-semibold text-emerald-700">
                                {{ $p->discount_price ? 'Rp ' . number_format($p->discount_price, 0, ',', '.') : '-' }}
                            </td>
                            <td class="p-3 font-mono">{{ $p->stock }} unit</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1 w-max">
                                    <i data-lucide="check" class="w-3 h-3"></i> Tampil di Homepage
                                </span>
                            </td>
                            <td class="p-3">
                                <button type="button" @click="activeLink = '{{ url('/p/' . $p->slug) }}'; linkModalOpen = true" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded text-[11px] flex items-center gap-1 border border-slate-200">
                                    <i data-lucide="share-2" class="w-3 h-3 text-emerald-600"></i> Dapatkan Link
                                </button>
                            </td>
                            <td class="p-3">
                                <a href="{{ route('marketplace.product', $p->slug) }}" target="_blank" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg inline-block" title="Lihat di Web">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Toast Copy Notification -->
    <div x-show="copied" x-transition class="fixed bottom-6 right-6 bg-slate-900 text-white px-4 py-2.5 rounded-xl shadow-xl text-xs font-semibold z-50 flex items-center gap-2 border border-slate-700">
        <i data-lucide="check" class="w-4 h-4 text-emerald-400"></i>
        <span>Link berhasil disalin ke clipboard! Siap dibagikan.</span>
    </div>

    <!-- Share Product Link Modal -->
    <div x-show="linkModalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="linkModalOpen = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                    <i data-lucide="link-2" class="w-4 h-4 text-emerald-600"></i>
                    Bagikan Link Halaman Produk
                </h3>
                <button @click="linkModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <div class="space-y-3">
                <p class="text-xs text-slate-600">
                    Pelanggan yang mengklik link ini akan langsung diarahkan ke halaman detail produk barang Anda:
                </p>

                <div class="flex items-center gap-2">
                    <input type="text" readonly :value="activeLink" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800">
                    <button type="button" @click="navigator.clipboard.writeText(activeLink); copied = true; setTimeout(() => copied = false, 2000)" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl flex-shrink-0">
                        Salin
                    </button>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="button" @click="linkModalOpen = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-semibold text-xs rounded-xl">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Add Product Modal (Supports File Upload & Image URL) -->
    <div x-show="modalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="modalOpen = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-200 max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                    <i data-lucide="package-plus" class="w-4.5 h-4.5 text-emerald-600"></i>
                    Tambah Barang Dagangan Baru
                </h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3" x-data="{ imgMode: 'file' }">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Barang / Produk</label>
                    <input type="text" name="name" placeholder="Misal: Sepatu Sneakers Kanvas Lokal Black" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Produk</label>
                        <select name="category_id" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Stok Awal</label>
                        <input type="number" name="stock" value="50" min="1" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Normal (Rp)</label>
                        <input type="number" name="price" placeholder="150000" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Diskon (Opsional)</label>
                        <input type="number" name="discount_price" placeholder="120000" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <!-- Image Upload / URL Selector -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold text-slate-700">Foto / Gambar Produk</label>
                        <div class="flex gap-2 text-[10px]">
                            <button type="button" @click="imgMode = 'file'" :class="imgMode === 'file' ? 'text-emerald-700 font-bold underline' : 'text-slate-400'">Upload File</button>
                            <span>|</span>
                            <button type="button" @click="imgMode = 'url'" :class="imgMode === 'url' ? 'text-emerald-700 font-bold underline' : 'text-slate-400'">Pakai URL</button>
                        </div>
                    </div>

                    <div x-show="imgMode === 'file'">
                        <input type="file" name="image_file" accept="image/*" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>

                    <div x-show="imgMode === 'url'">
                        <input type="url" name="image" placeholder="https://images.unsplash.com/..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Lengkap Produk</label>
                    <textarea name="description" rows="3" required placeholder="Jelaskan spesifikasi, keunggulan, dan bahan produk Anda..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_local_umkm" value="1" checked id="umkm_check" class="w-4 h-4 text-emerald-600 rounded">
                    <label for="umkm_check" class="text-xs font-semibold text-slate-700">Tampilkan Badge Produk & UMKM Lokal</label>
                </div>

                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-[11px] text-emerald-900">
                    ✨ <strong>Otomatis Tampil di Homepage:</strong> Setelah disimpan, produk akan langsung tayang di katalog utama platform dan di Laman Toko Mandiri Anda.
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" @click="modalOpen = false" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md">Simpan & Tayangkan</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
