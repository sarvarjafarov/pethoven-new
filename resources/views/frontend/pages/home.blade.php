@extends('frontend.layouts.app')

@section('title', 'Home - ' . config('app.name'))

@section('content')
<!--== Start Hero Area Wrapper ==-->
<section class="hero-slider-area position-relative">
    <div class="swiper hero-slider-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide hero-slide-item">
                <div class="container">
                    <div class="row align-items-center position-relative">
                        <div class="col-12 col-md-6">
                            <div class="hero-slide-content">
                                <h2 class="hero-slide-title">CLEAN FRESH</h2>
                                <p class="hero-slide-desc">Discover our premium beauty and cosmetic products for your perfect skin care routine.</p>
                                <a class="btn btn-border-dark" href="{{ route('shop.index') }}">SHOP NOW</a>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="hero-slide-thumb">
                                <img src="{{ asset('brancy/images/slider/slider1.webp') }}" width="841" height="832" alt="Hero Image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--== Add Pagination ==-->
        <div class="hero-slider-pagination"></div>
    </div>
</section>
<!--== End Hero Area Wrapper ==-->

<!--== Start Product Category Area Wrapper ==-->
<section class="section-space pb-0">
    <div class="container">
        <div class="row g-3 g-sm-6">
            <div class="col-6 col-lg-2">
                <a href="{{ route('shop.index') }}" class="product-category-item">
                    <img class="icon" src="{{ asset('brancy/images/shop/category/1.webp') }}" width="70" height="80" alt="Hair Care">
                    <h3 class="title">Hair Care</h3>
                </a>
            </div>
            <div class="col-6 col-lg-2">
                <a href="{{ route('shop.index') }}" class="product-category-item" data-bg-color="#FFEDB4">
                    <img class="icon" src="{{ asset('brancy/images/shop/category/2.webp') }}" width="80" height="80" alt="Skin Care">
                    <h3 class="title">Skin Care</h3>
                </a>
            </div>
            <div class="col-6 col-lg-2">
                <a href="{{ route('shop.index') }}" class="product-category-item" data-bg-color="#DFE4FF">
                    <img class="icon" src="{{ asset('brancy/images/shop/category/3.webp') }}" width="80" height="80" alt="Lipstick">
                    <h3 class="title">Lipstick</h3>
                </a>
            </div>
            <div class="col-6 col-lg-2">
                <a href="{{ route('shop.index') }}" class="product-category-item" data-bg-color="#FFEACC">
                    <img class="icon" src="{{ asset('brancy/images/shop/category/4.webp') }}" width="80" height="80" alt="Face Skin">
                    <h3 class="title">Face Skin</h3>
                </a>
            </div>
            <div class="col-6 col-lg-2">
                <a href="{{ route('shop.index') }}" class="product-category-item" data-bg-color="#FFDAE0">
                    <img class="icon" src="{{ asset('brancy/images/shop/category/5.webp') }}" width="80" height="80" alt="Blusher">
                    <h3 class="title">Blusher</h3>
                </a>
            </div>
            <div class="col-6 col-lg-2">
                <a href="{{ route('shop.index') }}" class="product-category-item" data-bg-color="#FFF3DA">
                    <img class="icon" src="{{ asset('brancy/images/shop/category/6.webp') }}" width="80" height="80" alt="Natural">
                    <h3 class="title">Natural</h3>
                </a>
            </div>
        </div>
    </div>
</section>
<!--== End Product Category Area Wrapper ==-->

<!--== Start Welcome Section ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h2 class="title">Welcome to {{ config('app.name') }}</h2>
                    <p>Your premier destination for beauty and cosmetic products</p>
                </div>
            </div>
        </div>
        <div class="row mt-8">
            <div class="col-lg-4">
                <div class="text-center p-4">
                    <i class="fa fa-truck fa-3x mb-3" style="color: #835BF4"></i>
                    <h4>Free Shipping</h4>
                    <p>On orders over $50</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center p-4">
                    <i class="fa fa-refresh fa-3x mb-3" style="color: #835BF4"></i>
                    <h4>Easy Returns</h4>
                    <p>30-day return policy</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center p-4">
                    <i class="fa fa-shield fa-3x mb-3" style="color: #835BF4"></i>
                    <h4>Secure Payment</h4>
                    <p>100% secure checkout</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Welcome Section ==-->
@endsection

@push('scripts')
<script>
    // Initialize hero slider
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.hero-slider-container', {
                loop: true,
                autoplay: {
                    delay: 5000,
                },
                pagination: {
                    el: '.hero-slider-pagination',
                    clickable: true,
                },
            });
        }
    });
</script>
@endpush
