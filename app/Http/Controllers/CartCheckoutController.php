<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreTrackingLink;
use App\Models\TrackingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartCheckoutController extends Controller
{
    public function viewCart()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shippingFee = empty($cart) ? 0 : 15000;
        $platformFee = empty($cart) ? 0 : 2000;
        $total = $subtotal + $shippingFee + $platformFee;

        return view('cart.index', compact('cart', 'subtotal', 'shippingFee', 'platformFee', 'total'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $product = Product::with('store')->findOrFail($request->product_id);
        $cart = session()->get('cart', []);
        $qty = $request->input('quantity', 1);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $qty;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->effective_price,
                'image' => $product->image,
                'store_name' => $product->store->name,
                'store_id' => $product->store_id,
                'quantity' => $qty,
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'cartCount' => count(session('cart', []))]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang diperbarui!');
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang!');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('marketplace.index')->with('error', 'Keranjang belanja Anda kosong!');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shippingFee = 15000;
        $platformFee = 2000; // Low transparent service fee
        $total = $subtotal + $shippingFee + $platformFee;

        // Check active tracking link code from referral session
        $activeTrackingLink = null;
        if (session()->has('active_tracking_link_id')) {
            $activeTrackingLink = StoreTrackingLink::find(session('active_tracking_link_id'));
        }

        return view('checkout.index', compact('cart', 'subtotal', 'shippingFee', 'platformFee', 'total', 'activeTrackingLink'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:30',
            'shipping_address' => 'required|string',
            'courier' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('marketplace.index')->with('error', 'Keranjang kosong!');
        }

        // Auto login default buyer if not logged in
        $user = Auth::user() ?? \App\Models\User::where('role', 'buyer')->first();
        Auth::login($user);

        // Group cart items by store_id
        $itemsByStore = [];
        foreach ($cart as $item) {
            $itemsByStore[$item['store_id']][] = $item;
        }

        $createdOrders = [];
        $activeTrackingLinkId = session('active_tracking_link_id');

        foreach ($itemsByStore as $storeId => $items) {
            $storeSubtotal = 0;
            foreach ($items as $item) {
                $storeSubtotal += $item['price'] * $item['quantity'];
            }
            $shippingFee = 15000;
            $platformFee = 2000;
            $totalOrderPaid = $storeSubtotal + $shippingFee + $platformFee;

            $order = Order::create([
                'order_number' => 'PLZ-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'buyer_id' => $user->id,
                'store_id' => $storeId,
                'store_tracking_link_id' => $activeTrackingLinkId,
                'total_product_amount' => $storeSubtotal,
                'shipping_fee' => $shippingFee,
                'platform_fee' => $platformFee,
                'total_paid_amount' => $totalOrderPaid,
                'status' => 'paid',
                'shipping_courier' => $request->courier,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'shipping_address' => $request->shipping_address,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Increment sales count
                Product::where('id', $item['id'])->increment('sales_count', $item['quantity']);
            }

            // Track link conversion USP logic
            if ($activeTrackingLinkId) {
                $link = StoreTrackingLink::find($activeTrackingLinkId);
                if ($link && $link->store_id == $storeId) {
                    $link->increment('conversions_count');
                    $link->increment('total_revenue', $storeSubtotal);

                    TrackingLog::create([
                        'store_tracking_link_id' => $link->id,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->header('User-Agent'),
                        'event_type' => 'conversion',
                        'order_id' => $order->id,
                        'conversion_amount' => $storeSubtotal
                    ]);
                }
            }

            $createdOrders[] = $order;
        }

        // Clear cart and referral session
        session()->forget('cart');

        return redirect()->route('buyer.orders')->with('success', 'Transaksi berhasil! Pesanan Anda telah dibuat dan siap diproses penjual.');
    }
}
