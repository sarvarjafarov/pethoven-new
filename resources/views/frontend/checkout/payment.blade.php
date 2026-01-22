@extends('frontend.layouts.app')

@section('title', 'Payment - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">Payment</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li><a href="{{ route('cart.index') }}">Cart</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li><a href="{{ route('checkout.index') }}">Checkout</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>Payment</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area ==-->

<!--== Start Payment Area ==-->
<section class="section-space">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="payment-wrapper">
                    <h4 class="mb-5">Payment Details</h4>

                    <div class="order-total-summary mb-6 p-4 bg-light rounded">
                        <h5 class="mb-3">Order Total</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-4">Amount to pay:</span>
                            <strong class="fs-3 text-primary">{{ $cart->total->formatted }}</strong>
                        </div>
                    </div>

                    <form id="payment-form">
                        <div class="mb-4">
                            <label for="card-element" class="form-label">Credit or Debit Card</label>
                            <div id="card-element" class="form-control" style="height: 40px; padding-top: 10px;"></div>
                            <div id="card-errors" class="text-danger mt-2" role="alert"></div>
                        </div>

                        <div id="payment-message" class="alert alert-danger" style="display: none;"></div>

                        <button type="submit" id="submit-payment" class="btn btn-primary w-100">
                            <span id="button-text">Pay {{ $cart->total->formatted }}</span>
                            <span id="spinner" class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true" style="display: none;"></span>
                        </button>

                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fa fa-lock me-1"></i>
                                Your payment information is secure and encrypted
                            </small>
                        </div>
                    </form>

                    <div class="mt-4">
                        <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fa fa-angle-left me-2"></i> Back to Checkout
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="order-summary-sidebar">
                    <h5 class="mb-4">Order Summary</h5>

                    @foreach($cart->lines as $line)
                        @php
                            $purchasable = $line->purchasable;
                            $product = $purchasable->product ?? null;
                            $image = $product?->thumbnail?->getUrl('small') ?? asset('brancy/images/shop/1.webp');
                        @endphp
                        <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                            <img src="{{ $image }}" alt="{{ $line->description }}" style="width: 60px; height: 60px; object-fit: cover;" class="me-3 rounded">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $line->description }}</h6>
                                <small class="text-muted">Quantity: {{ $line->quantity }}</small>
                            </div>
                            <div>
                                <strong>{{ $line->subTotal->formatted }}</strong>
                            </div>
                        </div>
                    @endforeach

                    <div class="order-totals">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>{{ $cart->subTotal->formatted }}</span>
                        </div>
                        @if($cart->taxTotal->value > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax</span>
                                <span>{{ $cart->taxTotal->formatted }}</span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between pt-3 border-top">
                            <strong>Total</strong>
                            <strong class="text-primary">{{ $cart->total->formatted }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Payment Area ==-->
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ $stripeKey }}');
    const elements = stripe.elements();

    // Custom styling for card element
    const style = {
        base: {
            color: '#32325d',
            fontFamily: '"Inter", sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    const cardElement = elements.create('card', {style: style});
    cardElement.mount('#card-element');

    // Handle card errors
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-payment');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        // Disable button and show spinner
        submitButton.disabled = true;
        spinner.style.display = 'inline-block';
        buttonText.textContent = 'Processing...';

        // Create payment method
        const {paymentMethod, error} = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        });

        if (error) {
            // Show error
            document.getElementById('payment-message').textContent = error.message;
            document.getElementById('payment-message').style.display = 'block';

            // Re-enable button
            submitButton.disabled = false;
            spinner.style.display = 'none';
            buttonText.textContent = 'Pay {{ $cart->total->formatted }}';
        } else {
            // Send payment method to server
            fetch('{{ route("checkout.process-payment") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_method_id: paymentMethod.id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Handle 3D Secure authentication if required
                    if (data.requires_action && data.client_secret) {
                        stripe.handleCardAction(data.client_secret).then(function(result) {
                            if (result.error) {
                                // Show error
                                document.getElementById('payment-message').textContent = result.error.message;
                                document.getElementById('payment-message').style.display = 'block';
                                
                                // Re-enable button
                                submitButton.disabled = false;
                                spinner.style.display = 'none';
                                buttonText.textContent = 'Pay {{ $cart->total->formatted }}';
                            } else {
                                // 3D Secure succeeded, confirm payment intent
                                fetch('{{ route("checkout.process-payment") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        payment_intent_id: result.paymentIntent.id
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        window.location.href = data.redirect;
                                    } else {
                                        document.getElementById('payment-message').textContent = data.message || 'Payment failed';
                                        document.getElementById('payment-message').style.display = 'block';
                                        submitButton.disabled = false;
                                        spinner.style.display = 'none';
                                        buttonText.textContent = 'Pay {{ $cart->total->formatted }}';
                                    }
                                });
                            }
                        });
                    } else {
                        // Redirect to success page
                        window.location.href = data.redirect;
                    }
                } else {
                    // Show error
                    document.getElementById('payment-message').textContent = data.message || 'Payment failed';
                    document.getElementById('payment-message').style.display = 'block';

                    // Re-enable button
                    submitButton.disabled = false;
                    spinner.style.display = 'none';
                    buttonText.textContent = 'Pay {{ $cart->total->formatted }}';
                }
            })
            .catch(error => {
                document.getElementById('payment-message').textContent = 'An error occurred. Please try again.';
                document.getElementById('payment-message').style.display = 'block';

                // Re-enable button
                submitButton.disabled = false;
                spinner.style.display = 'none';
                buttonText.textContent = 'Pay {{ $cart->total->formatted }}';
            });
        }
    });
</script>
@endpush
