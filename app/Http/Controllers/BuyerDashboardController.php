<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductReview;
use App\Models\Dispute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerDashboardController extends Controller
{
    private function getBuyer()
    {
        return Auth::user() ?? \App\Models\User::where('role', 'buyer')->first();
    }

    public function orders()
    {
        $buyer = $this->getBuyer();
        $orders = Order::where('buyer_id', $buyer->id)
            ->with(['store', 'items.product', 'dispute'])
            ->latest()
            ->get();

        return view('buyer.orders', compact('orders'));
    }

    public function confirmReceived($orderId)
    {
        $buyer = $this->getBuyer();
        $order = Order::where('buyer_id', $buyer->id)->where('id', $orderId)->firstOrFail();

        if ($order->status !== 'completed') {
            $order->status = 'completed';
            $order->escrow_released_at = now();
            $order->save();

            // Release escrow money into seller's store balance
            $store = $order->store;
            if ($store) {
                $store->increment('balance', $order->total_product_amount);
            }
        }

        return redirect()->route('buyer.orders')->with('success', 'Pesanan dikonfirmasi selesai! Dana escrow telah diteruskan ke toko penjual.');
    }

    public function storeReview(Request $request, $orderId)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5',
        ]);

        $buyer = $this->getBuyer();
        $order = Order::where('buyer_id', $buyer->id)->where('id', $orderId)->firstOrFail();

        // Check verified purchase condition
        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'Hanya transaksi selesai yang dapat memberikan ulasan terverifikasi.');
        }

        ProductReview::updateOrCreate(
            ['order_id' => $order->id, 'product_id' => $request->product_id, 'user_id' => $buyer->id],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_verified_purchase' => true
            ]
        );

        return redirect()->route('buyer.orders')->with('success', 'Ulasan Verified Purchase berhasil dipublikasikan!');
    }

    public function storeDispute(Request $request, $orderId)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'required|string|min:10',
        ]);

        $buyer = $this->getBuyer();
        $order = Order::where('buyer_id', $buyer->id)->where('id', $orderId)->firstOrFail();

        $order->status = 'disputed';
        $order->save();

        Dispute::create([
            'order_id' => $order->id,
            'user_id' => $buyer->id,
            'reason' => $request->reason,
            'details' => $request->details,
            'status' => 'open',
            'sla_escalated' => true // SLA auto escalated to Human CS
        ]);

        return redirect()->route('buyer.orders')->with('success', 'Komplain diajukan! CS Manusia Plazio telah di-eskalasi secara otomatis untuk menyelesaikan sengketa Anda.');
    }
}
