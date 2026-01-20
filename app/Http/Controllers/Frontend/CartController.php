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

            // Ensure cart exists
            $cart = CartSession::current();
            if (!$cart) {
                $cart = CartSession::create();
            }

            CartSession::add(
                purchasable: $variant,
                quantity: $request->quantity
            );

            $cart = CartSession::current();

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_count' => $cart ? $cart->lines->sum('quantity') : 0,
                'cart_total' => $cart ? $cart->total->formatted : '£0.00'
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
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated',
                    'cart_count' => $cart->lines->sum('quantity'),
                    'cart_total' => $cart->total->formatted
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
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'cart_count' => $cart->lines->sum('quantity'),
                    'cart_total' => $cart->total->formatted
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
        $cart = CartSession::current();

        return response()->json([
            'count' => $cart->lines->sum('quantity')
        ]);
    }
}
