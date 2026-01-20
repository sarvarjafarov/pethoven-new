<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\Facades\CartSession;
use Lunar\Models\ProductVariant;

class CartController extends Controller
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        $cart = CartSession::current();

        return view('frontend.cart.index', compact('cart'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        try {
            $request->validate([
                'variant_id' => 'required|exists:lunar_product_variants,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $variant = ProductVariant::find($request->variant_id);

            if (!$variant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product variant not found'
                ], 404);
            }

            // Add item to cart (Lunar will auto-create cart if needed with proper currency/channel)
            CartSession::add(
                purchasable: $variant,
                quantity: $request->quantity
            );

            // Get the cart after adding item
            $cart = CartSession::current();
            
            // Ensure cart exists and has required relationships
            if (!$cart) {
                \Log::error('Cart is null after adding item');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create cart. Please refresh and try again.'
                ], 500);
            }
            
            // Refresh cart to ensure calculations are up to date
            $cart->refresh();
            
            // Calculate cart count safely
            $cartCount = 0;
            if ($cart && $cart->lines) {
                $cartCount = $cart->lines->sum('quantity');
            }
            
            // Calculate cart total safely
            $cartTotal = '£0.00';
            if ($cart) {
                try {
                    // Calculate the cart total if it hasn't been calculated yet
                    $total = $cart->total;
                    if ($total) {
                        $cartTotal = $total->formatted ?? '£0.00';
                    }
                } catch (\Exception $e) {
                    // If total calculation fails, default to £0.00
                    \Log::warning('Cart total calculation failed: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Cart add error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $lineId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            CartSession::updateLine(
                cartLineId: $lineId,
                quantity: $request->quantity
            );

            $cart = CartSession::current();

            if ($request->ajax()) {
                $cartCount = $cart && $cart->lines ? $cart->lines->sum('quantity') : 0;
                $cartTotal = '£0.00';
                if ($cart) {
                    try {
                        $total = $cart->total;
                        if ($total) {
                            $cartTotal = $total->formatted ?? '£0.00';
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Cart total calculation failed in update: ' . $e->getMessage());
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated',
                    'cart_count' => $cartCount,
                    'cart_total' => $cartTotal
                ]);
            }

            return redirect()->route('cart.index')
                ->with('success', 'Cart updated successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update cart'
                ], 400);
            }

            return redirect()->route('cart.index')
                ->with('error', 'Failed to update cart');
        }
    }

    /**
     * Remove item from cart
     */
    public function remove($lineId)
    {
        try {
            CartSession::removeLine($lineId);

            $cart = CartSession::current();

            if (request()->ajax()) {
                $cartCount = $cart && $cart->lines ? $cart->lines->sum('quantity') : 0;
                $cartTotal = '£0.00';
                if ($cart) {
                    try {
                        $total = $cart->total;
                        if ($total) {
                            $cartTotal = $total->formatted ?? '£0.00';
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Cart total calculation failed in remove: ' . $e->getMessage());
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'cart_count' => $cartCount,
                    'cart_total' => $cartTotal
                ]);
            }

            return redirect()->route('cart.index')
                ->with('success', 'Item removed from cart');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to remove item'
                ], 400);
            }

            return redirect()->route('cart.index')
                ->with('error', 'Failed to remove item');
        }
    }

    /**
     * Clear all items from cart
     */
    public function clear()
    {
        CartSession::clear();

        return redirect()->route('cart.index')
            ->with('success', 'Cart cleared successfully');
    }

    /**
     * Get cart count for header
     */
    public function count()
    {
        try {
            $cart = CartSession::current();
            $count = $cart && $cart->lines ? $cart->lines->sum('quantity') : 0;
            
            return response()->json([
                'count' => $count
            ]);
        } catch (\Exception $e) {
            \Log::warning('Cart count failed: ' . $e->getMessage());
            return response()->json([
                'count' => 0
            ]);
        }
    }
}
