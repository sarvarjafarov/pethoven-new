<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\Models\Product;

class QuickViewController extends Controller
{
    /**
     * Show product quick view
     */
    public function show($slug)
    {
        // Find product by URL slug or ID
        $product = Product::whereHas('urls', function($q) use ($slug) {
                $q->where('slug', $slug)->where('default', true);
            })
            ->orWhere('id', $slug)
            ->where('status', 'published')
            ->with([
                'variants.prices.currency',
                'variants.values.option',
                'images',
                'thumbnail',
                'collections'
            ])
            ->firstOrFail();

        // Return partial view for AJAX request
        if (request()->ajax()) {
            return view('frontend.components.product-quick-view', compact('product'));
        }

        // Redirect to full product page if not AJAX
        return redirect()->route('shop.product.show', $product->defaultUrl?->slug ?? $product->id);
    }
}
