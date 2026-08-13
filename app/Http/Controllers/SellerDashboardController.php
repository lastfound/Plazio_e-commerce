<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\StoreTrackingLink;
use App\Models\SellerPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SellerDashboardController extends Controller
{
    private function getSellerStore()
    {
        $user = Auth::user() ?? \App\Models\User::where('role', 'seller')->first();
        if (!$user->store) {
            // Auto create store if seller doesn't have one yet
            return Store::create([
                'user_id' => $user->id,
                'name' => $user->name . ' Store',
                'slug' => Str::slug($user->name . '-store'),
                'city' => 'Jakarta',
                'is_verified' => true,
                'is_local_umkm' => true
            ]);
        }
        return $user->store;
    }

    public function index()
    {
        $store = $this->getSellerStore();
        $totalProducts = $store->products()->count();
        $totalOrders = $store->orders()->count();
        $totalRevenue = $store->orders()->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])->sum('total_product_amount');
        
        $recentOrders = $store->orders()->with(['buyer', 'items'])->latest()->take(5)->get();
        $trackingLinks = $store->trackingLinks()->latest()->take(5)->get();

        $totalClicks = $store->trackingLinks()->sum('clicks_count');
        $totalConversions = $store->trackingLinks()->sum('conversions_count');
        $overallConversionRate = $totalClicks > 0 ? round(($totalConversions / $totalClicks) * 100, 2) : 0;

        return view('seller.dashboard', compact('store', 'totalProducts', 'totalOrders', 'totalRevenue', 'recentOrders', 'trackingLinks', 'totalClicks', 'totalConversions', 'overallConversionRate'));
    }

    public function trackingLinks()
    {
        $store = $this->getSellerStore();
        $links = $store->trackingLinks()->with('product')->latest()->get();
        $products = $store->products()->get();

        $totalClicks = $links->sum('clicks_count');
        $totalConversions = $links->sum('conversions_count');
        $totalRevenue = $links->sum('total_revenue');

        return view('seller.tracking-links', compact('store', 'links', 'products', 'totalClicks', 'totalConversions', 'totalRevenue'));
    }

    public function storeTrackingLink(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'channel' => 'required|string',
            'target_type' => 'required|in:store,product',
            'product_id' => 'nullable|required_if:target_type,product|exists:products,id',
        ]);

        $store = $this->getSellerStore();
        $code = Str::slug($request->channel) . '_' . Str::random(6);

        StoreTrackingLink::create([
            'store_id' => $store->id,
            'product_id' => $request->target_type === 'product' ? $request->product_id : null,
            'name' => $request->name,
            'code' => $code,
            'channel' => $request->channel,
            'target_type' => $request->target_type,
            'clicks_count' => 0,
            'conversions_count' => 0,
            'total_revenue' => 0
        ]);

        return redirect()->route('seller.tracking-links')->with('success', 'Link Tracking berhasil dibuat! Siap dipasang di iklan Anda.');
    }

    public function products()
    {
        $store = $this->getSellerStore();
        $products = $store->products()->with('category')->latest()->get();
        $categories = Category::all();

        return view('seller.products', compact('store', 'products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|string',
        ]);

        $store = $this->getSellerStore();

        Product::create([
            'store_id' => $store->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(4),
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->filled('discount_price') ? $request->discount_price : null,
            'stock' => $request->stock,
            'weight_grams' => $request->input('weight_grams', 500),
            'image' => $request->image ?: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
            'is_local_umkm' => $request->has('is_local_umkm'),
            'platform_commission_percent' => 3.5
        ]);

        return redirect()->route('seller.products')->with('success', 'Produk berhasil ditambahkan ke katalog toko!');
    }

    public function orders()
    {
        $store = $this->getSellerStore();
        $orders = $store->orders()->with(['buyer', 'items', 'trackingLink'])->latest()->get();

        return view('seller.orders', compact('store', 'orders'));
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:processing,shipped,completed',
            'tracking_number' => 'nullable|string'
        ]);

        $store = $this->getSellerStore();
        $order = Order::where('store_id', $store->id)->where('id', $orderId)->firstOrFail();

        $order->status = $request->status;
        if ($request->filled('tracking_number')) {
            $order->tracking_number = $request->tracking_number;
        }
        $order->save();

        return redirect()->route('seller.orders')->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function payouts()
    {
        $store = $this->getSellerStore();
        $payouts = $store->payouts()->latest()->get();

        return view('seller.payouts', compact('store', 'payouts'));
    }

    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50000',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'payout_speed' => 'required|in:instant,regular'
        ]);

        $store = $this->getSellerStore();

        if ($store->balance < $request->amount) {
            return redirect()->back()->with('error', 'Saldo toko tidak mencukupi untuk penarikan dana ini.');
        }

        // Deduct balance & create payout record
        $store->decrement('balance', $request->amount);

        SellerPayout::create([
            'store_id' => $store->id,
            'amount' => $request->amount,
            'payout_speed' => $request->payout_speed,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'reference_code' => 'PO-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'status' => 'processed'
        ]);

        return redirect()->route('seller.payouts')->with('success', 'Pencairan dana ' . ($request->payout_speed === 'instant' ? 'Instant' : 'Reguler') . ' berhasil diproses!');
    }
}
