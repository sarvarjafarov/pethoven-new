@extends('frontend.layouts.app')

@section('title', 'My Wishlist - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<section class="page-header-area" data-bg-img="{{ asset('brancy/images/photos/breadcrumb1.webp') }}">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-st3-content text-center">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Wishlist</li>
                    </ol>
                    <h2 class="page-header-st3-title">My Wishlist</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Page Header Area ==-->

<!--== Start Wishlist Area ==-->
<section class="section-space">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($products && $products->count() > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="shopping-cart-form table-responsive">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">Product name</th>
                                    <th class="product-price">Unit price</th>
                                    <th class="product-stock">Stock status</th>
                                    <th class="product-add-cart">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    @php
                                        $firstVariant = $product->variants->first();
                                        $price = $firstVariant?->prices->first();
                                        $thumbnail = $product->thumbnail?->getUrl('small') ?? asset('brancy/images/shop/1.webp');
                                        $productName = $product->translateAttribute('name');
                                        $productUrl = route('shop.product.show', $product->defaultUrl?->slug ?? $product->id);
                                        $inStock = $firstVariant && ($firstVariant->stock > 0 || $firstVariant->purchasable === 'always');
                                    @endphp
                                    <tr data-product-id="{{ $product->id }}">
                                        <td class="product-remove">
                                            <button type="button" class="btn-close remove-wishlist-item" data-product-id="{{ $product->id }}" aria-label="Remove">×</button>
                                        </td>
                                        <td class="product-thumbnail">
                                            <a class="d-block" href="{{ $productUrl }}">
                                                <img src="{{ $thumbnail }}" width="80" height="90" alt="{{ $productName }}">
                                            </a>
                                        </td>
                                        <td class="product-name">
                                            <h5 class="title"><a href="{{ $productUrl }}">{{ $productName }}</a></h5>
                                            @if($product->collections->isNotEmpty())
                                                <small class="text-muted">{{ $product->collections->first()->translateAttribute('name') }}</small>
                                            @endif
                                        </td>
                                        <td class="product-price">
                                            @if($price)
                                                <span class="price">{{ $price->price->formatted }}</span>
                                            @else
                                                <span class="text-muted">Price on request</span>
                                            @endif
                                        </td>
                                        <td class="product-stock">
                                            @if($inStock)
                                                <span class="badge bg-success">In Stock</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </td>
                                        <td class="product-add-cart">
                                            @if($inStock && $firstVariant)
                                                <a href="{{ route('cart.add') }}" 
                                                   class="btn btn-sm btn-theme add-to-cart-from-wishlist"
                                                   data-variant-id="{{ $firstVariant->id }}"
                                                   data-product-id="{{ $product->id }}"
                                                   data-product-name="{{ $productName }}">
                                                    Add to Cart
                                                </a>
                                            @else
                                                <a href="{{ $productUrl }}" class="btn btn-sm btn-outline-secondary">View Details</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="shopping-cart-footer mt-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="shopping-cart-btn">
                                    <a class="btn btn-outline-dark" href="{{ route('shop.index') }}">
                                        <i class="fa fa-shopping-bag me-2"></i>Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-10">
                        <i class="fa fa-heart-o fa-4x mb-4 text-muted"></i>
                        <h4>Your wishlist is empty</h4>
                        <p class="text-muted">You have no items in your wishlist. Start adding products you love!</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary mt-4">
                            <i class="fa fa-shopping-bag me-2"></i>Explore Products
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
<!--== End Wishlist Area ==-->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Remove item from wishlist
    $('.remove-wishlist-item').on('click', function() {
        const $btn = $(this);
        const productId = $btn.data('product-id');
        const $row = $btn.closest('tr');

        if (!confirm('Remove this product from your wishlist?')) {
            return;
        }

        $.ajax({
            url: '/wishlist/' + productId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Remove row with animation
                    $row.fadeOut(300, function() {
                        $(this).remove();

                        // Check if wishlist is empty
                        if ($('tbody tr').length === 0) {
                            location.reload();
                        }
                    });

                    // Update wishlist count in header if exists
                    if (response.count !== undefined) {
                        $('.wishlist-count').text(response.count);
                        if (response.count === 0) {
                            $('.wishlist-count').hide();
                        }
                    }
                }
            },
            error: function() {
                alert('Failed to remove item from wishlist');
            }
        });
    });

    // Add to cart from wishlist
    $('.add-to-cart-from-wishlist').on('click', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const variantId = $btn.data('variant-id');
        const productId = $btn.data('product-id');
        const productName = $btn.data('product-name');
        const originalHtml = $btn.html();

        $btn.prop('disabled', true);
        $btn.html('Adding...');

        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                variant_id: variantId,
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count in header
                    const $cartBadge = $('.cart-count');
                    $cartBadge.text(response.cart_count);
                    if (response.cart_count > 0) {
                        $cartBadge.show();
                    }

                    // Get product image and URL for modal
                    const productImage = $btn.closest('tr').find('.product-thumbnail img').attr('src') || '{{ asset('brancy/images/shop/1.webp') }}';
                    const productUrl = $btn.closest('tr').find('.product-name a').attr('href') || '{{ route('shop.index') }}';

                    // Show success modal
                    showAddToCartModal(productName, productImage, productUrl);

                    // Reset button
                    $btn.prop('disabled', false);
                    $btn.html(originalHtml);
                } else {
                    alert('Failed to add product to cart: ' + (response.message || 'Unknown error'));
                    $btn.prop('disabled', false);
                    $btn.html(originalHtml);
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Error adding product to cart';
                alert('Failed to add product to cart: ' + errorMsg);
                $btn.prop('disabled', false);
                $btn.html(originalHtml);
            }
        });
    });
});
</script>
@endpush
