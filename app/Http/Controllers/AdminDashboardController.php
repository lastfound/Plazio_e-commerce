<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use App\Models\Dispute;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalStores = Store::count();
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $platformGrossGMV = Order::sum('total_paid_amount');
        $platformCommissions = Order::sum('platform_fee');

        $disputes = Dispute::with(['order.store', 'user'])->latest()->get();
        $stores = Store::with('user')->latest()->get();

        return view('admin.dashboard', compact('totalStores', 'totalUsers', 'totalOrders', 'platformGrossGMV', 'platformCommissions', 'disputes', 'stores'));
    }

    public function resolveDispute(Request $request, $disputeId)
    {
        $request->validate([
            'status' => 'required|in:resolved_buyer,resolved_seller',
            'notes' => 'required|string',
        ]);

        $dispute = Dispute::with('order.store')->findOrFail($disputeId);
        $dispute->status = $request->status;
        $dispute->resolution_notes = $request->notes;
        $dispute->save();

        if ($request->status === 'resolved_buyer') {
            $dispute->order->status = 'cancelled';
            $dispute->order->save();
        } else {
            $dispute->order->status = 'completed';
            $dispute->order->escrow_released_at = now();
            $dispute->order->save();

            // Release funds to seller
            $dispute->order->store->increment('balance', $dispute->order->total_product_amount);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Sengketa berhasil diselesaikan oleh CS Admin!');
    }
}
