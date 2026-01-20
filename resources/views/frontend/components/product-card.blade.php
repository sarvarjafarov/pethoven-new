@props(['product'])

@php
    $firstVariant = $product->variants->first();
    $price = $firstVariant?->prices->first();
    // Use product thumbnail if available, otherwise cycle through demo images (1-6)
    $demoImageIndex = ($product->id % 6) + 1;
    $thumbnail = $product->thumbnail?->getUrl('medium') ?? asset("brancy/images/shop/{$demoImageIndex}.webp");
    $productName = $product->translateAttribute('name');
    $productUrl = $product->defaultUrl?->slug ?? $product->id;
@endphp

<!--== Start Product Item ==-->
<div class="product-item product-st3-item">
    <div class="product-thumb">
        <a class="d-block" href="{{ route('shop.product.show', $productUrl) }}">
            <img src="{{ $thumbnail }}" width="370" height="450" alt="{{ $productName }}">
        </a>
        @if($product->collections->isNotEmpty())
            <span class="flag-new">{{ $product->collections->first()->translateAttribute('name') }}</span>
        @endif
        <div class="product-action">
            <button type="button" class="product-action-btn action-btn-quick-view">
                <i class="fa fa-expand"></i>
            </button>
            <button type="button" class="product-action-btn action-btn-cart quick-add-to-cart" data-variant-id="{{ $firstVariant?->id }}" data-product-name="{{ $productName }}">
                <span>Add to cart</span>
            </button>
            <button type="button" class="product-action-btn action-btn-wishlist" data-product-id="{{ $product->id }}" data-product-name="{{ $productName }}">
                <i class="fa fa-heart-o"></i>
            </button>
            <button type="button" class="product-action-btn action-btn-compare" data-product-id="{{ $product->id }}" data-product-name="{{ $productName }}" title="Add to Compare">
                <i class="fa fa-random"></i>
            </button>
        </div>
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
        <h4 class="title"><a href="{{ route('shop.product.show', $productUrl) }}">{{ $productName }}</a></h4>
        @if($price)
            <div class="prices">
                <span class="price">{{ $price->price->formatted }}</span>
            </div>
        @else
            <div class="prices">
                <span class="price">Price on request</span>
            </div>
        @endif
    </div>
    <div class="product-action-bottom">
        <button type="button" class="product-action-btn action-btn-quick-view">
            <i class="fa fa-expand"></i>
        </button>
        <button type="button" class="product-action-btn action-btn-wishlist" data-product-id="{{ $product->id }}" data-product-name="{{ $productName }}">
            <i class="fa fa-heart-o"></i>
        </button>
        <button type="button" class="product-action-btn action-btn-compare" data-product-id="{{ $product->id }}" data-product-name="{{ $productName }}" title="Add to Compare">
            <i class="fa fa-random"></i>
        </button>
        <button type="button" class="product-action-btn action-btn-cart quick-add-to-cart" data-variant-id="{{ $firstVariant?->id }}" data-product-name="{{ $productName }}">
            <span>Add to cart</span>
        </button>
    </div>
</div>
<!--== End Product Item ==-->
