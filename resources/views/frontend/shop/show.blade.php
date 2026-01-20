@extends('frontend.layouts.app')

@section('title', $product->translateAttribute('name') . ' - ' . config('app.name'))

@php
    $productName = $product->translateAttribute('name');
    $productDescription = $product->translateAttribute('description') ? strip_tags($product->translateAttribute('description')) : $productName;
    $productImage = $product->thumbnail?->getUrl('large') ?? asset('brancy/images/shop/1.webp');
    $productUrl = route('shop.product.show', $product->defaultUrl?->slug ?? $product->id);
    $firstVariant = $product->variants->first();
    $price = $firstVariant?->prices->first();
@endphp

@section('meta_description', Str::limit($productDescription, 160))
@section('meta_keywords', implode(', ', array_merge(['product', $productName], $product->collections->pluck('name')->toArray())))

@section('canonical', $productUrl)

@section('og_type', 'product')
@section('og_title', $productName)
@section('og_description', Str::limit($productDescription, 200))
@section('og_image', $productImage)
@section('og_url', $productUrl)

@section('twitter_title', $productName)
@section('twitter_description', Str::limit($productDescription, 200))
@section('twitter_image', $productImage)

@push('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": "{{ $productName }}",
    "description": "{{ $productDescription }}",
    "image": "{{ $productImage }}",
    "sku": "{{ $firstVariant?->sku }}",
    "brand": {
        "@type": "Brand",
        "name": "{{ config('app.name') }}"
    },
    "offers": {
        "@type": "Offer",
        "url": "{{ $productUrl }}",
        "priceCurrency": "{{ $price?->currency->code ?? 'USD' }}",
        "price": "{{ $price?->price->value ?? 0 }}",
        "availability": "{{ $firstVariant && $firstVariant->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "priceValidUntil": "{{ now()->addYear()->format('Y-m-d') }}"
    }
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ route('home') }}"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Shop",
            "item": "{{ route('shop.index') }}"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "{{ $productName }}"
        }
    ]
}
</script>
@endpush

@section('content')
<!--== Start Page Header Area Wrapper ==-->
<section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="page-header-st3-content text-center text-md-start">
                    <ol class="breadcrumb justify-content-center justify-content-md-start">
                        <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Product Detail</li>
                    </ol>
                    <h2 class="page-header-title">{{ $product->translateAttribute('name') }}</h2>
                </div>
            </div>
            <div class="col-md-7">
                <h5 class="showing-pagination-results mt-5 mt-md-9 text-center text-md-end">Showing Single Product</h5>
            </div>
        </div>
    </div>
</section>
<!--== End Page Header Area Wrapper ==-->

<!--== Start Product Details Area ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="product-single-thumb">
                    @php
                        $mainImage = $product->thumbnail?->getUrl('large') ?? asset('brancy/images/shop/1.webp');
                    @endphp
                    <img src="{{ $mainImage }}" alt="{{ $product->name }}" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product-details-content">
                    @if($product->collections->isNotEmpty())
                        <h5 class="product-details-collection">{{ $product->collections->first()->name }}</h5>
                    @endif
                    <h3 class="product-details-title">{{ $product->name }}</h3>

                    <div class="product-details-review mb-5">
                        <div class="product-review-icon">
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-half-o"></i>
                        </div>
                        <button type="button" class="product-review-show">150 reviews</button>
                    </div>

                    @if($product->description)
                        <p class="mb-6">{!! nl2br(e($product->translateAttribute('description'))) !!}</p>
                    @else
                        <p class="mb-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed viverra amet, sodales faucibus nibh. Vivamus amet potenti ultricies nunc gravida duis. Nascetur scelerisque massa sodales.</p>
                    @endif

                    @php
                        $firstVariant = $product->variants->first();
                        $price = $firstVariant?->prices->first();
                    @endphp


                    @if($product->variants->count() > 1)
                        <div class="product-details-variant mb-4">
                            @php
                                $options = [];
                                foreach($product->variants as $variant) {
                                    foreach($variant->values as $value) {
                                        $optionName = $value->option->name ?? 'Option';
                                        if (!isset($options[$optionName])) {
                                            $options[$optionName] = [];
                                        }
                                        $options[$optionName][] = $value->name ?? '';
                                    }
                                }
                                foreach($options as &$values) {
                                    $values = array_unique($values);
                                }
                            @endphp

                            @foreach($options as $optionName => $values)
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">{{ $optionName }}</label>
                                    <select class="form-select variant-option" data-option="{{ $optionName }}">
                                        <option value="">Select {{ $optionName }}</option>
                                        @foreach($values as $value)
                                            @if($value)
                                                <option value="{{ $value }}">{{ $value }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="product-details-pro-qty mb-6">
                        <label class="form-label fw-bold">Quantity</label>
                        <div class="pro-qty">
                            <button type="button" class="dec qtybtn">-</button>
                            <input type="text" id="quantity-input" title="Quantity" value="1" min="1" readonly>
                            <button type="button" class="inc qtybtn">+</button>
                        </div>
                    </div>

                    <div class="product-details-action">
                        <h4 class="price">{{ $price ? $price->price->formatted : 'Price on request' }}</h4>
                        <div class="product-details-cart-wishlist">
                            <button type="button" id="add-to-cart-btn" class="btn" data-variant-id="{{ $firstVariant?->id }}">
                                Add to cart
                            </button>
                            <button type="button" class="btn-wishlist action-btn-wishlist" data-product-id="{{ $product->id }}" data-product-name="{{ $productName }}">
                                <i class="fa fa-heart-o"></i>
                            </button>
                        </div>
                    </div>

                    <div class="product-details-meta mt-6">
                        <ul>
                            <li><span>SKU:</span> {{ $firstVariant?->sku ?? 'N/A' }}</li>
                            @if($product->collections->isNotEmpty())
                                <li>
                                    <span>Categories:</span>
                                    @foreach($product->collections as $collection)
                                        <a href="{{ route('shop.index', ['collection' => $collection->slug]) }}">{{ $collection->name }}</a>{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        @if($related->isNotEmpty())
            <div class="row mt-12">
                <div class="col-12">
                    <div class="section-title text-center mb-8">
                        <h2 class="title">Related Products</h2>
                    </div>
                </div>
            </div>
            <div class="row g-4 g-sm-5">
                @foreach($related as $relatedProduct)
                    <div class="col-6 col-md-3">
                        @include('frontend.components.product-card', ['product' => $relatedProduct])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
<!--== End Product Details Area ==-->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Product variants data
    const variants = @json($variantData);

    // Quantity buttons
    $('.qtybtn').on('click', function() {
        const $input = $('#quantity-input');
        let qty = parseInt($input.val()) || 1;

        if ($(this).hasClass('inc')) {
            qty++;
        } else if ($(this).hasClass('dec') && qty > 1) {
            qty--;
        }

        $input.val(qty);
    });

    // Variant selection
    $('.variant-option').on('change', function() {
        const selectedOptions = {};

        $('.variant-option').each(function() {
            const optionName = $(this).data('option');
            const optionValue = $(this).val();
            if (optionValue) {
                selectedOptions[optionName] = optionValue;
            }
        });

        // Find matching variant
        const matchingVariant = variants.find(variant => {
            return Object.keys(selectedOptions).every(optionName => {
                return variant.values[optionName] === selectedOptions[optionName];
            });
        });

        if (matchingVariant) {
            // Update price
            if (matchingVariant.price) {
                $('.product-details-action .price').text(matchingVariant.price);
            }

            // Update SKU
            $('.product-details-meta li:first span:last').text(matchingVariant.sku || 'N/A');

            // Update add to cart button
            $('#add-to-cart-btn').data('variant-id', matchingVariant.id);
        }
    });

    // Add to cart
    $('#add-to-cart-btn').on('click', function() {
        const $btn = $(this);
        const variantId = $btn.data('variant-id');
        const quantity = parseInt($('#quantity-input').val()) || 1;

        if (!variantId) {
            alert('Please select product options');
            return;
        }

        $btn.prop('disabled', true);
        $btn.html('<i class="fa fa-spinner fa-spin me-2"></i>Adding...');

        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                variant_id: variantId,
                quantity: quantity,
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

                    // Show success message
                    alert('Product added to cart successfully!');

                    // Reset button
                    $btn.prop('disabled', false);
                    $btn.html('<i class="fa fa-shopping-cart me-2"></i>Add to Cart');
                }
            },
            error: function(xhr) {
                alert('Error adding product to cart. Please try again.');
                $btn.prop('disabled', false);
                $btn.html('<i class="fa fa-shopping-cart me-2"></i>Add to Cart');
            }
        });
    });
});
</script>
@endpush
