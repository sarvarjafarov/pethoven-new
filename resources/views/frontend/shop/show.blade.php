@extends('frontend.layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">{{ $product->name }}</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li><a href="{{ route('shop.index') }}">Shop</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>{{ $product->name }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area ==-->

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
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                        </div>
                    </div>

                    @if($product->description)
                        <p class="mb-6">{!! nl2br(e($product->description)) !!}</p>
                    @endif

                    @php
                        $firstVariant = $product->variants->first();
                        $price = $firstVariant?->prices->first();
                    @endphp

                    @if($price)
                        <div class="product-details-action mb-4">
                            <h4 class="price">{{ $price->price->formatted }}</h4>
                        </div>
                    @endif

                    <div class="product-details-pro-qty mb-6">
                        <div class="pro-qty">
                            <input type="text" title="Quantity" value="1" min="1">
                        </div>
                    </div>

                    <div class="product-details-action">
                        <div class="product-details-cart-wishlist">
                            <button type="button" class="btn btn-primary">Add to Cart</button>
                            <button type="button" class="btn btn-outline-primary ms-3">
                                <i class="fa fa-heart-o"></i> Wishlist
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
