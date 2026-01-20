<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\Facades\CartSession;
use Lunar\Models\ProductVariant;
use Lunar\Models\Cart;
use Lunar\Models\Currency;
use Lunar\Models\Channel;
use Lunar\Models\TaxZone;
use Lunar\Models\TaxClass;
use Lunar\Models\TaxRate;
use Lunar\Models\Country;

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
                
                // Reload variant to ensure tax class relationship is available
                $variant->refresh();
                $variant->load('taxClass');
            }
            
            // Ensure the tax class relationship is loaded and valid
            if (!$variant->relationLoaded('taxClass')) {
                $variant->load('taxClass');
            }
            
            // Verify tax class exists (not just the ID)
            if (!$variant->taxClass || !$variant->taxClass->id) {
                \Log::error('Variant tax class relationship is null or invalid', [
                    'variant_id' => $variant->id,
                    'tax_class_id' => $variant->tax_class_id
                ]);
                
                // Get or create default tax class
                $taxClass = \Lunar\Models\TaxClass::first();
                if (!$taxClass) {
                    $taxClass = \Lunar\Models\TaxClass::create([
                        'name' => 'Default Tax',
                    ]);
                }
                
                // Assign and reload
                $variant->tax_class_id = $taxClass->id;
                $variant->save();
                $variant->refresh();
                $variant->load('taxClass');
            }

            // CRITICAL: Ensure TaxZone exists (required for tax calculation)
            // SystemTaxDriver will fail if TaxZone::getDefault() returns null
            $this->ensureTaxZoneExists();

            // CRITICAL: Fix any existing cart lines that might have missing tax classes
            // before we call CartSession::current() which triggers calculation
            // This must happen BEFORE any CartSession operations that trigger calculation
            try {
                $this->fixExistingCartLines();
            } catch (\Exception $e) {
                \Log::warning('Failed to fix existing cart lines before add: ' . $e->getMessage());
            }

            // Ensure we have a cart with currency and channel BEFORE adding items
            // This might trigger calculation, so we fixed existing lines above
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
                    $cart->load('currency');
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

            // Ensure cart has currency relationship loaded
            if (!$cart->relationLoaded('currency')) {
                $cart->load('currency');
            }

            // Verify required relationships exist
            if (!$cart->currency || !$cart->currency->id) {
                \Log::error('Cart currency is missing or invalid', ['cart_id' => $cart->id ?? null]);
                return response()->json([
                    'success' => false,
                    'message' => 'Cart configuration error. Please refresh and try again.'
                ], 500);
            }

            // Verify channel_id exists (Cart model doesn't have channel relationship)
            if (!$cart->channel_id) {
                \Log::error('Cart channel_id is missing', ['cart_id' => $cart->id ?? null]);
                return response()->json([
                    'success' => false,
                    'message' => 'Cart configuration error. Please refresh and try again.'
                ], 500);
            }

            // CRITICAL: Reload variant with all necessary relationships before adding
            // Lunar will use this variant instance, so ensure it has everything loaded
            $variant = ProductVariant::with(['product', 'taxClass', 'prices.currency'])
                ->findOrFail($variant->id);
            
            // Double-check tax class is present (shouldn't be needed but be safe)
            if (!$variant->tax_class_id || !$variant->taxClass || !$variant->taxClass->id) {
                $taxClass = \Lunar\Models\TaxClass::first();
                if (!$taxClass) {
                    $taxClass = \Lunar\Models\TaxClass::create(['name' => 'Default Tax']);
                }
                $variant->tax_class_id = $taxClass->id;
                $variant->save();
                $variant->refresh();
                $variant->load('taxClass');
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
            // Fix any existing cart lines before calculating
            $this->fixExistingCartLines();
            
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

    /**
     * Ensure TaxZone exists - CRITICAL for tax calculation
     * SystemTaxDriver fails if TaxZone::getDefault() returns null
     */
    private function ensureTaxZoneExists()
    {
        try {
            $taxZone = TaxZone::where('default', true)->first();
            
            if (!$taxZone) {
                \Log::warning('Default TaxZone missing, creating one');
                
                $taxZone = TaxZone::create([
                    'name' => 'Default Tax Zone',
                    'zone_type' => 'country',
                    'price_display' => 'tax_exclusive',
                    'default' => true,
                    'active' => true,
                ]);
                
                // Add all countries to the tax zone if possible
                if (class_exists(Country::class) && Country::count() > 0) {
                    try {
                        $taxZone->countries()->createMany(
                            Country::get()->take(10)->map(fn ($country) => [
                                'country_id' => $country->id,
                            ])
                        );
                    } catch (\Exception $e) {
                        \Log::warning('Failed to add countries to tax zone: ' . $e->getMessage());
                    }
                }
            }
            
            // Ensure TaxClass exists
            $taxClass = TaxClass::first();
            if (!$taxClass) {
                $taxClass = TaxClass::create([
                    'name' => 'Default Tax',
                ]);
            }
            
            // Ensure TaxRate exists
            $taxRate = TaxRate::where('tax_zone_id', $taxZone->id)->first();
            
            if (!$taxRate) {
                $taxRate = TaxRate::create([
                    'tax_zone_id' => $taxZone->id,
                    'name' => 'Standard Rate',
                    'priority' => 1,
                ]);
            }

            // Ensure TaxRateAmount exists linking TaxRate to TaxClass
            $taxRateAmount = \Lunar\Models\TaxRateAmount::where('tax_rate_id', $taxRate->id)
                ->where('tax_class_id', $taxClass->id)
                ->first();
            
            if (!$taxRateAmount) {
                \Lunar\Models\TaxRateAmount::create([
                    'tax_rate_id' => $taxRate->id,
                    'tax_class_id' => $taxClass->id,
                    'percentage' => 0,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to ensure TaxZone exists: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            // Don't throw - let it fail gracefully if we can't create it
        }
    }

    /**
     * Fix existing cart lines that might have variants without tax classes
     * This prevents tax calculation errors when CartSession::current() is called
     */
    private function fixExistingCartLines()
    {
        try {
            // Get cart ID from session without triggering calculation
            $cartId = session()->get(config('lunar.cart_session.session_key'));
            
            if (!$cartId) {
                return;
            }
            
            // Get cart with lines and purchasables
            // Use withoutGlobalScopes to avoid any issues
            $cart = Cart::withoutGlobalScopes()
                ->with(['lines.purchasable' => function($query) {
                    $query->with('taxClass');
                }])
                ->find($cartId);
            
            if (!$cart || !$cart->lines || $cart->lines->isEmpty()) {
                return;
            }
            
            // Get default tax class
            $defaultTaxClass = \Lunar\Models\TaxClass::first();
            if (!$defaultTaxClass) {
                $defaultTaxClass = \Lunar\Models\TaxClass::create([
                    'name' => 'Default Tax',
                ]);
            }
            
            $fixed = false;
            
            // Fix any lines with variants missing tax classes
            foreach ($cart->lines as $line) {
                $purchasable = $line->purchasable;
                
                if ($purchasable instanceof ProductVariant) {
                    // Reload purchasable to ensure we have fresh data
                    $variant = ProductVariant::with('taxClass')->find($purchasable->id);
                    
                    if (!$variant) {
                        continue;
                    }
                    
                    // Check if variant has tax class
                    $needsFix = false;
                    
                    if (!$variant->tax_class_id) {
                        $needsFix = true;
                        \Log::warning('Cart line variant missing tax_class_id', [
                            'line_id' => $line->id,
                            'variant_id' => $variant->id
                        ]);
                    } elseif (!$variant->relationLoaded('taxClass') || !$variant->taxClass) {
                        // Tax class ID exists but relationship is null
                        $needsFix = true;
                        \Log::warning('Cart line variant tax class relationship is null', [
                            'line_id' => $line->id,
                            'variant_id' => $variant->id,
                            'tax_class_id' => $variant->tax_class_id
                        ]);
                    } elseif (!$variant->taxClass->id) {
                        // Tax class exists but has no ID (shouldn't happen but be safe)
                        $needsFix = true;
                        \Log::warning('Cart line variant tax class has no ID', [
                            'line_id' => $line->id,
                            'variant_id' => $variant->id,
                            'tax_class_id' => $variant->tax_class_id
                        ]);
                    }
                    
                    if ($needsFix) {
                        // Assign default tax class
                        $variant->tax_class_id = $defaultTaxClass->id;
                        $variant->save();
                        $fixed = true;
                        
                        \Log::info('Fixed cart line variant tax class', [
                            'line_id' => $line->id,
                            'variant_id' => $variant->id,
                            'tax_class_id' => $defaultTaxClass->id
                        ]);
                    }
                }
            }
            
            // If we fixed anything, clear any cached cart data
            if ($fixed) {
                // Clear cart from cache/session to force reload
                // This ensures the next CartSession::current() gets fresh data
                cache()->forget('cart_' . $cartId);
            }
        } catch (\Exception $e) {
            // Don't let this break the flow, just log it
            \Log::warning('Failed to fix existing cart lines: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
