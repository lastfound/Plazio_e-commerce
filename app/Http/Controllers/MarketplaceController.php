<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['store', 'category']);

        // Search filter
        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->query('category'));
            });
        }

        // Local UMKM filter
        if ($request->query('local_umkm') === '1') {
            $query->where('is_local_umkm', true);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->query('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->query('max_price'));
        }

        // Location / City filter
        if ($request->filled('city')) {
            $query->whereHas('store', function ($q) use ($request) {
                $q->where('city', $request->query('city'));
            });
        }

        // Sorting
        $sort = $request->query('sort', 'latest');
        if ($sort === 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_high') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'top_sales') {
            $query->orderBy('sales_count', 'desc');
        } elseif ($sort === 'rating') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();
        $featuredStores = Store::where('is_verified', true)->withCount('products')->get();

        return view('marketplace.index', compact('products', 'categories', 'featuredStores'));
    }

    public function showProduct($slug)
    {
        $product = Product::where('slug', $slug)->with(['store', 'category', 'reviews.user', 'reviews.order'])->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        // Calculate transparent price estimate (Product price + Shipping estimate 15k + Transparent Service Fee 2k)
        $estimatedTotal = $product->effective_price + 15000 + 2000;

        return view('marketplace.product-detail', compact('product', 'relatedProducts', 'estimatedTotal'));
    }

    public function showStore($slug)
    {
        $store = Store::where('slug', $slug)->with(['user', 'products.category'])->firstOrFail();
        $products = $store->products()->latest()->paginate(12);
        $categories = Category::all();

        return view('marketplace.store-front', compact('store', 'products', 'categories'));
    }
}
