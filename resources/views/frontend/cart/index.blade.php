@extends('frontend.layouts.app')

@section('title', 'Shopping Cart - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">Shopping Cart</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>Shopping Cart</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area ==-->

<!--== Start Shopping Cart Area ==-->
<section class="section-space">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($cart && $cart->lines->count() > 0)
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopping-cart-table table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-subtotal">Subtotal</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart->lines as $line)
                                    @php
                                        $purchasable = $line->purchasable;
                                        $product = $purchasable->product ?? null;
                                        $image = $product?->thumbnail?->getUrl('small') ?? asset('brancy/images/shop/1.webp');
                                    @endphp
                                    <tr data-line-id="{{ $line->id }}">
                                        <td class="product-name">
                                            <div class="d-flex align-items-center">
                                                <div class="product-thumb me-3">
                                                    <img src="{{ $image }}" alt="{{ $line->description }}" style="width: 80px; height: 80px; object-fit: cover;">
                                                </div>
                                                <div class="product-info">
                                                    <h5 class="mb-1">{{ $line->description }}</h5>
                                                    @if($purchasable->sku)
                                                        <small class="text-muted">SKU: {{ $purchasable->sku }}</small>
                                                    @endif
                                                    @if($purchasable->values && $purchasable->values->count() > 0)
                                                        <div class="mt-1">
                                                            @foreach($purchasable->values as $value)
                                                                <small class="text-muted">{{ $value->option->name ?? '' }}: {{ $value->name ?? '' }}</small><br>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="product-price">
                                            <span class="amount">{{ $line->unitPrice->formatted }}</span>
                                        </td>
                                        <td class="product-quantity">
                                            <div class="pro-qty" style="width: 120px;">
                                                <button type="button" class="dec qtybtn" data-line-id="{{ $line->id }}">-</button>
                                                <input type="text" class="quantity-input" value="{{ $line->quantity }}" readonly>
                                                <button type="button" class="inc qtybtn" data-line-id="{{ $line->id }}">+</button>
                                            </div>
                                        </td>
                                        <td class="product-subtotal">
                                            <span class="amount line-total">{{ $line->subTotal->formatted }}</span>
                                        </td>
                                        <td class="product-remove">
                                            <button type="button" class="btn btn-link text-danger remove-item" data-line-id="{{ $line->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="cart-actions mt-4">
                        <div class="row justify-content-between">
                            <div class="col-auto">
                                <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">
                                    <i class="fa fa-angle-left me-2"></i>Continue Shopping
                                </a>
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('cart.clear') }}" method="POST" class="d-inline" id="clear-cart-form">
                                    @csrf
                                    <button type="button" class="btn btn-outline-danger" id="clear-cart-btn">
                                        <i class="fa fa-trash me-2"></i>Clear Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-totals-wrapper mt-lg-0 mt-8">
                        <h4 class="mb-4">Cart Totals</h4>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Subtotal</th>
                                    <td id="cart-subtotal">{{ $cart->subTotal->formatted }}</td>
                                </tr>
                                @if($cart->taxTotal->value > 0)
                                    <tr>
                                        <th>Tax</th>
                                        <td id="cart-tax">{{ $cart->taxTotal->formatted }}</td>
                                    </tr>
                                @endif
                                @if($cart->discountTotal && $cart->discountTotal->value > 0)
                                    <tr>
                                        <th>Discount</th>
                                        <td class="text-success" id="cart-discount">-{{ $cart->discountTotal->formatted }}</td>
                                    </tr>
                                @endif
                                <tr class="order-total">
                                    <th>Total</th>
                                    <td id="cart-total"><strong>{{ $cart->total->formatted }}</strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="cart-checkout-btn mt-4">
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100">
                                Proceed to Checkout<i class="fa fa-angle-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-10">
                        <i class="fa fa-shopping-cart fa-4x mb-4 text-muted"></i>
                        <h4>Your cart is empty</h4>
                        <p class="text-muted">You have no items in your shopping cart.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary mt-4">
                            <i class="fa fa-shopping-bag me-2"></i>Start Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
<!--== End Shopping Cart Area ==-->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update quantity
    $('.qtybtn').on('click', function() {
        const $btn = $(this);
        const lineId = $btn.data('line-id');
        const $row = $btn.closest('tr');
        const $input = $row.find('.quantity-input');
        let qty = parseInt($input.val()) || 1;

        if ($btn.hasClass('inc')) {
            qty++;
        } else if ($btn.hasClass('dec') && qty > 1) {
            qty--;
        } else {
            return;
        }

        $input.val(qty);

        // Send AJAX request to update cart
        $.ajax({
            url: '/cart/' + lineId,
            method: 'PUT',
            data: {
                quantity: qty,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Reload page to update totals
                    location.reload();
                }
            },
            error: function() {
                alert('Failed to update cart');
                location.reload();
            }
        });
    });

    // Remove item
    $('.remove-item').on('click', function() {
        if (!confirm('Are you sure you want to remove this item?')) {
            return;
        }

        const lineId = $(this).data('line-id');

        $.ajax({
            url: '/cart/' + lineId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function() {
                alert('Failed to remove item');
            }
        });
    });

    // Clear cart
    $('#clear-cart-btn').on('click', function() {
        if (confirm('Are you sure you want to clear your entire cart?')) {
            $('#clear-cart-form').submit();
        }
    });
});
</script>
@endpush
