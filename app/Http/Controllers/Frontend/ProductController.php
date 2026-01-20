<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\Models\Product;
use Lunar\Models\Collection;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with([
            'variants.prices',
            'variants.values.option',
            'thumbnail',
            'collections',
            'defaultUrl'
        ])->where('status', 'published');

        // Filter by collection
        if ($request->has('collection')) {
            $query->whereHas('collections', function($q) use ($request) {
                $q->where('slug', $request->collection);
            });
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_high':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);
        $collections = Collection::withCount('products')->get();
        $totalProducts = Product::where('status', 'published')->count();

        return view('frontend.shop.index', compact('products', 'collections', 'sort', 'totalProducts'));
    }

    public function show($slug)
    {
        // Try to find by URL slug first, fallback to ID
        $product = Product::whereHas('urls', function($q) use ($slug) {
                $q->where('slug', $slug)->where('default', true);
            })
            ->orWhere('id', $slug)
            ->where('status', 'published')
            ->with([
                'variants.prices.currency',
                'variants.values.option',
                'images',
                'collections'
            ])
            ->firstOrFail();

        // Get related products from same collections
        $related = Product::whereHas('collections', function($q) use ($product) {
                // Qualify the column to avoid ambiguity in Postgres when the relationship subquery joins tables
                $q->whereIn('lunar_collections.id', $product->collections->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->where('status', 'published')
            ->with([
                'variants.prices',
                'thumbnail',
                'collections',
                'defaultUrl'
            ])
            ->limit(4)
            ->get();

        // Prepare variant data for frontend (JSON-safe, avoid complex expressions inside Blade @json)
        $variantData = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'price' => optional($variant->prices->first())->price->formatted ?? '',
                'values' => $variant->values->pluck('name', 'option.name')->toArray(),
            ];
        })->values();

        return view('frontend.shop.show', compact('product', 'related', 'variantData'));
    }
}
