# Konsep Marketplace Multi-Seller
### Dokumen Ringkas untuk Pitching

---

## 1. Latar Belakang & Peluang Pasar

Sejak Mei 2026, muncul gelombang keresahan besar di kalangan seller Shopee, Tokopedia, dan TikTok Shop akibat kenaikan biaya layanan. Gerakan "Tinggalkan E-Commerce" viral di media sosial (Threads, X, TikTok), dan bukan cuma seller kecil — brand-brand besar seperti True to Skin dan Raecca mulai membangun kanal penjualan sendiri di luar marketplace besar.

**Masalah utama yang dikeluhkan:**
- Potongan biaya platform mencapai 19–21% dari nilai transaksi
- Biaya layanan logistik tambahan yang baru diberlakukan sejak Mei 2026
- Resolusi sengketa/komplain yang lambat dan tidak berpihak ke pembeli
- Produk spam dan kualitas tidak terjamin
- Ketergantungan penuh pada satu platform (platform risk)

**Peluang:** Ini momentum tepat untuk hadir sebagai alternatif yang lebih adil bagi seller, dengan model bisnis yang tetap sehat secara finansial.

---

## 2. Pain Points Tambahan & Fitur Penyelesaiannya

Selain masalah biaya & sengketa yang sudah dibahas di atas, ada beberapa pain point spesifik lain yang perlu jadi perhatian desain produk:

### Sisi Pembeli
| Masalah | Solusi di Platform Kita |
|---|---|
| Biaya tersembunyi — harga di halaman produk beda jauh dari total di checkout | Tampilkan estimasi harga total (termasuk semua biaya layanan) sejak di halaman produk/keranjang, bukan baru muncul di langkah akhir checkout |
| Ulasan palsu/manipulatif dari jasa review | Label "Verified Purchase" — hanya pembeli yang sudah menyelesaikan transaksi yang bisa memberi ulasan; batasi ulasan berulang dalam waktu singkat |
| Hasil pencarian didominasi iklan tidak relevan | Utamakan relevansi hasil pencarian organik; sediakan filter presisi (spesifikasi, harga akhir, lokasi, opsi pengiriman) |
| Terjebak chatbot CS yang berputar tanpa solusi | Eskalasi otomatis ke CS manusia jika bot gagal selesaikan masalah dalam 2–3 langkah |

### Sisi Penjual
| Masalah | Solusi di Platform Kita |
|---|---|
| Persaingan tidak sehat dari barang impor murah (dumping price) | Badge/highlight khusus untuk produk lokal & UMKM; badge reputasi toko berdasarkan kualitas layanan & kecepatan kirim |
| Pencairan dana (payout) lambat, ganggu arus kas seller | Integrasi payment gateway dengan fitur instant/cepat payout setelah transaksi terkonfirmasi selesai (bukan menunggu berhari-hari) |

### Fitur UX Tambahan (Unique Selling Proposition)
- **UI/UX ringan & cepat dimuat** — hindari pop-up iklan mengganggu yang jadi ciri khas keluhan di platform besar
- **Checkout efisien** — minimalkan jumlah langkah dari halaman produk sampai pembayaran (misal one-click checkout untuk pengguna terdaftar)
- **Tracking pengiriman real-time** — status resi terintegrasi langsung di aplikasi, tanpa perlu buka web ekspedisi terpisah
- **Keamanan transaksi & privasi data** — enkripsi pembayaran yang kuat, kontrol privasi data yang mudah diakses pengguna

---

## 3. Positioning: Model Hybrid

**Marketplace + Toko Online Mandiri dalam Satu Platform**

Menggabungkan dua kebutuhan sekaligus:
1. **Sebagai marketplace** — seller ditemukan pembeli baru lewat trafik organik platform
2. **Sebagai tools toko mandiri** — seller yang sudah punya audiens sendiri (dari iklan FB/TikTok) bisa punya storefront pribadi, data pelanggan sendiri, tanpa terkunci penuh ke satu platform

Ini terinspirasi dari tren nyata: platform seperti Scalev (sudah proses 1 juta+ transaksi) membuktikan ada demand besar untuk tools "self-owned e-commerce" — tapi Scalev tidak menyediakan trafik organik/marketplace. Di situlah celah differensiasi kita.

---

## 4. Model Bisnis: Komisi Rendah, Multi-Sumber Pendapatan

Komisi transaksi **rendah dan transparan** (bukan sumber utama profit), sumber pendapatan digeser ke:

| Sumber Pendapatan | Tahap |
|---|---|
| Komisi transaksi rendah (3–5%) | Sejak awal |
| Iklan & Boost Produk (termasuk fitur ads FB/TikTok terintegrasi) | Sejak awal |
| Subscription seller (paket Pro/Premium) | Growth |
| Jasa logistik & fulfillment | Growth |
| Fintech add-on (paylater, pinjaman modal, asuransi) | Scale (via partnership) |

---

## 5. Fitur Unggulan: Tracking Link Toko (Ganti "Self-Service Ads Tool")

Fitur pembeda utama — **Link Toko dengan Tracking Klik & Konversi**, jauh lebih sederhana dari integrasi Ads API penuh:

1. Seller di dashboard klik "Generate Link Toko" atau "Generate Link Produk" → dapat link unik dengan kode referral (misal `namaplatform.id/toko/namatoko?ref=abc123`)
2. Seller pasang/bagikan link ini sendiri di iklan Meta Ads, TikTok Ads, Google Ads, atau media sosial manapun — seller yang atur & bayar iklannya sendiri, sepenuhnya di luar platform kita
3. Saat calon pembeli klik link → sistem catat 1 klik, lalu arahkan ke landing page toko/produk seller
4. Sistem simpan jejak kunjungan (cookie/session) untuk kaitkan kunjungan dengan link tersebut
5. Kalau pengunjung itu akhirnya membeli (walau beberapa saat kemudian), sistem catat sebagai konversi dari link itu
6. Dashboard seller menampilkan: jumlah klik, jumlah konversi, dan conversion rate — bisa dipecah per produk atau per platform iklan (Meta vs TikTok vs lainnya)

**Kenapa ini lebih baik untuk MVP:**
- Tidak perlu App Review/approval dari Meta, TikTok, atau Google — karena platform kita tidak mengelola campaign iklan mereka lewat API, hanya menyediakan link + tracking
- Development jauh lebih sederhana (mirip cara kerja tracking link/affiliate link), tidak perlu menangani OAuth atau spesifikasi API tiap platform
- Seller tetap punya kontrol penuh atas materi iklan, budget, dan platform mana yang mereka pakai
- Seller dapat data yang berguna (klik & konversi) tanpa perlu buka Ads Manager masing-masing platform

**Pengembangan lanjutan (opsional, tahap growth):**
- Link berbeda per produk, bukan cuma per toko — biar seller tahu produk mana yang paling laku dari iklan
- Integrasi Meta/TikTok/Google Ads API penuh baru dipertimbangkan di tahap growth/scale, setelah tervalidasi bahwa seller memang butuh kontrol iklan langsung dari dashboard kita (bukan sekadar tracking)

---

## 6. Strategi Trafik & Akuisisi Pembeli

**A. Iklan dari sisi platform (brand awareness)**
- Meta Ads, TikTok Ads, Google Search Ads untuk menarik pembeli baru ke marketplace secara keseluruhan

**B. Bantu seller lacak hasil iklan sendiri (tracking link)**
- Seperti dijelaskan di poin 5 — seller pasang iklan & buat materinya sendiri di platform pilihan mereka, kita hanya sediakan link toko/produk dengan tracking klik & konversi
- Kredit iklan gratis untuk seller baru sebagai insentif awal (opsional, bisa berupa saldo promosi di marketplace, bukan budget ads eksternal)

**C. Growth organik & affiliate**
- Program afiliasi/reseller dengan komisi
- Kolaborasi micro-influencer dan komunitas UMKM
- Program referral (ajak teman dapat cashback)
- Konten organik di TikTok/Instagram resmi platform

---

## 7. Diferensiasi vs Kompetitor

| Aspek | Shopee/Tokopedia | Scalev | Toco.id | Platform Ini |
|---|---|---|---|---|
| Model bisnis | Marketplace multi-seller | Tools toko mandiri (bukan marketplace) | Marketplace multi-seller | Marketplace multi-seller |
| Trafik pembeli | Organik besar | Seller cari sendiri | Masih tahap awal, belum ada network effect kuat | Organik + bantu seller beriklan |
| Biaya untuk seller | Komisi 15–21%+ | Langganan Rp0–495rb/bulan + ~1% fee | Rp2.000 flat per transaksi (bukan persentase) | Rendah (3–5%) + ads/subscription |
| Performa teknis | Stabil, matang | Stabil | Masih lambat/kurang stabil saat traffic tinggi | Target: stabil sejak awal |
| Verifikasi produk & seller | Ketat, tapi tetap ada spam | N/A | Longgar — sering ditemukan produk KW/palsu | Kurasi & verifikasi lebih ketat |
| Resolusi sengketa | Lambat, dikeluhkan | N/A | Belum banyak data | SLA jelas, sistem eskrow |
| Fitur promosi/iklan | Lengkap (ads, flash sale) | Tools sendiri, seller atur sendiri | Belum ada iklan berbayar, flash sale, atau push campaign | Tracking link toko/produk (klik & konversi), seller pasang iklan sendiri di platform pilihannya |
| Kepemilikan data pelanggan | Dikunci platform | Milik seller | Dikunci platform | Hybrid — tergantung sumber traffic (dari marketplace: dikunci platform; dari storefront mandiri seller: milik seller) |
| Kategori produk utama | Umum, semua kategori | Semua kategori (digital & fisik) | Classified ads: preloved, properti, otomotif (mirip OLX) | Sesuai niche pilihan kita |

### Insight dari Kompetitor Toco.id
Toco adalah pemain baru (diluncurkan Agustus 2024) dengan model serupa — biaya rendah, ramah ke seller kecil/mikro — dan tumbuh cepat (150 ribu+ seller aktif dalam waktu singkat). Namun mereka masih punya celah nyata:
- Performa aplikasi belum stabil saat traffic tinggi
- Verifikasi produk longgar, banyak produk KW/palsu
- Belum ada fitur promosi masif (iklan berbayar, flash sale)
- Basis pembeli & network effect masih tahap awal

**Peluang kita:** masuk dengan eksekusi teknis lebih matang sejak awal, verifikasi/kurasi lebih ketat, dan fitur self-service ads yang belum mereka punya — sambil tetap mempertahankan model biaya rendah yang jadi daya tarik utama seller.

---

## 8. Tahapan Pengembangan

1. **MVP/Validasi:** Fokus 1 kota/niche, komisi rendah, fitur tracking link toko/produk (klik & konversi) — seller pasang iklan sendiri di Meta/TikTok/Google, cukup salin & pasang link dari dashboard kita
2. **Growth:** Ekspansi regional, tambah subscription seller, link tracking per produk & per sumber iklan, mulai profit dari komisi & subscription
3. **Scale:** Ekspansi nasional, pertimbangkan integrasi penuh Meta/TikTok/Google Ads API (jika tervalidasi dibutuhkan), tambah fintech add-on via partnership, cari pendanaan investor lanjutan

---

## 9. Yang Masih Perlu Divalidasi

- Riset mendalam target niche spesifik (survei calon seller & pembeli)
- Proyeksi finansial & unit economics detail
- Kesiapan teknis (tim developer / partner tech, termasuk pengalaman integrasi Meta/TikTok/Google Ads API)
- Regulasi terkait (perizinan e-commerce, data pribadi/UU PDP, fintech jika masuk tahap scale)
