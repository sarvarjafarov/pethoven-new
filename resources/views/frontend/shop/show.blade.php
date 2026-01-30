@extends('frontend.layouts.app')

@section('title', $product->translateAttribute('name') . ' - ' . config('app.name'))

@php
    $productName = $product->translateAttribute('name');
    $productDescription = $product->translateAttribute('description') ? strip_tags($product->translateAttribute('description')) : $productName;
    $productImage = $product->thumbnail?->getUrl('large') ?? asset('brancy/images/shop/product-details/1.webp');
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

<!--== Start Product Details Area Wrapper ==-->
<section class="section-space">
    <div class="container">
        <div class="row product-details">
            <div class="col-lg-6">
                <div class="product-details-thumb">
                    @php
                        // Use product thumbnail if available and valid LOCAL URL, otherwise use template CDN demo image
                        $productThumbnail = null;
                        try {
                            $productThumbnail = $product->thumbnail?->getUrl('large');
                        } catch (\Exception $e) {
                            // If thumbnail fails, use demo image
                        }
                        // Check if thumbnail is valid LOCAL URL (not from template CDN, and not a broken /storage URL on Heroku)
                        $isValidLocalThumbnail = !empty($productThumbnail) 
                            && strpos($productThumbnail, 'http') !== false 
                            && strpos($productThumbnail, 'template.hasthemes.com') === false
                            && strpos($productThumbnail, '/storage/') === false
                            && (strpos($productThumbnail, url('/')) === 0 || strpos($productThumbnail, '/') === 0);
                        // Use template CDN demo image if no local thumbnail
                        $mainImage = $isValidLocalThumbnail
                            ? $productThumbnail
                            : 'https://template.hasthemes.com/brancy/brancy/assets/images/shop/product-details/1.webp';
                        // Local fallback if external demo CDN is blocked in the browser.
                        $mainImageFallback = asset('brancy/images/shop/product-details/1.webp');
                    @endphp
                    <img src="{{ $mainImage }}" width="570" height="693" alt="{{ $productName }}" onerror="this.onerror=null;this.src='{{ $mainImageFallback }}';">
                    <span class="flag-new">new</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product-details-content">
                    @php
                        $collectionName = $product->collections->first()?->translateAttribute('name') ?? 'Premium Collection';
                    @endphp
                    <h5 class="product-details-collection">{{ $collectionName }}</h5>
                    <h3 class="product-details-title">{{ $productName }}</h3>
                    <div class="product-details-review">
                        <div class="product-review-icon">
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                        <button type="button" class="product-review-show">0 reviews</button>
                    </div>

                    @php
                        $variants = $product->variants;
                        $selectedVariant = $firstVariant;
                        $selectedPrice = $price;
                        $shippingFee = 4.22;
                    @endphp

                    <div class="product-details-qty-list">
                        @foreach($variants as $index => $variant)
                            @php
                                $variantPrice = $variant->prices->first();
                                $variantLabel = $variant->values->isNotEmpty()
                                    ? $variant->values->map(fn($v) => $v->name)->join(', ')
                                    : ($variant->sku ?? 'Option ' . ($index + 1));
                                $priceValue = $variantPrice?->price?->decimal ?? 0;
                                $priceFormatted = $variantPrice?->price?->formatted ?? '$0.00';
                            @endphp
                            <div class="qty-list-check">
                                <input class="form-check-input variant-radio" type="radio" name="productVariant" id="variant{{ $variant->id }}" value="{{ $variant->id }}" data-price="{{ $priceValue }}" {{ $index === 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="variant{{ $variant->id }}">
                                    {{ $variantLabel }} <b>{{ $priceFormatted }}</b>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="product-details-pro-qty">
                        <div class="pro-qty">
                            <input type="text" id="quantity-input" title="Quantity" value="01" readonly>
                        </div>
                    </div>

                    <div class="product-details-shipping-cost">
                        <input class="form-check-input" type="checkbox" value="" id="ShippingCost" checked>
                        <label class="form-check-label" for="ShippingCost">Shipping from USA, Shipping Fees $4.22</label>
                    </div>

                    <div class="product-details-action">
                        <h4 class="price" id="final-price">$254.22</h4>
                        <div class="product-details-cart-wishlist">
                            <button type="button" class="btn-wishlist action-btn-wishlist" data-product-id="{{ $product->id }}" data-product-name="{{ $productName }}">
                                <i class="fa fa-heart-o"></i>
                            </button>
                            <button type="button" id="add-to-cart-btn" class="btn" data-variant-id="{{ $selectedVariant?->id }}">
                                Add to cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7">
                <div class="nav product-details-nav" id="product-details-nav-tab" role="tablist">
                    <button class="nav-link" id="specification-tab" data-bs-toggle="tab" data-bs-target="#specification" type="button" role="tab" aria-controls="specification" aria-selected="false">Specification</button>
                    <button class="nav-link active" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button" role="tab" aria-controls="review" aria-selected="true">Review</button>
                </div>
                <div class="tab-content" id="product-details-nav-tabContent">
                    <div class="tab-pane" id="specification" role="tabpanel" aria-labelledby="specification-tab">
                        @php
                            $sku = $firstVariant?->sku;
                            $weight = $firstVariant?->weight_value;
                            $weightUnit = $firstVariant?->weight_unit ?? 'g';
                            $length = $firstVariant?->length_value;
                            $width = $firstVariant?->width_value;
                            $height = $firstVariant?->height_value;
                            $dimensionUnit = $firstVariant?->dimension_unit ?? 'cm';
                        @endphp
                        <ul class="product-details-info-wrap">
                            @if($sku)
                            <li><span>SKU</span>
                                <p>{{ $sku }}</p>
                            </li>
                            @endif
                            @if($weight)
                            <li><span>Weight</span>
                                <p>{{ $weight }} {{ $weightUnit }}</p>
                            </li>
                            @endif
                            @if($length && $width && $height)
                            <li><span>Dimensions</span>
                                <p>{{ $length }} x {{ $width }} x {{ $height }} {{ $dimensionUnit }}</p>
                            </li>
                            @endif
                        </ul>

                        @if($product->translateAttribute('description'))
                            <p>{!! nl2br(e($product->translateAttribute('description'))) !!}</p>
                        @else
                            <p>No description available for this product.</p>
                        @endif
                    </div>

                    <div class="tab-pane fade show active" id="review" role="tabpanel" aria-labelledby="review-tab">
                        <!--== Start Reviews Content Item ==-->
                        <div class="product-review-item">
                            <div class="product-review-top">
                                <div class="product-review-thumb">
                                    <img src="{{ asset('brancy/images/shop/product-details/comment1.webp') }}" alt="Images">
                                </div>
                                <div class="product-review-content">
                                    <span class="product-review-name">Tomas Doe</span>
                                    <span class="product-review-designation">Delveloper</span>
                                    <div class="product-review-icon">
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed viverra amet, sodales faucibus nibh. Vivamus amet potenti ultricies nunc gravida duis. Nascetur scelerisque massa sodales.</p>
                            <button type="button" class="review-reply"><i class="fa fa fa-undo"></i></button>
                        </div>
                        <!--== End Reviews Content Item ==-->

                        <!--== Start Reviews Content Item ==-->
                        <div class="product-review-item product-review-reply">
                            <div class="product-review-top">
                                <div class="product-review-thumb">
                                    <img src="{{ asset('brancy/images/shop/product-details/comment2.webp') }}" alt="Images">
                                </div>
                                <div class="product-review-content">
                                    <span class="product-review-name">Tomas Doe</span>
                                    <span class="product-review-designation">Delveloper</span>
                                    <div class="product-review-icon">
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed viverra amet, sodales faucibus nibh. Vivamus amet potenti ultricies nunc gravida duis. Nascetur scelerisque massa sodales.</p>
                            <button type="button" class="review-reply"><i class="fa fa fa-undo"></i></button>
                        </div>
                        <!--== End Reviews Content Item ==-->

                        <!--== Start Reviews Content Item ==-->
                        <div class="product-review-item mb-0">
                            <div class="product-review-top">
                                <div class="product-review-thumb">
                                    <img src="{{ asset('brancy/images/shop/product-details/comment3.webp') }}" alt="Images">
                                </div>
                                <div class="product-review-content">
                                    <span class="product-review-name">Tomas Doe</span>
                                    <span class="product-review-designation">Delveloper</span>
                                    <div class="product-review-icon">
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed viverra amet, sodales faucibus nibh. Vivamus amet potenti ultricies nunc gravida duis. Nascetur scelerisque massa sodales.</p>
                            <button type="button" class="review-reply"><i class="fa fa fa-undo"></i></button>
                        </div>
                        <!--== End Reviews Content Item ==-->
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="product-reviews-form-wrap">
                    <h4 class="product-form-title">Leave a Review</h4>
                    <div class="product-reviews-form">
                        <p class="text-muted">Reviews are coming soon. Check back later to share your feedback on this product.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Product Details Area Wrapper ==-->

<!--== Start Product Banner Area Wrapper ==-->
<div class="container">
    <!--== Start Product Category Item ==-->
    <a href="{{ route('shop.index') }}" class="product-banner-item">
        <img src="{{ asset('brancy/images/shop/banner/7.webp') }}" width="1170" height="240" alt="Image-HasTech">
    </a>
    <!--== End Product Category Item ==-->
</div>
<!--== End Product Banner Area Wrapper ==-->

<!--== Start Product Area Wrapper ==-->
@if($related->isNotEmpty())
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2 class="title">Related Products</h2>
                    <p class="m-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis</p>
                </div>
            </div>
        </div>
        <div class="row mb-n10">
            <div class="col-12">
                <div class="swiper related-product-slide-container">
                    <div class="swiper-wrapper">
                        @foreach($related as $relatedProduct)
                            @php
                                $relatedVariant = $relatedProduct->variants->first();
                                $relatedPrice = $relatedVariant?->prices->first();
                                // Use product thumbnail if available and valid LOCAL URL, otherwise use demo image
                                $relatedProductThumbnail = null;
                                try {
                                    $relatedProductThumbnail = $relatedProduct->thumbnail?->getUrl('medium');
                                } catch (\Exception $e) {
                                    // If thumbnail fails, use demo image
                                }
                                // Cycle through demo images (1-6) based on product ID
                                $demoImageIndex = (($relatedProduct->id ?? 1) % 6) + 1;
                                // Check if thumbnail is valid LOCAL URL (not from template CDN, and not a broken /storage URL on Heroku)
                                $isValidLocalThumbnail = !empty($relatedProductThumbnail) 
                                    && strpos($relatedProductThumbnail, 'http') !== false 
                                    && strpos($relatedProductThumbnail, 'template.hasthemes.com') === false
                                    && strpos($relatedProductThumbnail, '/storage/') === false
                                    && (strpos($relatedProductThumbnail, url('/')) === 0 || strpos($relatedProductThumbnail, '/') === 0);
                                // Use template CDN demo images if no local thumbnail
                                $relatedThumbnail = $isValidLocalThumbnail
                                    ? $relatedProductThumbnail
                                    : "https://template.hasthemes.com/brancy/brancy/assets/images/shop/{$demoImageIndex}.webp";
                                // Local fallback if external demo CDN is blocked in the browser.
                                $relatedThumbnailFallback = asset("brancy/images/shop/{$demoImageIndex}.webp");
                                $relatedName = $relatedProduct->translateAttribute('name');
                                $relatedUrl = $relatedProduct->defaultUrl?->slug ?? $relatedProduct->id;
                            @endphp
                            <div class="swiper-slide mb-10">
                                <!--== Start Product Item ==-->
                                <div class="product-item product-st2-item">
                                    <div class="product-thumb">
                                        <a class="d-block" href="{{ route('shop.product.show', $relatedUrl) }}">
                                            <img src="{{ $relatedThumbnail }}" width="370" height="450" alt="{{ $relatedName }}" onerror="this.onerror=null;this.src='{{ $relatedThumbnailFallback }}';">
                                        </a>
                                        @if($relatedProduct->collections->isNotEmpty() || true)
                                            <span class="flag-new">new</span>
                                        @endif
                                    </div>
                                    <div class="product-info">
                                        <div class="product-rating">
                                            <div class="rating">
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-half-o"></i>
                                            </div>
                                            <div class="reviews">150 reviews</div>
                                        </div>
                                        <h4 class="title"><a href="{{ route('shop.product.show', $relatedUrl) }}">{{ $relatedName }}</a></h4>
                                        @if($relatedPrice)
                                            <div class="prices">
                                                <span class="price">{{ $relatedPrice->price->formatted }}</span>
                                            </div>
                                        @else
                                            <div class="prices">
                                                <span class="price">Price on request</span>
                                            </div>
                                        @endif
                                        <div class="product-action">
                                            @php
                                                $relatedThumbnail = $relatedProduct->thumbnail?->getUrl('medium') ?? asset('brancy/images/shop/' . ((($relatedProduct->id ?? 1) % 6) + 1) . '.webp');
                                            @endphp
                                            <button type="button" class="product-action-btn action-btn-cart quick-add-to-cart" data-variant-id="{{ $relatedVariant?->id }}" data-product-name="{{ $relatedName }}" data-product-image="{{ $relatedThumbnail }}" data-product-url="{{ route('shop.product.show', $relatedUrl) }}">
                                                <span>Add to cart</span>
                                            </button>
                                            <button type="button" class="product-action-btn action-btn-quick-view">
                                                <i class="fa fa-expand"></i>
                                            </button>
                                            <button type="button" class="product-action-btn action-btn-wishlist" data-product-id="{{ $relatedProduct->id }}" data-product-name="{{ $relatedName }}">
                                                <i class="fa fa-heart-o"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!--== End Product Item ==-->
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!--== End Product Area Wrapper ==-->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const shippingFee = 4.22;
    
    // Calculate final price
    function calculateFinalPrice() {
        const selectedVariant = $('.variant-radio:checked');
        const basePrice = parseFloat(selectedVariant.data('price')) || 250.00;
        const shippingIncluded = $('#ShippingCost').is(':checked');
        const finalPrice = basePrice + (shippingIncluded ? shippingFee : 0);
        $('#final-price').text('$' + finalPrice.toFixed(2));
    }

    // Quantity increment/decrement (Brancy's main.js adds the buttons, we handle the formatting)
    $(document).on('click', '.pro-qty .qty-btn', function() {
        const $button = $(this);
        const $input = $('#quantity-input');
        let oldValue = parseInt($input.val()) || 1;
        
        if ($button.hasClass('inc')) {
            var newVal = oldValue + 1;
        } else {
            if (oldValue > 1) {
                var newVal = oldValue - 1;
            } else {
                newVal = 1;
            }
        }
        
        // Format with leading zero if < 10 (matching template format)
        $input.val(newVal < 10 ? '0' + newVal : newVal.toString());
    });

    // Variant radio button selection
    $('.variant-radio').on('change', function() {
        calculateFinalPrice();
    });

    // Shipping checkbox change
    $('#ShippingCost').on('change', function() {
        calculateFinalPrice();
    });

    // Initialize price on page load
    calculateFinalPrice();

    // Add to cart
    $('#add-to-cart-btn').on('click', function() {
        const $btn = $(this);
        const variantId = $('.variant-radio:checked').val() || $btn.data('variant-id');
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
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count in header
                    const $cartBadge = $('.cart-count');
                    $cartBadge.text(response.cart_count);

                    if (response.cart_count > 0) {
                        $cartBadge.show();
                    }

                    // Show success modal
                    @php
                        $modalProductName = $product->translateAttribute('name');
                        $modalProductImage = $product->thumbnail ? $product->thumbnail->getUrl('medium') : asset('brancy/images/shop/1.webp');
                        $modalProductUrl = route('shop.product.show', $product->defaultUrl?->slug ?? $product->id);
                    @endphp
                    showAddToCartModal('{{ addslashes($modalProductName) }}', '{{ $modalProductImage }}', '{{ $modalProductUrl }}');

                    // Reset button
                    $btn.prop('disabled', false);
                    $btn.html('Add to cart');
                }
            },
            error: function(xhr) {
                alert('Error adding product to cart. Please try again.');
                $btn.prop('disabled', false);
                $btn.html('Add to cart');
            }
        });
    });
});
</script>
@endpush
