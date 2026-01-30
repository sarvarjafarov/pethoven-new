<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\Facades\CartSession;
use Lunar\Models\Country;
use Lunar\Models\Cart;
use Lunar\Models\Order;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page
     */
    public function index()
    {
        $cart = CartSession::current();

        if (!$cart || $cart->lines->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        $countries = Country::orderBy('name')->get();

        $cart->loadMissing([
            'billingAddress.country',
            'shippingAddress.country',
        ]);

        return view('frontend.checkout.index', compact('cart', 'countries'));
    }

    /**
     * Process the checkout
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'shipping_first_name' => 'required|string|max:255',
            'shipping_last_name' => 'required|string|max:255',
            'shipping_email' => 'required|email',
            'shipping_phone' => 'nullable|string|max:20',
            'shipping_line_one' => 'required|string|max:255',
            'shipping_line_two' => 'nullable|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_postcode' => 'nullable|string|max:20',
            'shipping_country_id' => 'required|exists:lunar_countries,id',
            'billing_same_as_shipping' => 'boolean',
            'billing_first_name' => 'required_if:billing_same_as_shipping,false|string|max:255',
            'billing_last_name' => 'required_if:billing_same_as_shipping,false|string|max:255',
            'billing_line_one' => 'required_if:billing_same_as_shipping,false|string|max:255',
            'billing_line_two' => 'nullable|string|max:255',
            'billing_city' => 'required_if:billing_same_as_shipping,false|string|max:255',
            'billing_state' => 'nullable|string|max:255',
            'billing_postcode' => 'required_if:billing_same_as_shipping,false|string|max:20',
            'billing_country_id' => 'required_if:billing_same_as_shipping,false|exists:lunar_countries,id',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:stripe,cod,direct_bank',
        ]);

        $cart = CartSession::current();

        if (!$cart || $cart->lines->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        // Set shipping address
        $cart->setShippingAddress([
            'first_name' => $validated['shipping_first_name'],
            'last_name' => $validated['shipping_last_name'],
            'line_one' => $validated['shipping_line_one'],
            'line_two' => $validated['shipping_line_two'] ?? null,
            'city' => $validated['shipping_city'],
            'state' => $validated['shipping_state'] ?? null,
            'postcode' => $validated['shipping_postcode'],
            'country_id' => $validated['shipping_country_id'],
            'contact_email' => $validated['shipping_email'],
            'contact_phone' => $validated['shipping_phone'] ?? null,
        ]);

        // Set billing address
        if ($request->boolean('billing_same_as_shipping')) {
            $cart->setBillingAddress([
                'first_name' => $validated['shipping_first_name'],
                'last_name' => $validated['shipping_last_name'],
                'line_one' => $validated['shipping_line_one'],
                'line_two' => $validated['shipping_line_two'] ?? null,
                'city' => $validated['shipping_city'],
                'state' => $validated['shipping_state'] ?? null,
                'postcode' => $validated['shipping_postcode'],
                'country_id' => $validated['shipping_country_id'],
            ]);
        } else {
            $cart->setBillingAddress([
                'first_name' => $validated['billing_first_name'],
                'last_name' => $validated['billing_last_name'],
                'line_one' => $validated['billing_line_one'],
                'line_two' => $validated['billing_line_two'] ?? null,
                'city' => $validated['billing_city'],
                'state' => $validated['billing_state'] ?? null,
                'postcode' => $validated['billing_postcode'],
                'country_id' => $validated['billing_country_id'],
            ]);
        }

        // Add notes if provided
        if ($validated['notes']) {
            $cart->meta = array_merge($cart->meta ?? [], [
                'notes' => $validated['notes']
            ]);
            $cart->save();
        }

        // Store cart ID and payment method in session for payment processing
        session(['checkout_cart_id' => $cart->id]);
        session(['checkout_payment_method' => $validated['payment_method']]);

        // Handle non-Stripe payment methods (COD, Direct Bank Transfer)
        if (in_array($validated['payment_method'], ['cod', 'direct_bank'])) {
            // Ensure cart totals are calculated before creating order
            $cart->calculate();
            $order = $cart->createOrder();

            // Update order status based on payment method
            $status = $validated['payment_method'] === 'cod' ? 'awaiting-payment' : 'pending-payment';
            $order->update([
                'status' => $status,
                'placed_at' => now(),
            ]);

            // Record the payment method in order meta
            $order->meta = array_merge($order->meta ?? [], [
                'payment_method' => $validated['payment_method'],
            ]);
            $order->save();

            // Store order ID in session for guest access verification
            session(['last_order_id' => $order->id]);

            // Clear the cart
            CartSession::forget();
            session()->forget('checkout_cart_id');
            session()->forget('checkout_payment_method');

            return redirect()->route('checkout.success', ['order' => $order->id]);
        }

        // Redirect to Stripe payment page
        return redirect()->route('checkout.payment');
    }

    /**
     * Show payment page
     */
    public function payment()
    {
        $cartId = session('checkout_cart_id');

        if (!$cartId) {
            return redirect()->route('checkout.index')
                ->with('error', 'Please complete the checkout form');
        }

        $cart = Cart::find($cartId);

        if (!$cart || $cart->lines->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        // Get Stripe publishable key from config
        $stripeKey = config('services.stripe.key');

        return view('frontend.checkout.payment', compact('cart', 'stripeKey'));
    }

    /**
     * Process payment with Stripe
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required_without:payment_intent_id|string',
            'payment_intent_id' => 'nullable|string',
        ]);

        $cartId = session('checkout_cart_id');

        if (!$cartId) {
            return response()->json([
                'success' => false,
                'message' => 'Checkout session expired'
            ], 400);
        }

        $cart = Cart::find($cartId);

        if (!$cart || $cart->lines->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            // If payment_intent_id is provided, confirm existing intent (after 3D Secure)
            if ($request->payment_intent_id) {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($request->payment_intent_id);
                if ($paymentIntent->status === 'requires_confirmation') {
                    $paymentIntent->confirm();
                }
            } else {
                // Create new payment intent
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => $cart->total->value, // Amount in cents
                    'currency' => strtolower($cart->currency->code),
                    'payment_method' => $request->payment_method_id,
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                    'return_url' => route('checkout.payment'), // Return to payment page if 3D Secure required
                    'metadata' => [
                        'cart_id' => $cart->id,
                    ]
                ]);
            }

            // Handle different payment intent statuses
            if ($paymentIntent->status === 'succeeded') {
                // Ensure cart totals are calculated before creating order
                $cart->calculate();
                $order = $cart->createOrder();

                // Update order with payment info
                $order->update([
                    'status' => 'payment-received',
                    'placed_at' => now(),
                ]);

                $order->transactions()->create([
                    'success' => true,
                    'type' => 'capture',
                    'driver' => 'stripe',
                    'amount' => $cart->total->value,
                    'reference' => $paymentIntent->id,
                    'status' => 'settled',
                    'notes' => 'Payment processed via Stripe',
                    'card_type' => $paymentIntent->charges->data[0]->payment_method_details->card->brand ?? null,
                    'last_four' => $paymentIntent->charges->data[0]->payment_method_details->card->last4 ?? null,
                ]);

                // Store order ID in session for guest access verification
                session(['last_order_id' => $order->id]);

                // Clear the cart
                CartSession::forget();
                session()->forget('checkout_cart_id');

                return response()->json([
                    'success' => true,
                    'redirect' => route('checkout.success', ['order' => $order->id])
                ]);
            } elseif ($paymentIntent->status === 'requires_action') {
                // 3D Secure authentication required
                return response()->json([
                    'success' => true,
                    'requires_action' => true,
                    'client_secret' => $paymentIntent->client_secret
                ]);
            } elseif ($paymentIntent->status === 'requires_payment_method') {
                // Payment method was declined
                return response()->json([
                    'success' => false,
                    'message' => 'Your card was declined. Please try a different payment method.'
                ], 400);
            }

            // Other statuses (processing, requires_capture, etc.)
            return response()->json([
                'success' => false,
                'message' => 'Payment is being processed. Please check back later.'
            ], 400);

        } catch (\Stripe\Exception\CardException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred processing your payment'
            ], 500);
        }
    }

    /**
     * Show order success page
     */
    public function success($orderId)
    {
        $order = Order::with([
            'lines.purchasable.product',
            'shippingAddress',
            'billingAddress',
            'transactions'
        ])->findOrFail($orderId);

        // Verify this order belongs to the current user
        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // For guest orders, verify via session
        if (!auth()->check() && session('last_order_id') !== $order->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        return view('frontend.checkout.success', compact('order'));
    }
}
