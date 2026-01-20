<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\Facades\CartSession;
use Lunar\Models\ProductVariant;
use Lunar\Models\Cart;
use Lunar\Models\Currency;
use Lunar\Models\Channel;

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

            $variant = ProductVariant::with(['product', 'taxClass'])->find($request->variant_id);

            if (!$variant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product variant not found'
                ], 404);
            }

            // Ensure variant has required data
            if (!$variant->id) {
                \Log::error('Variant found but has no ID', ['variant_id' => $request->variant_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid product variant'
                ], 400);
            }

            // Ensure variant has a product relationship
            if (!$variant->product || !$variant->product->id) {
                \Log::error('Variant product is missing or invalid', [
                    'variant_id' => $variant->id,
                    'product' => $variant->product ? 'exists but no id' : 'null'
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Product information is incomplete. Please contact support.'
                ], 400);
            }

            // Ensure variant has a tax class (required for tax calculation)
            if (!$variant->tax_class_id) {
                \Log::warning('Variant missing tax_class_id, attempting to assign default tax class', [
                    'variant_id' => $variant->id
                ]);
                
                // Get or create default tax class
                $taxClass = \Lunar\Models\TaxClass::first();
                if (!$taxClass) {
                    $taxClass = \Lunar\Models\TaxClass::create([
                        'name' => 'Default Tax',
                    ]);
                }
                
                // Assign tax class to variant
                $variant->tax_class_id = $taxClass->id;
                $variant->save();
            }

            // Ensure we have a cart with currency and channel BEFORE adding items
            $cart = CartSession::current();
            
            // If no cart exists or cart is missing currency/channel, create/fix it
            if (!$cart || !$cart->currency_id || !$cart->channel_id) {
                $currency = Currency::getDefault();
                $channel = Channel::getDefault();
                
                if (!$currency || !$currency->id) {
                    \Log::error('No default currency found or currency has no ID', [
                        'currency' => $currency ? 'exists but no id' : 'null'
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Store configuration error. Please contact support.'
                    ], 500);
                }
                
                if (!$channel || !$channel->id) {
                    \Log::error('No default channel found or channel has no ID', [
                        'channel' => $channel ? 'exists but no id' : 'null'
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Store configuration error. Please contact support.'
                    ], 500);
                }
                
                // Create new cart if it doesn't exist
                if (!$cart) {
                    $cart = Cart::create([
                        'currency_id' => $currency->id,
                        'channel_id' => $channel->id,
                        'user_id' => auth()->id(),
                    ]);
                    
                    // Store cart ID in session
                    if ($cart && $cart->id) {
                        session()->put(config('lunar.cart_session.session_key'), $cart->id);
                    }
                } else {
                    // Fix existing cart
                    if (!$cart->currency_id && $currency->id) {
                        $cart->currency_id = $currency->id;
                    }
                    if (!$cart->channel_id && $channel->id) {
                        $cart->channel_id = $channel->id;
                    }
                    $cart->save();
                }
                
                // Reload cart with relationships to ensure Lunar can access them
                if ($cart) {
                    $cart->load(['currency', 'channel']);
                }
            }

            // Ensure cart is fresh from session before adding item
            $cart = CartSession::current();
            if (!$cart) {
                \Log::error('Cart is null before adding item');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to retrieve cart. Please refresh and try again.'
                ], 500);
            }

            // Ensure cart has currency and channel relationships loaded
            if (!$cart->relationLoaded('currency') || !$cart->relationLoaded('channel')) {
                $cart->load(['currency', 'channel']);
            }

            // Verify required relationships exist
            if (!$cart->currency || !$cart->currency->id) {
                \Log::error('Cart currency is missing or invalid', ['cart_id' => $cart->id ?? null]);
                return response()->json([
                    'success' => false,
                    'message' => 'Cart configuration error. Please refresh and try again.'
                ], 500);
            }

            if (!$cart->channel || !$cart->channel->id) {
                \Log::error('Cart channel is missing or invalid', ['cart_id' => $cart->id ?? null]);
                return response()->json([
                    'success' => false,
                    'message' => 'Cart configuration error. Please refresh and try again.'
                ], 500);
            }

            // Now add item to cart (cart is guaranteed to have currency and channel)
            // Wrap in try-catch to catch any Lunar internal errors
            try {
                CartSession::add(
                    purchasable: $variant,
                    quantity: $request->quantity
                );
            } catch (\Exception $e) {
                \Log::error('CartSession::add() failed: ' . $e->getMessage(), [
                    'exception' => $e,
                    'trace' => $e->getTraceAsString(),
                    'cart_id' => ($cart && isset($cart->id)) ? $cart->id : null,
                    'cart_currency_id' => ($cart && isset($cart->currency_id)) ? $cart->currency_id : null,
                    'cart_channel_id' => ($cart && isset($cart->channel_id)) ? $cart->channel_id : null,
                    'variant_id' => ($variant && isset($variant->id)) ? $variant->id : null,
                ]);
                throw $e; // Re-throw to be caught by outer try-catch
            }

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
