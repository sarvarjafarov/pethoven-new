@props(['product'])

@php
    $firstVariant = $product->variants->first();
    $price = $firstVariant?->prices->first();
    $thumbnail = $product->thumbnail?->getUrl('medium') ?? asset('brancy/images/shop/1.webp');
@endphp

<div class="product-item">
    <div class="product-thumb">
        <a href="{{ route('shop.product.show', $product->slug) }}">
            <img src="{{ $thumbnail }}" alt="{{ $product->name }}" class="w-100">
        </a>
        @if($product->collections->isNotEmpty())
            <span class="badges">
                <span class="badge badge-new">{{ $product->collections->first()->name }}</span>
            </span>
        @endif
        <div class="product-action">
            <a class="action-btn" href="{{ route('shop.product.show', $product->slug) }}" title="Quick View">
                <i class="fa fa-eye"></i>
            </a>
            <button class="action-btn" type="button" title="Add to Wishlist">
                <i class="fa fa-heart-o"></i>
            </button>
            <button class="action-btn" type="button" title="Add to Cart">
                <i class="fa fa-shopping-cart"></i>
            </button>
        </div>
    </div>
    <div class="product-info">
        <h4 class="product-title">
            <a href="{{ route('shop.product.show', $product->slug) }}">{{ $product->name }}</a>
        </h4>
        @if($price)
            <div class="product-price">
                <span class="price">{{ $price->price->formatted }}</span>
            </div>
        @else
            <div class="product-price">
                <span class="price">Price on request</span>
            </div>
        @endif
        <div class="product-rating">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star-o"></i>
        </div>
    </div>
</div>
