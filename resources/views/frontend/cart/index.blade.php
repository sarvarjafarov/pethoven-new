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
                        <li class="breadcrumb-item active text-dark" aria-current="page">Cart</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Page Header Area ==-->

<!--== Start Shopping Cart Area ==-->
<section class="section-space">
    <div class="container">
        <!-- Layout derived from Brancy product-cart template -->
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
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">PRODUCT</th>
                                    <th class="product-price">PRICE</th>
                                    <th class="product-quantity">QUANTITY</th>
                                    <th class="product-subtotal">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart->lines as $line)
                                    @php
                                        $purchasable = $line->purchasable;
                                        $product = $purchasable->product ?? null;
                                        // Use template CDN image with fallback
                                        $imageIndex = ($product ? ($product->id % 6) : 0) + 1;
                                        $image = $product?->thumbnail?->getUrl('small') ?? "https://template.hasthemes.com/brancy/brancy/assets/images/shop/{$imageIndex}.webp";
                                        $productUrl = $product ? route('shop.product.show', $product->defaultUrl?->slug ?? $product->id) : '#';
                                    @endphp
                                    <tr class="tbody-item" data-line-id="{{ $line->id }}">
                                        <td class="product-remove">
                                            <a href="javascript:void(0)" class="remove" data-line-id="{{ $line->id }}">×</a>
                                        </td>
                                        <td class="product-thumbnail">
                                            <a href="{{ $productUrl }}">
                                                <img src="{{ $image }}" width="80" height="90" alt="{{ $line->description }}" onerror="this.src='{{ asset('brancy/images/shop/' . $imageIndex . '.webp') }}'">
                                            </a>
                                        </td>
                                        <td class="product-name">
                                            @php
                                                // Get product name with fallback
                                                $productName = $line->description;
                                                if (empty($productName) && $product) {
                                                    $productName = $product->translateAttribute('name') ?? $product->attr('name') ?? 'Product';
                                                }
                                            @endphp
                                            <h5 class="title"><a href="{{ $productUrl }}">{{ $productName }}</a></h5>
                                            @if($purchasable && $purchasable->values && $purchasable->values->count() > 0)
                                                @foreach($purchasable->values as $value)
                                                    <small class="text-muted">{{ $value->option->name ?? '' }}: {{ $value->name ?? '' }}</small>@if(!$loop->last)<br>@endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="product-price">
                                            <span class="price">{{ $line->unitPrice->formatted }}</span>
                                        </td>
                                        <td class="product-quantity">
                                            <div class="pro-qty">
                                                <input type="text" class="quantity-input" value="{{ $line->quantity }}" data-line-id="{{ $line->id }}" readonly>
                                            </div>
                                        </td>
                                        <td class="product-subtotal">
                                            <span class="price line-total">{{ $line->subTotal->formatted }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="shopping-cart-footer">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <div class="shopping-cart-btn text-end">
                                    <button type="button" class="btn-update-cart" id="update-cart-btn">Update Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="coupon-wrap mt-6">
                        <h4 class="title">COUPON</h4>
                        <p class="desc">Enter your coupon code if you have one.</p>
                        <form action="#" method="post" id="coupon-form">
                            @csrf
                            <input class="form-control" type="text" name="coupon" placeholder="Coupon code">
                            <button type="submit" class="btn-coupon">APPLY COUPON</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="cart-totals-wrap mt-6">
                        <h4 class="title">CART TOTALS</h4>
                        <table>
                            <tbody>
                                <tr>
                                    <th>SUBTOTAL</th>
                                    <td class="amount" id="cart-subtotal">{{ $cart->subTotal->formatted }}</td>
                                </tr>
                                <tr>
                                    <th>SHIPPING</th>
                                    <td>
                                        <ul class="shipping-list">
                                            <li>
                                                <input type="radio" name="shipping_method" id="shipping_flat_rate" value="flat_rate" {{ ($cart->shippingTotal && $cart->shippingTotal->value > 0) ? 'checked' : '' }}>
                                                <label for="shipping_flat_rate">
                                                    Flat rate: @if($cart->shippingTotal && $cart->shippingTotal->value > 0)
                                                        {{ $cart->shippingTotal->formatted }}
                                                    @else
                                                        $3.00
                                                    @endif
                                                </label>
                                            </li>
                                            <li>
                                                <input type="radio" name="shipping_method" id="shipping_free" value="free" {{ (!$cart->shippingTotal || $cart->shippingTotal->value == 0) ? 'checked' : '' }}>
                                                <label for="shipping_free">
                                                    Free shipping
                                                </label>
                                            </li>
                                            <li>
                                                <input type="radio" name="shipping_method" id="shipping_local" value="local">
                                                <label for="shipping_local">
                                                    Local pickup
                                                </label>
                                            </li>
                                        </ul>
                                        <p class="destination">
                                            Shipping to <strong>USA</strong>.
                                        </p>
                                        <a href="javascript:void(0)" class="btn-shipping-address">Change address</a>
                                    </td>
                                </tr>
                                <tr id="shipping-cost-row" style="display: none;">
                                    <th>SHIPPING COST</th>
                                    <td class="amount" id="shipping-cost-amount">$0.00</td>
                                </tr>
                                @if($cart->taxTotal && $cart->taxTotal->value > 0)
                                    <tr>
                                        <th>TAX</th>
                                        <td class="amount" id="cart-tax">{{ $cart->taxTotal->formatted }}</td>
                                    </tr>
                                @endif
                                @if($cart->discountTotal && $cart->discountTotal->value > 0)
                                    <tr>
                                        <th>DISCOUNT</th>
                                        <td class="amount" style="color: #FF6565;" id="cart-discount">-{{ $cart->discountTotal->formatted }}</td>
                                    </tr>
                                @endif
                                <tr class="order-total">
                                    <th><strong>TOTAL</strong></th>
                                    <td class="amount"><strong id="cart-total">{{ $cart->total->formatted }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-end">
                            <a class="checkout-button" href="{{ route('checkout.index') }}">PROCEED TO CHECKOUT</a>
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

@push('styles')
<style>
/* Cart table styling */
.shopping-cart-form table {
    width: 100%;
    table-layout: fixed;
}
.shopping-cart-form table th,
.shopping-cart-form table td {
    vertical-align: middle;
    padding: 15px 10px;
}
.shopping-cart-form table th.product-remove,
.shopping-cart-form table td.product-remove {
    width: 50px;
    text-align: center;
}
.shopping-cart-form table th.product-thumbnail,
.shopping-cart-form table td.product-thumbnail {
    width: 100px;
}
.shopping-cart-form table td.product-thumbnail img {
    max-width: 80px;
    height: auto;
}
.shopping-cart-form table th.product-name,
.shopping-cart-form table td.product-name {
    width: auto;
    min-width: 200px;
    text-align: left;
}
.shopping-cart-form table td.product-name h5.title {
    margin: 0;
    font-size: 16px;
    font-weight: 500;
}
.shopping-cart-form table td.product-name h5.title a {
    color: #1c1c1c;
    text-decoration: none;
}
.shopping-cart-form table td.product-name h5.title a:hover {
    color: #457B9D;
}
.shopping-cart-form table th.product-price,
.shopping-cart-form table td.product-price {
    width: 120px;
}
.shopping-cart-form table th.product-quantity,
.shopping-cart-form table td.product-quantity {
    width: 150px;
}
.shopping-cart-form table th.product-subtotal,
.shopping-cart-form table td.product-subtotal {
    width: 120px;
}
.shopping-cart-form .product-remove .remove {
    font-size: 24px;
    color: #999;
    text-decoration: none;
}
.shopping-cart-form .product-remove .remove:hover {
    color: #ff6565;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Override Brancy's quantity handler for cart page to use AJAX
    // Brancy's main.js adds the buttons, we intercept the clicks
    $(document).off('click', '.pro-qty .qty-btn').on('click', '.pro-qty .qty-btn', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const $proQty = $btn.closest('.pro-qty');
        const $input = $proQty.find('.quantity-input');
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

    // Shipping method selection - update total dynamically
    var cartSubtotal = {{ $cart->subTotal->value ?? 0 }};
    var cartTax = {{ $cart->taxTotal->value ?? 0 }};
    var currencySymbol = '{{ $cart->currency->code === "USD" ? "$" : $cart->currency->code }}';

    // Shipping costs in cents
    var shippingCosts = {
        'flat_rate': 300, // $3.00
        'free': 0,
        'local': 0
    };

    function formatMoney(cents) {
        return currencySymbol + (cents / 100).toFixed(2);
    }

    function updateCartTotal() {
        var selectedShipping = $('input[name="shipping_method"]:checked').val();
        var shippingCost = shippingCosts[selectedShipping] || 0;
        var newTotal = cartSubtotal + cartTax + shippingCost;

        // Update shipping display
        if (shippingCost > 0) {
            $('#shipping-cost-row').show();
            $('#shipping-cost-amount').text(formatMoney(shippingCost));
        } else {
            $('#shipping-cost-row').hide();
        }

        // Update total
        $('#cart-total').text(formatMoney(newTotal));

        // Store selected shipping in session via AJAX
        $.post('/cart/shipping', {
            shipping_method: selectedShipping,
            _token: '{{ csrf_token() }}'
        }).fail(function() {
            // Silently fail - shipping will be set at checkout
        });
    }

    // Bind shipping method change
    $('input[name="shipping_method"]').on('change', function() {
        updateCartTotal();
    });

    // Initialize on page load
    updateCartTotal();
});
</script>
@endpush
