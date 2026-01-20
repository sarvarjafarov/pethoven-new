<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Lunar\Models\Product;

class WishlistController extends Controller
{
    /**
     * Display wishlist page
     */
    public function index()
    {
        $wishlistItems = $this->getWishlistItems();

        // Load products with relationships
        $productIds = $wishlistItems->pluck('product_id');
        $products = Product::with([
            'variants.prices',
            'thumbnail',
            'collections',
            'defaultUrl'
        ])
        ->whereIn('id', $productIds)
        ->where('status', 'published')
        ->get();

        return view('frontend.wishlist.index', compact('products', 'wishlistItems'));
    }

    /**
     * Add product to wishlist
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:lunar_products,id'
        ]);

        $productId = $request->product_id;
        $userId = auth()->id();
        $sessionId = session()->getId();

        // Check if already in wishlist
        if (Wishlist::isInWishlist($productId, $userId, $sessionId)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is already in your wishlist'
                ]);
            }

            return back()->with('info', 'Product is already in your wishlist');
        }

        // Add to wishlist
        Wishlist::create([
            'user_id' => $userId,
            'session_id' => $userId ? null : $sessionId,
            'product_id' => $productId,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist',
                'count' => $this->getWishlistCount()
            ]);
        }

        return back()->with('success', 'Product added to wishlist');
    }

    /**
     * Remove product from wishlist
     */
    public function remove(Request $request, $productId)
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $query = Wishlist::where('product_id', $productId);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $deleted = $query->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist',
                'count' => $this->getWishlistCount()
            ]);
        }

        return back()->with('success', 'Product removed from wishlist');
    }

    /**
     * Clear entire wishlist
     */
    public function clear()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $query = Wishlist::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $query->delete();

        return back()->with('success', 'Wishlist cleared successfully');
    }

    /**
     * Get wishlist count
     */
    public function count()
    {
        $count = $this->getWishlistCount();

        return response()->json(['count' => $count]);
    }

    /**
     * Helper: Get wishlist items for current user/session
     */
    protected function getWishlistItems()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $query = Wishlist::query();

        if ($userId) {
            $query->forUser($userId);
        } else {
            $query->forSession($sessionId);
        }

        return $query->get();
    }

    /**
     * Helper: Get wishlist count
     */
    protected function getWishlistCount(): int
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $query = Wishlist::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return $query->count();
    }
}
