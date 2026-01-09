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
                                <h5 class="hero-slide-subtitle" style="color: #F4A29B; font-size: 48px; font-family: 'Pacifico', cursive; font-weight: 400; margin-bottom: 15px;">Best</h5>
                                <h2 class="hero-slide-title" style="font-size: 72px; font-weight: 800; line-height: 1.1; margin-bottom: 20px;">CLEAN FRESH</h2>
                                <p class="hero-slide-desc" style="font-size: 16px; color: #666; margin-bottom: 30px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.</p>
                                <a class="btn btn-border-dark" href="{{ route('shop.index') }}" style="padding: 15px 40px; border-radius: 30px; font-weight: 600; letter-spacing: 2px;">BUY NOW</a>
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

<!--== Start Top Sale Section ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center mb-8">
                    <h2 class="title" style="font-size: 48px; font-weight: 800; margin-bottom: 15px;">Top Sale</h2>
                    <p style="color: #666;">Lorem ipsum dolor sit amet, consectetur adipiscing elit<br>ut aliquam, purus sit amet luctus venenatis</p>
                </div>
            </div>
        </div>
        <div class="row g-4 g-sm-5">
            @foreach($featuredProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('frontend.components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>
<!--== End Top Sale Section ==-->

<!--== Start Blog Section ==-->
<section class="section-space" style="background-color: #f8f8f8;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center mb-8">
                    <h2 class="title" style="font-size: 48px; font-weight: 800; margin-bottom: 15px;">Blog Posts</h2>
                    <p style="color: #666;">Lorem ipsum dolor sit amet, consectetur adipiscing elit<br>ut aliquam, purus sit amet luctus venenatis</p>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="blog-card" style="background: white; border-radius: 15px; overflow: hidden;">
                    <div class="blog-thumb">
                        <img src="{{ asset('brancy/images/blog/1.webp') }}" alt="Blog" class="w-100">
                    </div>
                    <div class="blog-content p-4">
                        <span class="badge" style="background-color: #FFB8B8; color: white; padding: 8px 20px; border-radius: 20px; font-size: 12px; margin-bottom: 15px; display: inline-block;">BEAUTY</span>
                        <h4 style="font-size: 20px; font-weight: 700; margin-bottom: 15px;">Lorem ipsum dolor sit amet consectetur adipiscing.</h4>
                        <p style="color: #999; font-size: 14px;">BY: TOMAS DE MOMEN &nbsp;&nbsp; FEBRUARY 13, 2022</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="blog-card" style="background: white; border-radius: 15px; overflow: hidden;">
                    <div class="blog-thumb">
                        <img src="{{ asset('brancy/images/blog/2.webp') }}" alt="Blog" class="w-100">
                    </div>
                    <div class="blog-content p-4">
                        <span class="badge" style="background-color: #C4B5FD; color: white; padding: 8px 20px; border-radius: 20px; font-size: 12px; margin-bottom: 15px; display: inline-block;">BEAUTY</span>
                        <h4 style="font-size: 20px; font-weight: 700; margin-bottom: 15px;">Facial Scrub is natural treatment for face.</h4>
                        <p style="color: #999; font-size: 14px;">BY: TOMAS DE MOMEN &nbsp;&nbsp; FEBRUARY 13, 2022</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="blog-card" style="background: white; border-radius: 15px; overflow: hidden;">
                    <div class="blog-thumb">
                        <img src="{{ asset('brancy/images/blog/3.webp') }}" alt="Blog" class="w-100">
                    </div>
                    <div class="blog-content p-4">
                        <span class="badge" style="background-color: #9DD6FF; color: white; padding: 8px 20px; border-radius: 20px; font-size: 12px; margin-bottom: 15px; display: inline-block;">BEAUTY</span>
                        <h4 style="font-size: 20px; font-weight: 700; margin-bottom: 15px;">Benefit of Hot Ston Spa for your health & life.</h4>
                        <p style="color: #999; font-size: 14px;">BY: TOMAS DE MOMEN &nbsp;&nbsp; FEBRUARY 13, 2022</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Blog Section ==-->

<!--== Start Newsletter Section ==-->
<section class="section-space" style="background: linear-gradient(135deg, #FFDEE2 0%, #FFE5D4 25%, #FFF8E1 50%, #E8F5FF 75%, #F3E5F5 100%); border-radius: 20px; margin: 0 auto; max-width: 1200px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 style="font-size: 48px; font-weight: 800; margin-bottom: 15px; color: #1a1a2e;">Join With Us</h2>
                <p style="color: #666; font-size: 16px;">Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit ut aliquam.</p>
            </div>
            <div class="col-md-6">
                <form class="newsletter-form">
                    <div class="input-group" style="box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 50px; overflow: hidden; background: white;">
                        <input type="email" class="form-control" placeholder="enter your email" style="border: none; padding: 20px 30px; font-size: 16px;">
                        <button class="btn" type="submit" style="background-color: #E87B63; color: white; border: none; padding: 15px 35px; border-radius: 0;">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!--== End Newsletter Section ==-->
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
