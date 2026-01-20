<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Compare;
use Illuminate\Http\Request;
use Lunar\Models\Product;

class CompareController extends Controller
{
    /**
     * Maximum number of products allowed in compare
     */
    const MAX_COMPARE_ITEMS = 4;

    /**
     * Display compare page
     */
    public function index()
    {
        $compareItems = $this->getCompareItems();

        // Load products with relationships
        $productIds = $compareItems->pluck('product_id');
        $products = Product::with([
            'variants.prices',
            'variants.values.option',
            'thumbnail',
            'collections',
            'defaultUrl'
        ])
        ->whereIn('id', $productIds)
        ->where('status', 'published')
        ->get();

        return view('frontend.compare.index', compact('products', 'compareItems'));
    }

    /**
     * Add product to compare
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:lunar_products,id'
        ]);

        $productId = $request->product_id;
        $userId = auth()->id();
        $sessionId = session()->getId();

        // Check if already in compare
        if (Compare::isInCompare($productId, $userId, $sessionId)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is already in your compare list'
                ]);
            }

            return back()->with('info', 'Product is already in your compare list');
        }

        // Check if compare list is full
        $currentCount = $this->getCompareCount();
        if ($currentCount >= self::MAX_COMPARE_ITEMS) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only compare up to ' . self::MAX_COMPARE_ITEMS . ' products at a time'
                ]);
            }

            return back()->with('error', 'You can only compare up to ' . self::MAX_COMPARE_ITEMS . ' products at a time');
        }

        // Add to compare
        Compare::create([
            'user_id' => $userId,
            'session_id' => $userId ? null : $sessionId,
            'product_id' => $productId,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to compare',
                'count' => $this->getCompareCount()
            ]);
        }

        return back()->with('success', 'Product added to compare');
    }

    /**
     * Remove product from compare
     */
    public function remove(Request $request, $productId)
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $query = Compare::where('product_id', $productId);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $deleted = $query->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from compare',
                'count' => $this->getCompareCount()
            ]);
        }

        return back()->with('success', 'Product removed from compare');
    }

    /**
     * Clear entire compare list
     */
    public function clear()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $query = Compare::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $query->delete();

        return back()->with('success', 'Compare list cleared successfully');
    }

    /**
     * Get compare count
     */
    public function count()
    {
        $count = $this->getCompareCount();

        return response()->json(['count' => $count]);
    }

    /**
     * Helper: Get compare items for current user/session
     */
    protected function getCompareItems()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $query = Compare::query();

        if ($userId) {
            $query->forUser($userId);
        } else {
            $query->forSession($sessionId);
        }

        return $query->get();
    }

    /**
     * Helper: Get compare count
     */
    protected function getCompareCount(): int
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $query = Compare::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return $query->count();
    }
}
