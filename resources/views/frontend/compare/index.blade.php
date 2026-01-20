@extends('frontend.layouts.app')

@section('title', 'Compare Products - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<section class="page-header-area" data-bg-img="{{ asset('brancy/images/photos/breadcrumb1.webp') }}">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-st3-content text-center">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Compare Products</li>
                    </ol>
                    <h2 class="page-header-st3-title">Compare Products</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Page Header Area ==-->

<!--== Start Compare Area ==-->
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

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($products && $products->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Comparing {{ $products->count() }} {{ Str::plural('product', $products->count()) }}</h5>
                        <form action="{{ route('compare.clear') }}" method="POST" class="d-inline" id="clear-compare-form">
                            @csrf
                            <button type="button" class="btn btn-outline-danger btn-sm" id="clear-compare-btn">
                                <i class="fa fa-trash me-1"></i>Clear All
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered compare-table">
                            <tbody>
                                <!-- Product Images -->
                                <tr>
                                    <th class="text-muted">Product</th>
                                    @foreach($products as $product)
                                        @php
                                            $thumbnail = $product->thumbnail?->getUrl('medium') ?? asset('brancy/images/shop/1.webp');
                                            $productName = $product->translateAttribute('name');
                                            $productUrl = route('shop.product.show', $product->defaultUrl?->slug ?? $product->id);
                                        @endphp
                                        <td class="text-center" style="width: {{ 70 / $products->count() }}%">
                                            <button type="button" class="btn-close float-end remove-compare-item" data-product-id="{{ $product->id }}" aria-label="Remove"></button>
                                            <a href="{{ $productUrl }}">
                                                <img src="{{ $thumbnail }}" alt="{{ $productName }}" class="img-fluid mb-3" style="max-height: 200px;">
                                            </a>
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Product Names -->
                                <tr>
                                    <th class="text-muted">Name</th>
                                    @foreach($products as $product)
                                        @php
                                            $productName = $product->translateAttribute('name');
                                            $productUrl = route('shop.product.show', $product->defaultUrl?->slug ?? $product->id);
                                        @endphp
                                        <td class="text-center">
                                            <h6><a href="{{ $productUrl }}">{{ $productName }}</a></h6>
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Price -->
                                <tr>
                                    <th class="text-muted">Price</th>
                                    @foreach($products as $product)
                                        @php
                                            $firstVariant = $product->variants->first();
                                            $price = $firstVariant?->prices->first();
                                        @endphp
                                        <td class="text-center">
                                            @if($price)
                                                <h5 class="text-primary">{{ $price->price->formatted }}</h5>
                                            @else
                                                <span class="text-muted">Price on request</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Category -->
                                <tr>
                                    <th class="text-muted">Category</th>
                                    @foreach($products as $product)
                                        <td class="text-center">
                                            @if($product->collections->isNotEmpty())
                                                {{ $product->collections->first()->translateAttribute('name') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- SKU -->
                                <tr>
                                    <th class="text-muted">SKU</th>
                                    @foreach($products as $product)
                                        @php
                                            $firstVariant = $product->variants->first();
                                        @endphp
                                        <td class="text-center">
                                            @if($firstVariant)
                                                <small>{{ $firstVariant->sku }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Stock Status -->
                                <tr>
                                    <th class="text-muted">Availability</th>
                                    @foreach($products as $product)
                                        @php
                                            $firstVariant = $product->variants->first();
                                            $inStock = $firstVariant && ($firstVariant->stock > 0 || $firstVariant->purchasable === 'always');
                                        @endphp
                                        <td class="text-center">
                                            @if($inStock)
                                                <span class="badge bg-success">In Stock</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Description -->
                                <tr>
                                    <th class="text-muted align-top">Description</th>
                                    @foreach($products as $product)
                                        <td>
                                            @if($product->description)
                                                <small>{{ Str::limit($product->translateAttribute('description'), 100) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Actions -->
                                <tr>
                                    <th class="text-muted">Actions</th>
                                    @foreach($products as $product)
                                        @php
                                            $firstVariant = $product->variants->first();
                                            $productName = $product->translateAttribute('name');
                                            $inStock = $firstVariant && ($firstVariant->stock > 0 || $firstVariant->purchasable === 'always');
                                        @endphp
                                        <td class="text-center">
                                            @if($inStock && $firstVariant)
                                                <button type="button" class="btn btn-sm btn-dark mb-2 w-100 add-to-cart-from-compare"
                                                        data-variant-id="{{ $firstVariant->id }}"
                                                        data-product-id="{{ $product->id }}"
                                                        data-product-name="{{ $productName }}">
                                                    <i class="fa fa-shopping-cart me-1"></i>Add to Cart
                                                </button>
                                            @endif
                                            <a href="{{ route('shop.product.show', $product->defaultUrl?->slug ?? $product->id) }}" class="btn btn-sm btn-outline-secondary w-100">
                                                View Details
                                            </a>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a class="btn btn-outline-dark" href="{{ route('shop.index') }}">
                            <i class="fa fa-shopping-bag me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-10">
                        <i class="fa fa-balance-scale fa-4x mb-4 text-muted"></i>
                        <h4>No products to compare</h4>
                        <p class="text-muted">You haven't added any products to compare yet. Add products from the shop to compare them side-by-side.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary mt-4">
                            <i class="fa fa-shopping-bag me-2"></i>Explore Products
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
<!--== End Compare Area ==-->
@endsection

@push('styles')
<style>
.compare-table th {
    background-color: #f8f9fa;
    font-weight: 500;
    vertical-align: middle;
}
.compare-table td {
    vertical-align: middle;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Remove item from compare
    $('.remove-compare-item').on('click', function() {
        const $btn = $(this);
        const productId = $btn.data('product-id');

        if (!confirm('Remove this product from comparison?')) {
            return;
        }

        $.ajax({
            url: '/compare/' + productId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Reload page to update the comparison table
                    location.reload();
                }
            },
            error: function() {
                alert('Failed to remove item from compare');
            }
        });
    });

    // Add to cart from compare
    $('.add-to-cart-from-compare').on('click', function() {
        const $btn = $(this);
        const variantId = $btn.data('variant-id');
        const productName = $btn.data('product-name');
        const originalHtml = $btn.html();

        $btn.prop('disabled', true);
        $btn.html('<i class="fa fa-spinner fa-spin me-1"></i>Adding...');

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
                    $('.cart-count').text(response.cart_count).show();

                    // Show success
                    $btn.html('<i class="fa fa-check me-1"></i>Added!');
                    $btn.removeClass('btn-dark').addClass('btn-success');

                    setTimeout(function() {
                        $btn.prop('disabled', false);
                        $btn.html(originalHtml);
                        $btn.removeClass('btn-success').addClass('btn-dark');
                    }, 2000);
                } else {
                    alert('Failed to add product to cart');
                    $btn.prop('disabled', false);
                    $btn.html(originalHtml);
                }
            },
            error: function() {
                alert('Error adding product to cart');
                $btn.prop('disabled', false);
                $btn.html(originalHtml);
            }
        });
    });

    // Clear compare
    $('#clear-compare-btn').on('click', function() {
        if (confirm('Are you sure you want to clear all products from comparison?')) {
            $('#clear-compare-form').submit();
        }
    });
});
</script>
@endpush
