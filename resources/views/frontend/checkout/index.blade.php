@extends('frontend.layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area Wrapper ==-->
<nav aria-label="breadcrumb" class="breadcrumb-style1">
    <div class="container">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </div>
</nav>
<!--== End Page Header Area Wrapper ==-->

<!--== Start Shopping Checkout Area Wrapper ==-->
<section class="shopping-checkout-wrap section-space">
    <div class="container">
        <div class="checkout-page-coupon-wrap">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!--== Start Checkout Coupon Accordion ==-->
            <div class="coupon-accordion" id="CouponAccordion">
                <div class="card">
                    <h3>
                        <i class="fa fa-info-circle"></i>
                        Have a Coupon?
                        <a href="#/" data-bs-toggle="collapse" data-bs-target="#couponaccordion">Click here to enter your code</a>
                    </h3>
                    <div id="couponaccordion" class="collapse" data-bs-parent="#CouponAccordion">
                        <div class="card-body">
                            <div class="apply-coupon-wrap">
                                <p>If you have a coupon code, please apply it below.</p>
                                <form action="#" method="post">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input class="form-control" type="text" placeholder="Coupon code">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn-coupon">Apply coupon</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--== End Checkout Coupon Accordion ==-->
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" novalidate>
            @csrf
            @php
                $billingAddress = $cart->billingAddress;
                $shippingAddress = $cart->shippingAddress;
                $billingCountryId = old('billing_country_id', $billingAddress?->country_id);
                $shippingCountryId = old('shipping_country_id', $shippingAddress?->country_id ?? $billingCountryId);
                $shipToDifferentAddress = old('ship_to_different_address', $shippingAddress ? '1' : '0');
            @endphp
            <div class="row">
                <div class="col-lg-6">
                    <!--== Start Billing Accordion ==-->
                    <div class="checkout-billing-details-wrap">
                        <h2 class="title">Billing details</h2>
                        <div class="billing-form-wrap">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing_first_name">First name <abbr class="required" title="required">*</abbr></label>
                                        <input id="billing_first_name" name="billing_first_name" type="text" class="form-control @error('billing_first_name') is-invalid @enderror" value="{{ old('billing_first_name') }}" required>
                                        @error('billing_first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing_last_name">Last name <abbr class="required" title="required">*</abbr></label>
                                        <input id="billing_last_name" name="billing_last_name" type="text" class="form-control @error('billing_last_name') is-invalid @enderror" value="{{ old('billing_last_name') }}" required>
                                        @error('billing_last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="billing_company">Company name (optional)</label>
                                        <input id="billing_company" name="billing_company" type="text" class="form-control" value="{{ old('billing_company') }}">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <div class="form-group">
                                        <label for="billing_country_id">Country <abbr class="required" title="required">*</abbr></label>
                                        <select id="billing_country_id" name="billing_country_id" class="form-control wide @error('billing_country_id') is-invalid @enderror" required>
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ $billingCountryId == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('billing_country_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="billing_line_one">Street address <abbr class="required" title="required">*</abbr></label>
                                        <input id="billing_line_one" name="billing_line_one" type="text" class="form-control @error('billing_line_one') is-invalid @enderror" placeholder="House number and street name" value="{{ old('billing_line_one') }}" required>
                                        @error('billing_line_one')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="billing_line_two" class="visually-hidden">Street address 2 <abbr class="required" title="required">*</abbr></label>
                                        <input id="billing_line_two" name="billing_line_two" type="text" class="form-control" placeholder="Apartment, suite, unit etc. (optional)" value="{{ old('billing_line_two') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="billing_city">Town / City <abbr class="required" title="required">*</abbr></label>
                                        <input id="billing_city" name="billing_city" type="text" class="form-control @error('billing_city') is-invalid @enderror" value="{{ old('billing_city') }}" required>
                                        @error('billing_city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <div class="form-group">
                                        <label for="billing_state">District <abbr class="required" title="required">*</abbr></label>
                                        <select id="billing_state" name="billing_state" class="form-control wide @error('billing_state') is-invalid @enderror" required>
                                            <option value="">Select District</option>
                                            <option value="District 1" {{ old('billing_state') == 'District 1' ? 'selected' : '' }}>District 1</option>
                                            <option value="District 2" {{ old('billing_state') == 'District 2' ? 'selected' : '' }}>District 2</option>
                                            <option value="District 3" {{ old('billing_state') == 'District 3' ? 'selected' : '' }}>District 3</option>
                                        </select>
                                        @error('billing_state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="billing_postcode">Postcode / ZIP (optional)</label>
                                        <input id="billing_postcode" name="billing_postcode" type="text" class="form-control" value="{{ old('billing_postcode') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="billing_phone">Phone (optional)</label>
                                        <input id="billing_phone" name="billing_phone" type="text" class="form-control" value="{{ old('billing_phone') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="billing_email">Email address <abbr class="required" title="required">*</abbr></label>
                                        <input id="billing_email" name="billing_email" type="text" class="form-control @error('billing_email') is-invalid @enderror" value="{{ old('billing_email') }}" required>
                                        @error('billing_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div id="CheckoutBillingAccordion2" class="col-md-12">
                                <div class="checkout-box" data-bs-toggle="collapse" data-bs-target="#CheckoutTwo" aria-expanded="{{ $shipToDifferentAddress === '1' ? 'true' : 'false' }}" role="toolbar">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input visually-hidden" id="ship-to-different-address" name="ship_to_different_address" value="1" {{ $shipToDifferentAddress === '1' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="ship-to-different-address">Ship to a different address?</label>
                                        </div>
                                    </div>
                                    <div id="CheckoutTwo" class="collapse {{ $shipToDifferentAddress === '1' ? 'show' : '' }}" data-bs-parent="#CheckoutBillingAccordion2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="shipping_first_name">First name <abbr class="required" title="required">*</abbr></label>
                                                    <input id="shipping_first_name" name="shipping_first_name" type="text" class="form-control @error('shipping_first_name') is-invalid @enderror" value="{{ old('shipping_first_name') }}">
                                                    @error('shipping_first_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="shipping_last_name">Last name <abbr class="required" title="required">*</abbr></label>
                                                    <input id="shipping_last_name" name="shipping_last_name" type="text" class="form-control @error('shipping_last_name') is-invalid @enderror" value="{{ old('shipping_last_name') }}">
                                                    @error('shipping_last_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="shipping_company">Company name (optional)</label>
                                                    <input id="shipping_company" name="shipping_company" type="text" class="form-control @error('shipping_company') is-invalid @enderror" value="{{ old('shipping_company') }}">
                                                    @error('shipping_company')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <div class="form-group">
                                                    <label for="shipping_country_id">Country <abbr class="required" title="required">*</abbr></label>
                                                    <select id="shipping_country_id" name="shipping_country_id" class="form-control wide @error('shipping_country_id') is-invalid @enderror">
                                                        <option value="">Select Country</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->id }}" {{ $shippingCountryId == $country->id ? 'selected' : '' }}>
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('shipping_country_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="shipping_line_one">Street address <abbr class="required" title="required">*</abbr></label>
                                                    <input id="shipping_line_one" name="shipping_line_one" type="text" class="form-control @error('shipping_line_one') is-invalid @enderror" placeholder="House number and street name" value="{{ old('shipping_line_one') }}">
                                                    @error('shipping_line_one')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="shipping_line_two" class="visually-hidden">Street address 2 <abbr class="required" title="required">*</abbr></label>
                                                    <input id="shipping_line_two" name="shipping_line_two" type="text" class="form-control @error('shipping_line_two') is-invalid @enderror" placeholder="Apartment, suite, unit etc. (optional)" value="{{ old('shipping_line_two') }}">
                                                    @error('shipping_line_two')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="shipping_city">Town / City <abbr class="required" title="required">*</abbr></label>
                                                    <input id="shipping_city" name="shipping_city" type="text" class="form-control @error('shipping_city') is-invalid @enderror" value="{{ old('shipping_city') }}">
                                                    @error('shipping_city')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <div class="form-group">
                                                    <label for="shipping_state">District <abbr class="required" title="required">*</abbr></label>
                                                    <select id="shipping_state" name="shipping_state" class="form-control wide @error('shipping_state') is-invalid @enderror">
                                                        <option value="">Select District</option>
                                                        <option value="District 1" {{ old('shipping_state') == 'District 1' ? 'selected' : '' }}>District 1</option>
                                                        <option value="District 2" {{ old('shipping_state') == 'District 2' ? 'selected' : '' }}>District 2</option>
                                                        <option value="District 3" {{ old('shipping_state') == 'District 3' ? 'selected' : '' }}>District 3</option>
                                                    </select>
                                                    @error('shipping_state')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="shipping_postcode">Postcode / ZIP (optional)</label>
                                                    <input id="shipping_postcode" name="shipping_postcode" type="text" class="form-control @error('shipping_postcode') is-invalid @enderror" value="{{ old('shipping_postcode') }}">
                                                    @error('shipping_postcode')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label for="notes">Order notes (optional)</label>
                                        <textarea id="notes" name="notes" class="form-control" placeholder="Notes about your order, e.g. special notes for delivery.">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--== End Billing Accordion ==-->
                </div>
                <div class="col-lg-6">
                    <!--== Start Order Details Accordion ==-->
                    <div class="checkout-order-details-wrap">
                        <div class="order-details-table-wrap table-responsive">
                            <h2 class="title mb-25">Your order</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="product-name">Product</th>
                                        <th class="product-total">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    @foreach($cart->lines as $line)
                                        <tr class="cart-item">
                                            <td class="product-name">{{ $line->description }} <span class="product-quantity">× {{ $line->quantity }}</span></td>
                                            <td class="product-total">{{ $line->subTotal->formatted }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-foot">
                                    <tr class="cart-subtotal">
                                        <th>Subtotal</th>
                                        <td>{{ $cart->subTotal->formatted }}</td>
                                    </tr>
                                    <tr class="shipping">
                                        <th>Shipping</th>
                                        <td>
                                            @if($cart->shippingTotal && $cart->shippingTotal->value > 0)
                                                Flat rate: {{ $cart->shippingTotal->formatted }}
                                            @else
                                                Free shipping
                                            @endif
                                        </td>
                                    </tr>
                                    @if($cart->taxTotal && $cart->taxTotal->value > 0)
                                        <tr class="tax">
                                            <th>Tax</th>
                                            <td>{{ $cart->taxTotal->formatted }}</td>
                                        </tr>
                                    @endif
                                    @if($cart->discountTotal && $cart->discountTotal->value > 0)
                                        <tr class="discount">
                                            <th>Discount</th>
                                            <td style="color: #FF6565;">-{{ $cart->discountTotal->formatted }}</td>
                                        </tr>
                                    @endif
                                    <tr class="order-total">
                                        <th>Total</th>
                                        <td>{{ $cart->total->formatted }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="shop-payment-method">
                                <div id="PaymentMethodAccordion">
                                    <input type="hidden" name="payment_method" id="payment_method_input" value="bank_transfer">
                                    <div class="card">
                                        <div class="card-header" id="check_payments">
                                            <h5 class="title" data-bs-toggle="collapse" data-bs-target="#itemOne" aria-controls="itemOne" aria-expanded="true">Direct bank transfer</h5>
                                        </div>
                                        <div id="itemOne" class="collapse show" aria-labelledby="check_payments" data-bs-parent="#PaymentMethodAccordion">
                                            <div class="card-body">
                                                <p>Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="check_payments2">
                                            <h5 class="title" data-bs-toggle="collapse" data-bs-target="#itemTwo" aria-controls="itemTwo" aria-expanded="false">Check payments</h5>
                                        </div>
                                        <div id="itemTwo" class="collapse" aria-labelledby="check_payments2" data-bs-parent="#PaymentMethodAccordion">
                                            <div class="card-body">
                                                <p>Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="check_payments3">
                                            <h5 class="title" data-bs-toggle="collapse" data-bs-target="#itemThree" aria-controls="itemThree" aria-expanded="false">Cash on delivery</h5>
                                        </div>
                                        <div id="itemThree" class="collapse" aria-labelledby="check_payments3" data-bs-parent="#PaymentMethodAccordion">
                                            <div class="card-body">
                                                <p>Pay with cash upon delivery.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="check_payments4">
                                            <h5 class="title" data-bs-toggle="collapse" data-bs-target="#itemFour" aria-controls="itemFour" aria-expanded="false">PayPal Express Checkout</h5>
                                        </div>
                                        <div id="itemFour" class="collapse" aria-labelledby="check_payments4" data-bs-parent="#PaymentMethodAccordion">
                                            <div class="card-body">
                                                <p>Pay via PayPal; you can pay with your credit card if you don't have a PayPal account.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="p-text">Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="#/">privacy policy.</a></p>
                                <div class="agree-policy">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="privacy" class="custom-control-input visually-hidden" required>
                                        <label for="privacy" class="custom-control-label">I have read and agree to the website terms and conditions <span class="required">*</span></label>
                                    </div>
                                </div>
                                <button type="submit" class="btn-place-order">Place order</button>
                            </div>
                        </div>
                    </div>
                    <!--== End Order Details Accordion ==-->
                </div>
            </div>
        </form>
    </div>
</section>
<!--== End Shopping Checkout Area Wrapper ==-->
@endsection

@push('styles')
<style>
    /* Title styling to match template */
    .checkout-billing-details-wrap .title,
    .shopping-checkout-wrap .title {
        font-size: 20px;
        position: relative;
        padding-bottom: 12px;
        margin-bottom: 35px;
        font-weight: 600;
        text-transform: capitalize;
        color: #1D3557; /* Dark blue to match template */
    }
    .checkout-billing-details-wrap .title:before,
    .shopping-checkout-wrap .title:before {
        background-color: #000000;
        bottom: 0;
        content: "";
        height: 2px;
        left: 0;
        position: absolute;
        width: 50px;
    }
    
    /* Fix for Nice Select Validation Styling */
    .form-control.is-invalid + .nice-select {
        border-color: #dc3545; /* Bootstrap danger color */
    }
    .form-control.is-invalid + .nice-select:after {
        border-right: 2px solid #dc3545;
        border-bottom: 2px solid #dc3545;
    }
    
    /* Form group and label styling to match template */
    .billing-form-wrap .form-group,
    .billing-form-wrap form .form-group {
        margin-bottom: 20px;
    }
    .billing-form-wrap .form-group label,
    .billing-form-wrap form .form-group label {
        font-size: 14px;
        color: #000000;
        margin-bottom: 8px;
        display: block;
    }
    .billing-form-wrap .form-group label .required,
    .billing-form-wrap form .form-group label .required {
        color: #16A34A;
    }
    
    /* Ensure billing form inputs match template exactly - using !important to override Bootstrap */
    .billing-form-wrap .form-group .form-control,
    .billing-form-wrap .form-group input.form-control,
    .billing-form-wrap .form-group select.form-control,
    .billing-form-wrap .form-group textarea.form-control,
    .billing-form-wrap form .form-group .form-control,
    .billing-form-wrap form .form-group input.form-control,
    .billing-form-wrap form .form-group select.form-control,
    .billing-form-wrap form .form-group textarea.form-control {
        border-radius: 4px !important;
        box-shadow: none !important;
        border: 1px solid #ebeef5 !important;
        color: #1c1c1c !important;
        font-size: 16px !important;
        font-weight: 400 !important;
        height: 70px !important;
        line-height: 70px !important;
        background-color: #fff !important;
        padding: 0 24px !important;
        outline: none !important;
        box-sizing: border-box !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
    }
    
    .billing-form-wrap .form-group .form-control:active,
    .billing-form-wrap .form-group .form-control:focus,
    .billing-form-wrap .form-group input.form-control:active,
    .billing-form-wrap .form-group input.form-control:focus,
    .billing-form-wrap .form-group select.form-control:active,
    .billing-form-wrap .form-group select.form-control:focus,
    .billing-form-wrap form .form-group .form-control:active,
    .billing-form-wrap form .form-group .form-control:focus,
    .billing-form-wrap form .form-group input.form-control:active,
    .billing-form-wrap form .form-group input.form-control:focus,
    .billing-form-wrap form .form-group select.form-control:active,
    .billing-form-wrap form .form-group select.form-control:focus {
        border-color: #a6b1c2 !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07) !important;
        outline: none !important;
        background-color: #fff !important;
    }
    
    .billing-form-wrap .form-group .form-control::placeholder,
    .billing-form-wrap .form-group input.form-control::placeholder,
    .billing-form-wrap form .form-group .form-control::placeholder,
    .billing-form-wrap form .form-group input.form-control::placeholder {
        color: #626262 !important;
        opacity: 1 !important;
    }
    .billing-form-wrap .form-group .form-control::-webkit-input-placeholder,
    .billing-form-wrap .form-group input.form-control::-webkit-input-placeholder,
    .billing-form-wrap form .form-group .form-control::-webkit-input-placeholder,
    .billing-form-wrap form .form-group input.form-control::-webkit-input-placeholder {
        color: #626262 !important;
        opacity: 1 !important;
    }
    .billing-form-wrap .form-group .form-control:-moz-placeholder,
    .billing-form-wrap .form-group input.form-control:-moz-placeholder,
    .billing-form-wrap form .form-group .form-control:-moz-placeholder,
    .billing-form-wrap form .form-group input.form-control:-moz-placeholder {
        color: #626262 !important;
        opacity: 1 !important;
    }
    .billing-form-wrap .form-group .form-control::-moz-placeholder,
    .billing-form-wrap .form-group input.form-control::-moz-placeholder,
    .billing-form-wrap form .form-group .form-control::-moz-placeholder,
    .billing-form-wrap form .form-group input.form-control::-moz-placeholder {
        color: #626262 !important;
        opacity: 1 !important;
    }
    .billing-form-wrap .form-group .form-control:-ms-input-placeholder,
    .billing-form-wrap .form-group input.form-control:-ms-input-placeholder,
    .billing-form-wrap form .form-group .form-control:-ms-input-placeholder,
    .billing-form-wrap form .form-group input.form-control:-ms-input-placeholder {
        color: #626262 !important;
        opacity: 1 !important;
    }
    
    .billing-form-wrap .form-group textarea.form-control,
    .billing-form-wrap form .form-group textarea.form-control {
        min-height: 120px !important;
        background-color: #fff !important;
        border: 1px solid #e8e8e8 !important;
        padding: 20px !important;
        line-height: 1.3 !important;
        height: auto !important;
    }
    
    /* Nice-select styling to match inputs exactly */
    .billing-form-wrap .form-group .nice-select,
    .billing-form-wrap form .form-group .nice-select {
        border-radius: 4px !important;
        border: 1px solid #ebeef5 !important;
        height: 70px !important;
        line-height: 70px !important;
        padding: 0 24px !important;
        float: none !important;
        width: 100% !important;
        margin-bottom: 0 !important;
        box-shadow: none !important;
        background-color: #fff !important;
        box-sizing: border-box !important;
    }
    .billing-form-wrap .form-group .nice-select:focus,
    .billing-form-wrap form .form-group .nice-select:focus,
    .billing-form-wrap .form-group .nice-select.open,
    .billing-form-wrap form .form-group .nice-select.open {
        border-color: #a6b1c2 !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07) !important;
    }
    .billing-form-wrap .form-group .nice-select:after,
    .billing-form-wrap form .form-group .nice-select:after {
        right: 24px !important;
        margin: 0 !important;
    }

    /* Adjust nice-select internal list */
    .nice-select .list {
        width: 100%;
        border-radius: 0;
        margin-top: 1px;
    }

    /* Order Details styling overrides if they are missing from app.css */
    .checkout-order-details-wrap {
        margin-left: 40px;
    }
    @media only screen and (min-width: 992px) and (max-width: 1199px), 
           only screen and (min-width: 768px) and (max-width: 991px), 
           only screen and (max-width: 767px) {
        .checkout-order-details-wrap {
            margin-left: 0;
            margin-top: 40px;
        }
    }
    .order-details-table-wrap {
        border: 2px solid rgba(52, 53, 56, 0.1);
        padding: 48px 50px 54px;
        position: relative;
        margin-bottom: 50px;
    }
    @media only screen and (min-width: 768px) and (max-width: 991px), 
           only screen and (max-width: 767px) {
        .order-details-table-wrap {
            padding: 20px 20px 20px;
            margin-top: 40px;
            margin-bottom: 0;
        }
    }
    .order-details-table-wrap .title {
        font-size: 20px;
        position: relative;
        padding-bottom: 12px;
        margin-bottom: 35px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .order-details-table-wrap .title:before {
        background-color: #000000;
        bottom: 0;
        content: "";
        height: 2px;
        left: 0;
        position: absolute;
        width: 50px;
    }
    .order-details-table-wrap .table th,
    .order-details-table-wrap .table td {
         color: #000000;
         font-weight: 400;
         font-size: 14px;
         vertical-align: middle;
         padding: 8px 0;
         border: none;
         border-bottom: 1px solid #e8e8e8;
    }
    .order-details-table-wrap .table th {
        border-bottom: 1px solid #e8e8e8 !important;
    }
    .product-total {
        text-align: right;
    }
    
    /* Place Order Button */
    .btn-place-order {
        display: block;
        text-align: center;
        width: 100%;
        border-radius: 0;
        padding: 18px 20px 16px;
        margin-top: 32px;
        font-size: 16px;
        background-color: #FF6565;
        border: 1px solid #FF6565;
        color: #fff;
        text-transform: uppercase;
        font-weight: 600;
        transition: all 0.3s ease-out;
    }
    .btn-place-order:hover {
        background-color: #000000;
        border-color: #000000;
        color: #fff;
    }

    /* Nice-select dropdown styling */
    .billing-form-wrap form .form-group .nice-select .current {
        font-size: 16px;
        color: #1c1c1c;
        font-weight: 400;
    }
    
    .billing-form-wrap form .form-group .nice-select .option.selected {
        font-weight: 400;
        background-color: #fff;
    }
    
    .billing-form-wrap form .form-group .nice-select .option:hover {
        background-color: #f7f7f7;
    }
    
    .billing-form-wrap form .form-group .nice-select.open .current {
        font-weight: 400;
    }
    
    /* Error message styling tweak */
    .invalid-feedback {
        display: block; /* Force display when present */
        margin-top: 5px;
        font-size: 0.875em;
        color: #dc3545;
    }
    
    /* mb-25 class for order details title */
    .mb-25 {
        margin-bottom: 25px;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle coupon form
    $('#toggle-coupon, #toggle-coupon-text').on('click', function() {
        $('#coupon-form-wrapper').slideToggle();
    });

    // Update payment method hidden input when accordion changes
    $('#PaymentMethodAccordion .collapse').on('show.bs.collapse', function () {
        // Find the trigger that controls this collapse
        var targetId = '#' + $(this).attr('id');
        var trigger = $('[data-bs-target="' + targetId + '"]');
        var titleText = trigger.text().trim().toLowerCase();
        var value = 'bank_transfer'; // default
        
        // Determine payment method from title text
        if(titleText.includes('direct bank transfer')) {
            value = 'bank_transfer';
        } else if(titleText.includes('check payment')) {
            value = 'check_payment';
        } else if(titleText.includes('cash on delivery')) {
            value = 'cod';
        } else if(titleText.includes('paypal') || titleText.includes('stripe')) {
            value = 'stripe';
        }
        
        $('#payment_method_input').val(value);
        // Change button text based on payment method
        if(value === 'stripe') {
            $('.btn-place-order').text('Continue to Payment');
        } else {
            $('.btn-place-order').text('Place order');
        }
    });

    // Ensure default state matches logic
    var defaultOpen = $('#PaymentMethodAccordion .collapse.show');
    if(defaultOpen.length) {
         var targetId = '#' + defaultOpen.attr('id');
         var trigger = $('[data-bs-target="' + targetId + '"]');
         var titleText = trigger.text().trim().toLowerCase();
         var value = 'bank_transfer'; // default
         
         if(titleText.includes('direct bank transfer')) {
             value = 'bank_transfer';
         } else if(titleText.includes('check payment')) {
             value = 'check_payment';
         } else if(titleText.includes('cash on delivery')) {
             value = 'cod';
         } else if(titleText.includes('paypal') || titleText.includes('stripe')) {
             value = 'stripe';
         }
         
         $('#payment_method_input').val(value);
    }

    // Fix for nice-select not updating the underlying select value properly
    // This ensures that when a user selects an option in the nice-select dropdown,
    // the original (hidden) select element handles the change event correctly.
    $(document).on('click.nice_select', '.nice-select .option', function(e) {
        var $option = $(this);
        var $dropdown = $option.closest('.nice-select');
        var $select = $dropdown.prev('select');
        
        if ($select.length > 0) {
            var value = $option.data('value');
            
            // Only trigger if value actually changed
            if ($select.val() !== value) {
                $select.val(value).trigger('change');
            }
        }
    });

    // FINAL SAFETY NET: Force sync all nice-select dropdowns on form submit
    // This catches any case where the click handler might have been missed
    $('#checkout-form').on('submit', function() {
        $('.nice-select').each(function() {
            var $dropdown = $(this);
            var $select = $dropdown.prev('select');
            
            // Find the selected option in the visual dropdown
            var $selectedOption = $dropdown.find('.option.selected');
            var selectedValue = $selectedOption.data('value');
            
            // Force update the native select if valid
            if ($select.length > 0 && selectedValue !== undefined) {
                 $select.val(selectedValue);
                 console.log('Synced select ' + $select.attr('id') + ' with value: ' + selectedValue);
            }
        });
    });

    // Handle shipping address toggle checkbox
    // Sync checkbox state with Bootstrap collapse state
    $('#ship-to-different-address').on('change', function() {
        var isChecked = $(this).is(':checked');
        var $collapse = $('#CheckoutTwo');
        
        if (isChecked) {
            $collapse.collapse('show');
        } else {
            $collapse.collapse('hide');
        }
    });
    
    // Sync collapse state with checkbox when collapse is toggled via Bootstrap
    $('#CheckoutTwo').on('show.bs.collapse', function() {
        $('#ship-to-different-address').prop('checked', true);
    });
    
    $('#CheckoutTwo').on('hide.bs.collapse', function() {
        $('#ship-to-different-address').prop('checked', false);
    });
    
    // Initialize nice-select for all selects in checkout form (if not already initialized)
    // This ensures selects are properly initialized even if main.js hasn't run yet
    setTimeout(function() {
        $('#checkout-form select:not(.no-nice-select)').each(function() {
            if (!$(this).next('.nice-select').length) {
                $(this).niceSelect();
            }
        });
    }, 100);
    
    // Ensure form validation works with nice-select
    $('#checkout-form').on('submit', function(e) {
        var isValid = true;
        
        // Check all required fields
        $('#checkout-form [required]').each(function() {
            var $field = $(this);
            var $niceSelect = $field.next('.nice-select');
            
            // Handle nice-select fields
            if ($niceSelect.length) {
                var value = $field.val();
                if (!value || value === '') {
                    isValid = false;
                    $niceSelect.addClass('is-invalid');
                    $field.addClass('is-invalid');
                } else {
                    $niceSelect.removeClass('is-invalid');
                    $field.removeClass('is-invalid');
                }
            }
            // Handle regular inputs
            else if (!$field.val() || $field.val() === '') {
                isValid = false;
                $field.addClass('is-invalid');
            } else {
                $field.removeClass('is-invalid');
            }
        });
        
        // If validation fails, prevent submission and show errors
        if (!isValid) {
            e.preventDefault();
            // Scroll to first invalid field
            var $firstInvalid = $('#checkout-form .is-invalid').first();
            if ($firstInvalid.length) {
                $('html, body').animate({
                    scrollTop: $firstInvalid.offset().top - 100
                }, 500);
            }
            return false;
        }
    });

    // Debugging: Log initialization
    console.log('Checkout script version 4.0 loaded');
});
</script>
@endpush
