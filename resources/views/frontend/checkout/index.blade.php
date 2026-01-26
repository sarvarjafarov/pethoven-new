@extends('frontend.layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<section class="page-header-area" data-bg-img="{{ asset('brancy/images/photos/breadcrumb1.webp') }}">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-st3-content text-center">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Checkout</li>
                    </ol>
                    <h2 class="page-header-st3-title">Checkout</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Page Header Area ==-->

<!--== Start Checkout Area ==-->
<section class="section-space">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
            @csrf

            <!-- Coupon Section -->
            <div class="row mb-6">
                <div class="col-12">
                    <div class="checkout-coupon-wrapper">
                        <h5 class="mb-3">
                            <span class="text-decoration-underline cursor-pointer" id="toggle-coupon" style="cursor: pointer;">Have a Coupon?</span>
                            <span class="text-decoration-underline cursor-pointer" id="toggle-coupon-text">Click here to enter your code</span>
                        </h5>
                        <div id="coupon-form-wrapper" style="display: none;" class="mt-3">
                            <p>If you have a coupon code, please apply it below.</p>
                            <form action="#" method="post" class="coupon-form-wrapper">
                                @csrf
                                <div class="coupon-form d-flex gap-2">
                                    <input class="form-control" type="text" name="coupon" placeholder="Enter coupon code">
                                    <button type="submit" class="btn btn-theme">Apply coupon</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Billing Information -->
                    <div class="checkout-billing-details mb-8">
                        <h4 class="mb-5">Billing details</h4>

                        <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shipping_first_name" class="form-label">First name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('shipping_first_name') is-invalid @enderror" id="shipping_first_name" name="shipping_first_name" value="{{ old('shipping_first_name') }}" required>
                                        @error('shipping_first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shipping_last_name" class="form-label">Last name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('shipping_last_name') is-invalid @enderror" id="shipping_last_name" name="shipping_last_name" value="{{ old('shipping_last_name') }}" required>
                                        @error('shipping_last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="shipping_company" class="form-label">Company name (optional)</label>
                                        <input type="text" class="form-control @error('shipping_company') is-invalid @enderror" id="shipping_company" name="shipping_company" value="{{ old('shipping_company') }}">
                                        @error('shipping_company')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <div class="form-group">
                                        <label for="shipping_country_id" class="form-label">Country <span class="text-danger">*</span></label>
                                        <select class="form-select @error('shipping_country_id') is-invalid @enderror" id="shipping_country_id" name="shipping_country_id" required>
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('shipping_country_id') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('shipping_country_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="shipping_line_one" class="form-label">Street address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('shipping_line_one') is-invalid @enderror" id="shipping_line_one" name="shipping_line_one" value="{{ old('shipping_line_one') }}" placeholder="House number and street name" required>
                                        @error('shipping_line_one')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="shipping_line_two" class="form-label">Street address 2 <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('shipping_line_two') is-invalid @enderror" id="shipping_line_two" name="shipping_line_two" value="{{ old('shipping_line_two') }}" placeholder="Apartment, suite, unit, etc. (optional)">
                                        @error('shipping_line_two')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="shipping_city" class="form-label">Town / City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('shipping_city') is-invalid @enderror" id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}" required>
                                        @error('shipping_city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="shipping_state" class="form-label">District <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('shipping_state') is-invalid @enderror" id="shipping_state" name="shipping_state" value="{{ old('shipping_state') }}">
                                        @error('shipping_state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="shipping_postcode" class="form-label">Postcode / ZIP (optional)</label>
                                        <input type="text" class="form-control @error('shipping_postcode') is-invalid @enderror" id="shipping_postcode" name="shipping_postcode" value="{{ old('shipping_postcode') }}">
                                        @error('shipping_postcode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="shipping_phone" class="form-label">Phone (optional)</label>
                                        <input type="tel" class="form-control @error('shipping_phone') is-invalid @enderror" id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone') }}">
                                        @error('shipping_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="shipping_email" class="form-label">Email address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('shipping_email') is-invalid @enderror" id="shipping_email" name="shipping_email" value="{{ old('shipping_email') }}" required>
                                        @error('shipping_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                        </div>
                    </div>

                    <!-- Shipping Address (Different from billing) -->
                    <div class="checkout-billing-details mb-8">
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="billing_same_as_shipping" name="billing_same_as_shipping" value="1" {{ old('billing_same_as_shipping', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="billing_same_as_shipping">
                                Ship to a different address?
                            </label>
                        </div>

                        <div id="billing-address-fields" style="{{ old('billing_same_as_shipping', true) ? 'display: none;' : '' }}">
                            <h4 class="mb-5">Shipping Address</h4>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing_first_name" class="form-label">First name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('billing_first_name') is-invalid @enderror" id="billing_first_name" name="billing_first_name" value="{{ old('billing_first_name') }}">
                                        @error('billing_first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing_last_name" class="form-label">Last name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('billing_last_name') is-invalid @enderror" id="billing_last_name" name="billing_last_name" value="{{ old('billing_last_name') }}">
                                        @error('billing_last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="billing_company" class="form-label">Company name (optional)</label>
                                        <input type="text" class="form-control @error('billing_company') is-invalid @enderror" id="billing_company" name="billing_company" value="{{ old('billing_company') }}">
                                        @error('billing_company')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="billing_country_id" class="form-label">Country <span class="text-danger">*</span></label>
                                        <select class="form-select @error('billing_country_id') is-invalid @enderror" id="billing_country_id" name="billing_country_id">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('billing_country_id') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('billing_country_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="billing_line_one" class="form-label">Street address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('billing_line_one') is-invalid @enderror" id="billing_line_one" name="billing_line_one" value="{{ old('billing_line_one') }}" placeholder="House number and street name">
                                        @error('billing_line_one')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="billing_line_two" class="form-label">Street address 2 <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('billing_line_two') is-invalid @enderror" id="billing_line_two" name="billing_line_two" value="{{ old('billing_line_two') }}" placeholder="Apartment, suite, unit, etc. (optional)">
                                        @error('billing_line_two')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="billing_city" class="form-label">Town / City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('billing_city') is-invalid @enderror" id="billing_city" name="billing_city" value="{{ old('billing_city') }}">
                                        @error('billing_city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="billing_state" class="form-label">District <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('billing_state') is-invalid @enderror" id="billing_state" name="billing_state" value="{{ old('billing_state') }}">
                                        @error('billing_state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="billing_postcode" class="form-label">Postcode / ZIP (optional)</label>
                                        <input type="text" class="form-control @error('billing_postcode') is-invalid @enderror" id="billing_postcode" name="billing_postcode" value="{{ old('billing_postcode') }}">
                                        @error('billing_postcode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="checkout-order-notes">
                        <label for="notes" class="form-label">Order notes (optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Notes about your order, e.g. special notes for delivery">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Order Summary -->
                    <div class="checkout-order-summary">
                        <h4 class="mb-5">Your order</h4>

                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart->lines as $line)
                                    <tr>
                                        <td>
                                            {{ $line->description }} × {{ $line->quantity }}
                                        </td>
                                        <td class="text-end">{{ $line->subTotal->formatted }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Subtotal</th>
                                    <td class="text-end">{{ $cart->subTotal->formatted }}</td>
                                </tr>
                                <tr>
                                    <th>Shipping</th>
                                    <td class="text-end">
                                        @if($cart->shippingTotal && $cart->shippingTotal->value > 0)
                                            {{ $cart->shippingTotal->formatted }}
                                        @else
                                            <span class="text-muted">Free shipping</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($cart->taxTotal->value > 0)
                                    <tr>
                                        <th>Tax</th>
                                        <td class="text-end">{{ $cart->taxTotal->formatted }}</td>
                                    </tr>
                                @endif
                                <tr class="order-total">
                                    <th>Total</th>
                                    <td class="text-end"><strong>{{ $cart->total->formatted }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Payment Methods -->
                        <div class="payment-methods mt-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_stripe" value="stripe" checked>
                                <label class="form-check-label" for="payment_stripe">
                                    <strong>Credit/Debit Card</strong>
                                </label>
                                <p class="small text-muted mt-1 mb-0">Pay securely with your credit or debit card via Stripe.</p>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod">
                                <label class="form-check-label" for="payment_cod">
                                    <strong>Cash on delivery</strong>
                                </label>
                                <p class="small text-muted mt-1 mb-0">Pay with cash upon delivery.</p>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_direct_bank" value="direct_bank">
                                <label class="form-check-label" for="payment_direct_bank">
                                    <strong>Direct bank transfer</strong>
                                </label>
                                <p class="small text-muted mt-1 mb-0">Make your payment directly into our bank account. Please use your Order ID as the payment reference.</p>
                            </div>
                        </div>

                        <div class="form-check mt-4 mb-4">
                            <input class="form-check-input" type="checkbox" id="terms_agreement" name="terms_agreement" value="1" required>
                            <label class="form-check-label" for="terms_agreement">
                                I have read and agree to the website <a href="#" class="text-decoration-underline">terms and conditions</a> <span class="text-danger">*</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-theme w-100">
                            Place order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<!--== End Checkout Area ==-->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle coupon form
    $('#toggle-coupon, #toggle-coupon-text').on('click', function() {
        $('#coupon-form-wrapper').slideToggle();
    });

    // Toggle billing address fields
    $('#billing_same_as_shipping').on('change', function() {
        if ($(this).is(':checked')) {
            $('#billing-address-fields').slideUp();
        } else {
            $('#billing-address-fields').slideDown();
        }
    });
});
</script>
@endpush
