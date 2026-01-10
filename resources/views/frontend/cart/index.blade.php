@extends('frontend.layouts.app')

@section('title', 'Shopping Cart - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<section class="page-header-area" data-bg-img="{{ asset('brancy/images/photos/breadcrumb1.webp') }}">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-st3-content text-center">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Shopping Cart</li>
                    </ol>
                    <h2 class="page-header-st3-title">Shopping Cart</h2>
                </div>
            </div>
        </div>
    </div>
</section>
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
                <div class="col-lg-12">
                    <div class="shopping-cart-form table-responsive">
                        <form action="#" method="post">
                            @csrf
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th class="product-remove">&nbsp;</th>
                                        <th class="product-thumbnail">&nbsp;</th>
                                        <th class="product-name">Product</th>
                                        <th class="product-price">Price</th>
                                        <th class="product-quantity">Quantity</th>
                                        <th class="product-subtotal">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->lines as $line)
                                        @php
                                            $purchasable = $line->purchasable;
                                            $product = $purchasable->product ?? null;
                                            $image = $product?->thumbnail?->getUrl('small') ?? asset('brancy/images/shop/1.webp');
                                            $productUrl = $product ? route('shop.product.show', $product->defaultUrl?->slug ?? $product->id) : '#';
                                        @endphp
                                        <tr data-line-id="{{ $line->id }}">
                                            <td class="product-remove">
                                                <button type="button" class="btn-close remove-item" data-line-id="{{ $line->id }}" aria-label="Close">×</button>
                                            </td>
                                            <td class="product-thumbnail">
                                                <a class="d-block" href="{{ $productUrl }}">
                                                    <img src="{{ $image }}" width="80" height="90" alt="{{ $line->description }}">
                                                </a>
                                            </td>
                                            <td class="product-name">
                                                <h5 class="title"><a href="{{ $productUrl }}">{{ $line->description }}</a></h5>
                                                @if($purchasable->values && $purchasable->values->count() > 0)
                                                    <div class="mt-1">
                                                        @foreach($purchasable->values as $value)
                                                            <small class="text-muted">{{ $value->option->name ?? '' }}: {{ $value->name ?? '' }}</small><br>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="product-price">
                                                <span class="price">{{ $line->unitPrice->formatted }}</span>
                                            </td>
                                            <td class="product-quantity">
                                                <div class="pro-qty">
                                                    <button type="button" class="dec qtybtn" data-line-id="{{ $line->id }}">-</button>
                                                    <input type="text" class="quantity-input" value="{{ $line->quantity }}" readonly>
                                                    <button type="button" class="inc qtybtn" data-line-id="{{ $line->id }}">+</button>
                                                </div>
                                            </td>
                                            <td class="product-subtotal">
                                                <span class="price line-total">{{ $line->subTotal->formatted }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <div class="shopping-cart-footer">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <div class="shopping-cart-btn">
                                    <a class="btn btn-outline-dark" href="{{ route('shop.index') }}">Continue Shopping</a>
                                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline" id="clear-cart-form">
                                        @csrf
                                        <button type="button" class="btn btn-outline-danger" id="clear-cart-btn">Clear Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="shopping-cart-coupon mt-6">
                        <h4 class="title">Coupon Code</h4>
                        <p>Enter your coupon code if you have one.</p>
                        <form action="#" method="post">
                            @csrf
                            <div class="coupon-form">
                                <input class="form-control" type="text" name="coupon" placeholder="Enter coupon code">
                                <button type="submit" class="btn">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shopping-cart-total mt-6">
                        <h4 class="title">Cart Totals</h4>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="amount" id="cart-subtotal">{{ $cart->subTotal->formatted }}</td>
                                </tr>
                                @if($cart->taxTotal->value > 0)
                                    <tr>
                                        <td>Tax</td>
                                        <td class="amount" id="cart-tax">{{ $cart->taxTotal->formatted }}</td>
                                    </tr>
                                @endif
                                @if($cart->discountTotal && $cart->discountTotal->value > 0)
                                    <tr>
                                        <td>Discount</td>
                                        <td class="amount text-success" id="cart-discount">-{{ $cart->discountTotal->formatted }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="total">Total</td>
                                    <td class="total-amount" id="cart-total">{{ $cart->total->formatted }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <a class="btn btn-theme d-block w-100" href="{{ route('checkout.index') }}">Proceed To Checkout</a>
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
