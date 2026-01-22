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
            // Billing is primary in the form
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name' => 'required|string|max:255',
            'billing_email' => 'required|email',
            'billing_phone' => 'nullable|string|max:20',
            'billing_company' => 'nullable|string|max:255',
            'billing_line_one' => 'required|string|max:255',
            'billing_line_two' => 'nullable|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_postcode' => 'nullable|string|max:20',
            'billing_country_id' => 'required|exists:lunar_countries,id',

            // Shipping toggle
            'ship_to_different_address' => 'nullable', 

            // Shipping fields required if toggle is on
            'shipping_first_name' => 'required_if:ship_to_different_address,1|nullable|string|max:255',
            'shipping_last_name' => 'required_if:ship_to_different_address,1|nullable|string|max:255',
            'shipping_company' => 'nullable|string|max:255',
            'shipping_line_one' => 'required_if:ship_to_different_address,1|nullable|string|max:255',
            'shipping_line_two' => 'nullable|string|max:255',
            'shipping_city' => 'required_if:ship_to_different_address,1|nullable|string|max:255',
            'shipping_state' => 'required_if:ship_to_different_address,1|nullable|string|max:255',
            'shipping_postcode' => 'nullable|string|max:20',
            'shipping_country_id' => 'required_if:ship_to_different_address,1|nullable|exists:lunar_countries,id',

            'notes' => 'nullable|string',
            'payment_method' => 'required',
        ]);

        $cart = CartSession::current();

        if (!$cart || $cart->lines->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        // Determine Shipping Address
        $shippingAddress = [];
        if ($request->input('ship_to_different_address')) {
            $shippingAddress = [
                'first_name' => $validated['shipping_first_name'],
                'last_name' => $validated['shipping_last_name'],
                'company_name' => $validated['shipping_company'] ?? null,
                'line_one' => $validated['shipping_line_one'],
                'line_two' => $validated['shipping_line_two'] ?? null,
                'city' => $validated['shipping_city'],
                'state' => $validated['shipping_state'] ?? null,
                'postcode' => $validated['shipping_postcode'],
                'country_id' => $validated['shipping_country_id'],
                // Use billing email/phone for contact info if specific shipping contact fields don't exist
                'contact_email' => $validated['billing_email'],
                'contact_phone' => $validated['billing_phone'] ?? null,
            ];
        } else {
            $shippingAddress = [
                'first_name' => $validated['billing_first_name'],
                'last_name' => $validated['billing_last_name'],
                'company_name' => $validated['billing_company'] ?? null,
                'line_one' => $validated['billing_line_one'],
                'line_two' => $validated['billing_line_two'] ?? null,
                'city' => $validated['billing_city'],
                'state' => $validated['billing_state'] ?? null,
                'postcode' => $validated['billing_postcode'],
                'country_id' => $validated['billing_country_id'],
                'contact_email' => $validated['billing_email'],
                'contact_phone' => $validated['billing_phone'] ?? null,
            ];
        }

        $cart->setShippingAddress($shippingAddress);

        // Billing Address is always from the Billing fields
        $cart->setBillingAddress([
            'first_name' => $validated['billing_first_name'],
            'last_name' => $validated['billing_last_name'],
            'company_name' => $validated['billing_company'] ?? null,
            'line_one' => $validated['billing_line_one'],
            'line_two' => $validated['billing_line_two'] ?? null,
            'city' => $validated['billing_city'],
            'state' => $validated['billing_state'] ?? null,
            'postcode' => $validated['billing_postcode'],
            'country_id' => $validated['billing_country_id'],
        ]);

        // Create order directly for offline methods
        if ($validated['payment_method'] !== 'stripe') {
            try {
                // Create order from cart
                $order = $cart->createOrder();
                
                // Update order status/meta based on payment method
                $paymentMethod = $validated['payment_method'];
                $notes = 'Payment method: ' . ucfirst(str_replace('_', ' ', $paymentMethod));
                
                $order->update([
                    'status' => 'order-placed',
                    'placed_at' => now(),
                    'notes' => $notes 
                ]);
                
                // Create a transaction record for the offline payment
                $order->transactions()->create([
                    'success' => true, // Pending offline payment validation
                    'type' => 'capture',
                    'driver' => 'offline', 
                    'amount' => $cart->total->value,
                    'reference' => 'OFFLINE-' . uniqid(),
                    'status' => 'pending', // Pending actual payment
                    'notes' => $notes,
                    'card_type' => $paymentMethod
                ]);

                // Clear the cart
                CartSession::forget();
                session()->forget('checkout_cart_id');
                
                return redirect()->route('checkout.success', ['order' => $order->id]);
            } catch (\Exception $e) {
                return back()->with('error', 'There was a problem placing your order: ' . $e->getMessage());
            }
        }

        // Add notes if provided
        if ($validated['notes']) {
            $cart->meta = array_merge($cart->meta ?? [], [
                'notes' => $validated['notes']
            ]);
            $cart->save();
        }

        // Store cart ID in session for payment processing
        session(['checkout_cart_id' => $cart->id]);

        // Redirect to payment
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
                $paymentIntent->confirm();
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
                // Create order from cart
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

        // Verify this order belongs to current user (if logged in)
        // For now, allow anyone with the order ID to view it

        return view('frontend.checkout.success', compact('order'));
    }
}
