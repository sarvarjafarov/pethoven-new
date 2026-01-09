@extends('frontend.layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">Checkout</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li><a href="{{ route('cart.index') }}">Cart</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>Checkout</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
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

            <div class="row">
                <div class="col-lg-8">
                    <!-- Shipping Information -->
                    <div class="checkout-billing-details mb-8">
                        <h4 class="mb-5">Shipping Information</h4>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shipping_first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('shipping_first_name') is-invalid @enderror" id="shipping_first_name" name="shipping_first_name" value="{{ old('shipping_first_name') }}" required>
                                    @error('shipping_first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shipping_last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('shipping_last_name') is-invalid @enderror" id="shipping_last_name" name="shipping_last_name" value="{{ old('shipping_last_name') }}" required>
                                    @error('shipping_last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shipping_email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('shipping_email') is-invalid @enderror" id="shipping_email" name="shipping_email" value="{{ old('shipping_email') }}" required>
                                    @error('shipping_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shipping_phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control @error('shipping_phone') is-invalid @enderror" id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone') }}">
                                    @error('shipping_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="shipping_line_one" class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('shipping_line_one') is-invalid @enderror" id="shipping_line_one" name="shipping_line_one" value="{{ old('shipping_line_one') }}" required>
                                    @error('shipping_line_one')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="shipping_line_two" class="form-label">Address Line 2</label>
                                    <input type="text" class="form-control @error('shipping_line_two') is-invalid @enderror" id="shipping_line_two" name="shipping_line_two" value="{{ old('shipping_line_two') }}">
                                    @error('shipping_line_two')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shipping_city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('shipping_city') is-invalid @enderror" id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}" required>
                                    @error('shipping_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shipping_state" class="form-label">State/Province</label>
                                    <input type="text" class="form-control @error('shipping_state') is-invalid @enderror" id="shipping_state" name="shipping_state" value="{{ old('shipping_state') }}">
                                    @error('shipping_state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shipping_postcode" class="form-label">Postcode/ZIP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('shipping_postcode') is-invalid @enderror" id="shipping_postcode" name="shipping_postcode" value="{{ old('shipping_postcode') }}" required>
                                    @error('shipping_postcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
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
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <div class="checkout-billing-details mb-8">
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="billing_same_as_shipping" name="billing_same_as_shipping" value="1" {{ old('billing_same_as_shipping', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="billing_same_as_shipping">
                                Billing address same as shipping
                            </label>
                        </div>

                        <div id="billing-address-fields" style="{{ old('billing_same_as_shipping', true) ? 'display: none;' : '' }}">
                            <h4 class="mb-5">Billing Information</h4>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing_first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('billing_first_name') is-invalid @enderror" id="billing_first_name" name="billing_first_name" value="{{ old('billing_first_name') }}">
                                        @error('billing_first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing_last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('billing_last_name') is-invalid @enderror" id="billing_last_name" name="billing_last_name" value="{{ old('billing_last_name') }}">
                                        @error('billing_last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="billing_line_one" class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('billing_line_one') is-invalid @enderror" id="billing_line_one" name="billing_line_one" value="{{ old('billing_line_one') }}">
                                        @error('billing_line_one')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="billing_line_two" class="form-label">Address Line 2</label>
                                        <input type="text" class="form-control @error('billing_line_two') is-invalid @enderror" id="billing_line_two" name="billing_line_two" value="{{ old('billing_line_two') }}">
                                        @error('billing_line_two')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing_city" class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('billing_city') is-invalid @enderror" id="billing_city" name="billing_city" value="{{ old('billing_city') }}">
                                        @error('billing_city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing_state" class="form-label">State/Province</label>
                                        <input type="text" class="form-control @error('billing_state') is-invalid @enderror" id="billing_state" name="billing_state" value="{{ old('billing_state') }}">
                                        @error('billing_state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing_postcode" class="form-label">Postcode/ZIP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('billing_postcode') is-invalid @enderror" id="billing_postcode" name="billing_postcode" value="{{ old('billing_postcode') }}">
                                        @error('billing_postcode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                            </div>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="checkout-order-notes">
                        <label for="notes" class="form-label">Order Notes (optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Notes about your order, e.g. special notes for delivery">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Order Summary -->
                    <div class="checkout-order-summary">
                        <h4 class="mb-5">Order Summary</h4>

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

                        <input type="hidden" name="payment_method" value="stripe">

                        <button type="submit" class="btn btn-primary w-100 mt-4">
                            Proceed to Payment <i class="fa fa-angle-right ms-2"></i>
                        </button>

                        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary w-100 mt-3">
                            <i class="fa fa-angle-left me-2"></i> Return to Cart
                        </a>
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
