<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Category;
use App\Models\Product;
use App\Models\StoreTrackingLink;
use App\Models\TrackingLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\Dispute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::create([
            'name' => 'Admin Plazio',
            'email' => 'admin@plazio.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jakarta Selatan, DKI Jakarta'
        ]);

        // 2. Create Buyer User
        $buyer = User::create([
            'name' => 'Nawaf Pratama',
            'email' => 'buyer@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'phone' => '082199887766',
            'address' => 'Jl. Kebon Jeruk No. 12, Jakarta Barat'
        ]);

        // 3. Create Categories
        $catSkincare = Category::create(['name' => 'Kecantikan & Skincare', 'slug' => 'kecantikan-skincare', 'icon' => 'sparkles', 'description' => 'Produk perawatan kulit lokal terpercaya']);
        $catFashion = Category::create(['name' => 'Fashion & Sepatu Lokal', 'slug' => 'fashion-sepatu-lokal', 'icon' => 'shopping-bag', 'description' => 'Sepatu dan pakaian brand lokal berkategori UMKM']);
        $catFood = Category::create(['name' => 'Makanan & Kopi Artisan', 'slug' => 'makanan-kopi-artisan', 'icon' => 'coffee', 'description' => 'Biji kopi pilihan dan camilan berkualitas khas daerah']);
        $catGadget = Category::create(['name' => 'Aksesoris & Gadget Lokal', 'slug' => 'aksesoris-gadget-lokal', 'icon' => 'smartphone', 'description' => 'Aksesoris handmade dan gadget buatan tanah air']);

        // 4. Create Seller 1 (True to Skin)
        $userSeller1 = User::create([
            'name' => 'True to Skin Official',
            'email' => 'seller@truetoskin.id',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '081122334455',
            'address' => 'Bandung, Jawa Barat'
        ]);

        $store1 = Store::create([
            'user_id' => $userSeller1->id,
            'name' => 'True to Skin Official',
            'slug' => 'truetoskin',
            'tagline' => 'Gentle & Clean Local Skincare Solution',
            'description' => 'Brand skincare lokal bebas bahan kimia keras, 100% halal dan tersertifikasi BPOM.',
            'logo' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=300&q=80',
            'banner' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=1200&q=80',
            'city' => 'Bandung',
            'is_verified' => true,
            'is_local_umkm' => true,
            'rating' => 4.95,
            'subscription_tier' => 'pro',
            'instant_payout_enabled' => true,
            'balance' => 4850000.00
        ]);

        // Products for Store 1
        $p1 = Product::create([
            'store_id' => $store1->id,
            'category_id' => $catSkincare->id,
            'name' => 'Mugwort Triphasic Purifying Cleanser 100ml',
            'slug' => 'mugwort-triphasic-purifying-cleanser-100ml',
            'description' => 'Pembersih wajah dengan ekstrak Mugwort dan Cica alami yang menenangkan kulit sensitif dan membersihkan pori-pori secara mendalam tanpa terasa kering.',
            'price' => 119000,
            'discount_price' => 89000,
            'stock' => 120,
            'weight_grams' => 200,
            'image' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=600&q=80',
            'specs' => ['Ukuran' => '100 ml', 'BPOM' => 'NA18221200192', 'Bahan Utama' => 'Mugwort, Cica, Niacinamide 2%'],
            'is_local_umkm' => true,
            'is_featured' => true,
            'rating' => 4.9,
            'reviews_count' => 84,
            'sales_count' => 340,
            'platform_commission_percent' => 3.0
        ]);

        $p2 = Product::create([
            'store_id' => $store1->id,
            'category_id' => $catSkincare->id,
            'name' => 'Hyalu-Cica Brightening Serum 30ml',
            'slug' => 'hyalu-cica-brightening-serum-30ml',
            'description' => 'Serum pencerah kulit dengan 5 jenis Hyaluronic Acid dan Vitamin C generasi terbaru yang cepat meresap dan mencerahkan flek hitam.',
            'price' => 149000,
            'discount_price' => 125000,
            'stock' => 85,
            'weight_grams' => 150,
            'image' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=600&q=80',
            'specs' => ['Ukuran' => '30 ml', 'BPOM' => 'NA18221900441', 'Bahan Utama' => '5x Hyaluronic Acid, Vitamin C 10%'],
            'is_local_umkm' => true,
            'is_featured' => true,
            'rating' => 4.95,
            'reviews_count' => 112,
            'sales_count' => 520,
            'platform_commission_percent' => 3.0
        ]);

        // 5. Create Seller 2 (Aerostreet Footwear)
        $userSeller2 = User::create([
            'name' => 'Aerostreet Shoes Official',
            'email' => 'seller@aerostreet.id',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '085711223344',
            'address' => 'Klaten, Jawa Tengah'
        ]);

        $store2 = Store::create([
            'user_id' => $userSeller2->id,
            'name' => 'Aerostreet Shoes',
            'slug' => 'aerostreet',
            'tagline' => 'Lokal Tak Gentar - Sepatu Berkualitas Harga Terjangkau',
            'description' => 'Produsen sepatu vulcanized lokal berkualitas tinggi dengan teknologi injection moulding tahan air dan awet.',
            'logo' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=300&q=80',
            'banner' => 'https://images.unsplash.com/photo-1460353581641-37babbab0fa6?auto=format&fit=crop&w=1200&q=80',
            'city' => 'Klaten',
            'is_verified' => true,
            'is_local_umkm' => true,
            'rating' => 4.88,
            'subscription_tier' => 'premium',
            'instant_payout_enabled' => true,
            'balance' => 12400000.00
        ]);

        $p3 = Product::create([
            'store_id' => $store2->id,
            'category_id' => $catFashion->id,
            'name' => 'Aerostreet Hoops Low White Red Navy - Sepatu Sneakers Lokal',
            'slug' => 'aerostreet-hoops-low-white-red-navy',
            'description' => 'Sepatu sneakers lokal terbaik dengan sol menyatu sempurna tanpa lem (Injection Moulding). Nyaman dipakai harian.',
            'price' => 199000,
            'discount_price' => 159000,
            'stock' => 200,
            'weight_grams' => 900,
            'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80',
            'specs' => ['Bahan' => 'Kain Kanvas Premium + Mesh', 'Sol' => 'Karet Moulded Anti Slip', 'Ukuran Tersedia' => '37 - 44'],
            'is_local_umkm' => true,
            'is_featured' => true,
            'rating' => 4.92,
            'reviews_count' => 450,
            'sales_count' => 1290,
            'platform_commission_percent' => 3.5
        ]);

        $p4 = Product::create([
            'store_id' => $store2->id,
            'category_id' => $catFashion->id,
            'name' => 'Aerostreet Osaka Black Gum Edition',
            'slug' => 'aerostreet-osaka-black-gum-edition',
            'description' => 'Sneakers kanvas klasik dengan sol warna gum alami yang retro dan durable.',
            'price' => 179000,
            'discount_price' => 149000,
            'stock' => 95,
            'weight_grams' => 850,
            'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=600&q=80',
            'specs' => ['Bahan' => 'Kanvas 12 oz', 'Sol' => 'Natural Rubber Gum'],
            'is_local_umkm' => true,
            'is_featured' => false,
            'rating' => 4.85,
            'reviews_count' => 98,
            'sales_count' => 410,
            'platform_commission_percent' => 3.5
        ]);

        // 6. Create Seller 3 (Kopi Kenangan Senja)
        $userSeller3 = User::create([
            'name' => 'Kopi Senja Roastery',
            'email' => 'seller@kopi.id',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '087812345678',
            'address' => 'Malang, Jawa Timur'
        ]);

        $store3 = Store::create([
            'user_id' => $userSeller3->id,
            'name' => 'Senja Roastery Malang',
            'slug' => 'senja-roastery',
            'tagline' => 'Fresh Roasted Indonesian Specialty Coffee',
            'description' => 'Roastery kopi lokal spesialis biji kopi Arabika dari petani binaan di Dampit dan Ijen.',
            'logo' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=300&q=80',
            'banner' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=1200&q=80',
            'city' => 'Malang',
            'is_verified' => true,
            'is_local_umkm' => true,
            'rating' => 4.98,
            'subscription_tier' => 'pro',
            'instant_payout_enabled' => true,
            'balance' => 3200000.00
        ]);

        $p5 = Product::create([
            'store_id' => $store3->id,
            'category_id' => $catFood->id,
            'name' => 'Biji Kopi Arabika Ijen Full Wash 250g - Specialty Single Origin',
            'slug' => 'biji-kopi-arabika-ijen-full-wash-250g',
            'description' => 'Biji kopi arabika pilihan dari lereng Gunung Ijen dengan notes citrus, brown sugar, dan floral aroma yang harum memikat.',
            'price' => 85000,
            'discount_price' => 75000,
            'stock' => 150,
            'weight_grams' => 300,
            'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=600&q=80',
            'specs' => ['Origin' => 'Gunung Ijen 1.400 mdpl', 'Process' => 'Full Wash', 'Roast Level' => 'Medium Roast'],
            'is_local_umkm' => true,
            'is_featured' => true,
            'rating' => 4.98,
            'reviews_count' => 64,
            'sales_count' => 280,
            'platform_commission_percent' => 3.0
        ]);

        // 7. Create Tracking Links (USP Feature)
        $link1 = StoreTrackingLink::create([
            'store_id' => $store1->id,
            'product_id' => $p1->id,
            'name' => 'Iklan Meta - Campaign Mugwort Cleanser Promo 8.8',
            'code' => 'meta_mugwort_88',
            'channel' => 'meta_ads',
            'target_type' => 'product',
            'clicks_count' => 1420,
            'conversions_count' => 84,
            'total_revenue' => 7476000
        ]);

        $link2 = StoreTrackingLink::create([
            'store_id' => $store1->id,
            'product_id' => null,
            'name' => 'TikTok Bio Link - Storefront Promo',
            'code' => 'tiktok_bio_truetoskin',
            'channel' => 'tiktok_ads',
            'target_type' => 'store',
            'clicks_count' => 2850,
            'conversions_count' => 165,
            'total_revenue' => 18975000
        ]);

        $link3 = StoreTrackingLink::create([
            'store_id' => $store2->id,
            'product_id' => $p3->id,
            'name' => 'Meta Ads Sepatu Hoops Low Target Remaja',
            'code' => 'meta_hoops_low',
            'channel' => 'meta_ads',
            'target_type' => 'product',
            'clicks_count' => 3900,
            'conversions_count' => 210,
            'total_revenue' => 33390000
        ]);

        // 8. Create Tracking Logs
        TrackingLog::create([
            'store_tracking_link_id' => $link1->id,
            'ip_address' => '180.252.10.14',
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X)',
            'referrer' => 'https://facebook.com',
            'event_type' => 'click'
        ]);

        // 9. Create Completed Order with Verified Purchase
        $order1 = Order::create([
            'order_number' => 'PLZ-20260813-0001',
            'buyer_id' => $buyer->id,
            'store_id' => $store1->id,
            'store_tracking_link_id' => $link1->id,
            'total_product_amount' => 89000,
            'shipping_fee' => 15000,
            'platform_fee' => 2000,
            'total_paid_amount' => 106000,
            'status' => 'completed',
            'shipping_courier' => 'J&T Express - Reguler',
            'tracking_number' => 'JT8892019208',
            'recipient_name' => $buyer->name,
            'recipient_phone' => $buyer->phone,
            'shipping_address' => $buyer->address,
            'escrow_released_at' => now()->subDays(1)
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p1->id,
            'product_name' => $p1->name,
            'price' => 89000,
            'quantity' => 1,
            'subtotal' => 89000
        ]);

        // 10. Verified Purchase Review
        ProductReview::create([
            'product_id' => $p1->id,
            'user_id' => $buyer->id,
            'order_id' => $order1->id,
            'rating' => 5,
            'comment' => 'Pengiriman cepat banget, packing aman pake bubble wrap tebal. Cleanser ini ampuh menenangkan jerawat pasir saya! Verified purchase memuaskan!',
            'is_verified_purchase' => true
        ]);
    }
}
