@extends('frontend.layouts.app')

@section('title', 'Shopping Cart - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area Wrapper ==-->
<nav aria-label="breadcrumb" class="breadcrumb-style1">
    <div class="container">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cart</li>
        </ol>
    </div>
</nav>
<!--== End Page Header Area Wrapper ==-->

<!--== Start Product Area Wrapper ==-->
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
            <div class="shopping-cart-form table-responsive">
                <form action="#" method="post">
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
                                    $imageIndex = ($product ? ($product->id % 6) : 0) + 1;
                                    $image = $product?->thumbnail?->getUrl('small') ?? "https://template.hasthemes.com/brancy/brancy/assets/images/shop/{$imageIndex}.webp";
                                    $productUrl = $product ? route('shop.product.show', $product->defaultUrl?->slug ?? $product->id) : '#';
                                @endphp
                                <tr class="tbody-item" data-line-id="{{ $line->id }}">
                                    <td class="product-remove">
                                        <a class="remove" href="javascript:void(0)" data-line-id="{{ $line->id }}">×</a>
                                    </td>
                                    <td class="product-thumbnail">
                                        <div class="thumb">
                                            <a href="{{ $productUrl }}">
                                                <img src="{{ $image }}" width="68" height="84" alt="{{ $line->description }}" onerror="this.src='{{ asset('brancy/images/shop/' . $imageIndex . '.webp') }}'">
                                            </a>
                                        </div>
                                    </td>
                                    <td class="product-name">
                                        <a class="title" href="{{ $productUrl }}">{{ $line->description }}</a>
                                        @if($purchasable->values && $purchasable->values->count() > 0)
                                            <br>
                                            @foreach($purchasable->values as $value)
                                                <small class="text-muted">{{ $value->option->name ?? '' }}: {{ $value->name ?? '' }}</small>@if(!$loop->last), @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="product-price">
                                        <span class="price">{{ $line->unitPrice->formatted }}</span>
                                    </td>
                                    <td class="product-quantity">
                                        <div class="pro-qty">
                                            <input type="text" class="quantity" title="Quantity" value="{{ $line->quantity }}" data-line-id="{{ $line->id }}" readonly>
                                        </div>
                                    </td>
                                    <td class="product-subtotal">
                                        <span class="price line-total">{{ $line->subTotal->formatted }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="tbody-item-actions">
                                <td colspan="6">
                                    <button type="button" class="btn-update-cart" id="update-cart-btn">Update cart</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="coupon-wrap">
                        <h4 class="title">Coupon</h4>
                        <p class="desc">Enter your coupon code if you have one.</p>
                        <input type="text" class="form-control" placeholder="Coupon code">
                        <button type="button" class="btn-coupon">Apply coupon</button>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="cart-totals-wrap">
                        <h2 class="title">Cart totals</h2>
                        <table>
                            <tbody>
                                <tr class="cart-subtotal">
                                    <th>Subtotal</th>
                                    <td>
                                        <span class="amount" id="cart-subtotal">{{ $cart->subTotal->formatted }}</span>
                                    </td>
                                </tr>
                                <tr class="shipping-totals">
                                    <th>Shipping</th>
                                    <td>
                                        <ul class="shipping-list">
                                            <li class="radio">
                                                <input type="radio" name="shipping_method" id="shipping_flat_rate" value="flat_rate" {{ ($cart->shippingTotal && $cart->shippingTotal->value > 0) ? 'checked' : '' }}>
                                                <label for="shipping_flat_rate">Flat rate: <span>@if($cart->shippingTotal && $cart->shippingTotal->value > 0)
                                                    {{ $cart->shippingTotal->formatted }}
                                                @else
                                                    $3.00
                                                @endif</span></label>
                                            </li>
                                            <li class="radio">
                                                <input type="radio" name="shipping_method" id="shipping_free" value="free" {{ (!$cart->shippingTotal || $cart->shippingTotal->value == 0) ? 'checked' : '' }}>
                                                <label for="shipping_free">Free shipping</label>
                                            </li>
                                            <li class="radio">
                                                <input type="radio" name="shipping_method" id="shipping_local" value="local">
                                                <label for="shipping_local">Local pickup</label>
                                            </li>
                                        </ul>
                                        <p class="destination">Shipping to <strong>USA</strong>.</p>
                                        <a href="javascript:void(0)" class="btn-shipping-address">Change address</a>
                                    </td>
                                </tr>
                                @if($cart->taxTotal && $cart->taxTotal->value > 0)
                                    <tr>
                                        <th>Tax</th>
                                        <td class="amount" id="cart-tax">{{ $cart->taxTotal->formatted }}</td>
                                    </tr>
                                @endif
                                @if($cart->discountTotal && $cart->discountTotal->value > 0)
                                    <tr>
                                        <th>Discount</th>
                                        <td class="amount" style="color: #FF6565;" id="cart-discount">-{{ $cart->discountTotal->formatted }}</td>
                                    </tr>
                                @endif
                                <tr class="order-total">
                                    <th>Total</th>
                                    <td>
                                        <span class="amount" id="cart-total">{{ $cart->total->formatted }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-end">
                            <a href="{{ route('checkout.index') }}" class="checkout-button">Proceed to checkout</a>
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
<!--== End Product Area Wrapper ==-->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Override Brancy's quantity handler for cart page to use AJAX
    // Brancy's main.js adds the buttons, we intercept the clicks
    $(document).off('click', '.pro-qty .qty-btn').on('click', '.pro-qty .qty-btn', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const $proQty = $btn.closest('.pro-qty');
        const $input = $proQty.find('input');
        const lineId = $input.data('line-id');
        const $row = $btn.closest('tr');
        
        if (!lineId) {
            // Fallback to Brancy's default behavior for non-cart pages
            var oldValue = $input.val();
            if ($btn.hasClass('inc')) {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                if (oldValue > 1) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 1;
                }
            }
            $input.val(newVal);
            return;
        }

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
    $(document).on('click', '.product-remove .remove', function(e) {
        e.preventDefault();
        
        if (!confirm('Are you sure you want to remove this item?')) {
            return;
        }

        const lineId = $(this).data('line-id');
        const $row = $(this).closest('tr');

        $.ajax({
            url: '/cart/' + lineId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Failed to remove item');
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Failed to remove item');
                location.reload();
            }
        });
    });

    // Update cart button (just refreshes to show updated quantities)
    $('#update-cart-btn').on('click', function() {
        location.reload();
    });
});
</script>
@endpush
