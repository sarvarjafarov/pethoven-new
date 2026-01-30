@extends('frontend.layouts.app')

@section('title', 'Home - ' . config('app.name'))

@section('meta_description', 'Pethoven - Premium beauty and cosmetic salon offering quality skincare, makeup, and spa products. Shop now for the best beauty products.')
@section('meta_keywords', 'beauty salon, cosmetic products, skincare, makeup, spa products, beauty care')

@push('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "Organization",
    "name": "{{ config('app.name') }}",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('brancy/images/logo.png') }}",
    "description": "Premium beauty and cosmetic salon",
    "address": {
        "@type": "PostalAddress",
        "addressCountry": "US"
    }
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "WebSite",
    "name": "{{ config('app.name') }}",
    "url": "{{ url('/') }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ route('shop.index') }}?search={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>
@endpush

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
                                <div class="hero-slide-text-img"><img src="{{ asset('brancy/images/slider/text-theme.webp') }}" width="427" height="232" alt="Image"></div>
                                <h2 class="hero-slide-title">CLEAN FRESH</h2>
                                <p class="hero-slide-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.</p>
                                <a class="btn btn-border-dark" href="{{ route('shop.index') }}">BUY NOW</a>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="hero-slide-thumb">
                                <img src="{{ asset('brancy/images/slider/slider1.webp') }}" width="841" height="832" alt="Image">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero-slide-text-shape"><img src="{{ asset('brancy/images/slider/text1.webp') }}" width="70" height="955" alt="Image"></div>
                <div class="hero-slide-social-shape"></div>
            </div>
            <div class="swiper-slide hero-slide-item">
                <div class="container">
                    <div class="row align-items-center position-relative">
                        <div class="col-12 col-md-6">
                            <div class="hero-slide-content">
                                <div class="hero-slide-text-img"><img src="{{ asset('brancy/images/slider/text-theme.webp') }}" width="427" height="232" alt="Image"></div>
                                <h2 class="hero-slide-title">Facial Cream</h2>
                                <p class="hero-slide-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.</p>
                                <a class="btn btn-border-dark" href="{{ route('shop.index') }}">BUY NOW</a>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="hero-slide-thumb">
                                <img src="{{ asset('brancy/images/slider/slider2.webp') }}" width="841" height="832" alt="Image">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero-slide-text-shape"><img src="{{ asset('brancy/images/slider/text1.webp') }}" width="70" height="955" alt="Image"></div>
                <div class="hero-slide-social-shape"></div>
            </div>
        </div>
        <!--== Add Pagination ==-->
        <div class="hero-slider-pagination"></div>
    </div>
    <div class="hero-slide-social-media">
        <a href="https://www.pinterest.com/" target="_blank" rel="noopener"><i class="fa fa-pinterest-p"></i></a>
        <a href="https://twitter.com/" target="_blank" rel="noopener"><i class="fa fa-twitter"></i></a>
        <a href="https://www.facebook.com/" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a>
    </div>
</section>
<!--== End Hero Area Wrapper ==-->

<!--== Start Product Category Area Wrapper ==-->
<section class="section-space pb-0">
    <div class="container">
        <div class="row g-3 g-sm-6">
            <div class="col-6 col-lg-4 col-lg-2 col-xl-2">
                <!--== Start Product Category Item ==-->
                <a href="{{ route('shop.index') }}" class="product-category-item">
                    <img class="icon" src="{{ asset('brancy/images/shop/category/1.webp') }}" width="70" height="80" alt="Image-HasTech">
                    <h3 class="title">Hare care</h3>
                    <span class="flag-new">new</span>
                </a>
                <!--== End Product Category Item ==-->
            </div>
            <div class="col-6 col-lg-4 col-lg-2 col-xl-2">
                <!--== Start Product Category Item ==-->
                <a href="{{ route('shop.index') }}" class="product-category-item" data-bg-color="#FFEDB4">
                    <img class="icon" src="{{ asset('brancy/images/shop/category/2.webp') }}" width="80" height="80" alt="Image-HasTech">
                    <h3 class="title">Skin care</h3>
                </a>
                <!--== End Product Category Item ==-->
            </div>
            <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-lg-0 mt-sm-6 mt-4">
                <!--== Start Product Category Item ==-->
                <a href="{{ route('shop.index') }}" class="product-category-item" data-bg-color="#DFE4FF">
                    <img class="icon" src="{{ asset('brancy/images/shop/category/3.webp') }}" width="80" height="80" alt="Image-HasTech">
                    <h3 class="title">Lip stick</h3>
                </a>
                <!--== End Product Category Item ==-->
            </div>
            <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-xl-0 mt-sm-6 mt-4">
                <!--== Start Product Category Item ==-->
                <a href="{{ route('shop.index') }}" class="product-category-item" data-bg-color="#FFEACC">
                    <img class="icon" src="{{ asset('brancy/images/shop/category/4.webp') }}" width="80" height="80" alt="Image-HasTech">
                    <h3 class="title">Face skin</h3>
                    <span data-bg-color="#835BF4" class="flag-new">sale</span>
                </a>
                <!--== End Product Category Item ==-->
            </div>
            <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-xl-0 mt-sm-6 mt-4">
                <!--== Start Product Category Item ==-->
                <a href="{{ route('shop.index') }}" class="product-category-item" data-bg-color="#FFDAE0">
                    <img class="icon" src="{{ asset('brancy/images/shop/category/5.webp') }}" width="80" height="80" alt="Image-HasTech">
                    <h3 class="title">Blusher</h3>
                </a>
                <!--== End Product Category Item ==-->
            </div>
            <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-xl-0 mt-sm-6 mt-4">
                <!--== Start Product Category Item ==-->
                <a href="{{ route('shop.index') }}" class="product-category-item" data-bg-color="#FFF3DA">
                    <img class="icon" src="{{ asset('brancy/images/shop/category/6.webp') }}" width="80" height="80" alt="Image-HasTech">
                    <h3 class="title">Natural</h3>
                </a>
                <!--== End Product Category Item ==-->
            </div>
        </div>
    </div>
</section>
<!--== End Product Category Area Wrapper ==-->

<!--== Start Product Area Wrapper ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h2 class="title">Top sale</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis</p>
                </div>
            </div>
        </div>
        <div class="row mb-n4 mb-sm-n10 g-3 g-sm-6">
            @foreach($featuredProducts->take(6) as $product)
                <div class="col-6 col-lg-4 mb-4 mb-sm-9">
                    @include('frontend.components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>
<!--== End Product Area Wrapper ==-->

<!--== Start Product Banner Area Wrapper ==-->
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-lg-4">
                <!--== Start Product Category Item ==-->
                <a href="{{ route('shop.index') }}" class="product-banner-item">
                    <img src="{{ asset('brancy/images/shop/banner/1.webp') }}" width="370" height="370" alt="Image-HasTech">
                </a>
                <!--== End Product Category Item ==-->
            </div>
            <div class="col-sm-6 col-lg-4 mt-sm-0 mt-6">
                <!--== Start Product Category Item ==-->
                <a href="{{ route('shop.index') }}" class="product-banner-item">
                    <img src="{{ asset('brancy/images/shop/banner/2.webp') }}" width="370" height="370" alt="Image-HasTech">
                </a>
                <!--== End Product Category Item ==-->
            </div>
            <div class="col-sm-6 col-lg-4 mt-lg-0 mt-6">
                <!--== Start Product Category Item ==-->
                <a href="{{ route('shop.index') }}" class="product-banner-item">
                    <img src="{{ asset('brancy/images/shop/banner/3.webp') }}" width="370" height="370" alt="Image-HasTech">
                </a>
                <!--== End Product Category Item ==-->
            </div>
        </div>
    </div>
</section>
<!--== End Product Banner Area Wrapper ==-->

<!--== Start Product Area Wrapper ==-->
<section class="section-space pb-0">
    <div class="container">
        <div class="row mb-n4 mb-sm-n10 g-3 g-sm-6">
            @foreach($featuredProducts->skip(3)->take(3) as $product)
                <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                    @include('frontend.components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>
<!--== End Product Area Wrapper ==-->

<!--== Start Blog Area Wrapper ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h2 class="title">Blog posts</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis</p>
                </div>
            </div>
        </div>
        <div class="row mb-n9">
            <div class="col-sm-6 col-lg-4 mb-8">
                <!--== Start Blog Item ==-->
                <div class="post-item">
                    <a href="{{ route('blog.show', 'demo-1') }}" class="thumb">
                        <img src="{{ asset('brancy/images/blog/1.webp') }}" width="370" height="320" alt="Blog Post">
                    </a>
                    <div class="content">
                        <a class="post-category" href="{{ route('blog.index', ['category' => 'beauty']) }}">beauty</a>
                        <h4 class="title"><a href="{{ route('blog.show', 'demo-1') }}">Lorem ipsum dolor sit amet consectetur adipiscing.</a></h4>
                        <ul class="meta">
                            <li class="author-info"><span>By:</span> Tomas Alva Addison</li>
                            <li class="post-date">February 13, 2022</li>
                        </ul>
                    </div>
                </div>
                <!--== End Blog Item ==-->
            </div>
            <div class="col-sm-6 col-lg-4 mb-8">
                <!--== Start Blog Item ==-->
                <div class="post-item">
                    <a href="{{ route('blog.show', 'demo-2') }}" class="thumb">
                        <img src="{{ asset('brancy/images/blog/2.webp') }}" width="370" height="320" alt="Blog Post">
                    </a>
                    <div class="content">
                        <a class="post-category post-category-two" data-bg-color="#A49CFF" href="{{ route('blog.index', ['category' => 'beauty']) }}">beauty</a>
                        <h4 class="title"><a href="{{ route('blog.show', 'demo-2') }}">Benefit of Hot Ston Spa for your health & life.</a></h4>
                        <ul class="meta">
                            <li class="author-info"><span>By:</span> Tomas Alva Addison</li>
                            <li class="post-date">February 13, 2022</li>
                        </ul>
                    </div>
                </div>
                <!--== End Blog Item ==-->
            </div>
            <div class="col-sm-6 col-lg-4 mb-8">
                <!--== Start Blog Item ==-->
                <div class="post-item">
                    <a href="{{ route('blog.show', 'demo-3') }}" class="thumb">
                        <img src="{{ asset('brancy/images/blog/3.webp') }}" width="370" height="320" alt="Blog Post">
                    </a>
                    <div class="content">
                        <a class="post-category post-category-three" data-bg-color="#9CDBFF" href="{{ route('blog.index', ['category' => 'beauty']) }}">beauty</a>
                        <h4 class="title"><a href="{{ route('blog.show', 'demo-3') }}">Facial Scrub is natural treatment for face.</a></h4>
                        <ul class="meta">
                            <li class="author-info"><span>By:</span> Tomas Alva Addison</li>
                            <li class="post-date">February 13, 2022</li>
                        </ul>
                    </div>
                </div>
                <!--== End Blog Item ==-->
            </div>
        </div>
    </div>
</section>
<!--== End Blog Area Wrapper ==-->

<!--== Start News Letter Area Wrapper ==-->
<section class="section-space pt-0">
    <div class="container">
        <div class="newsletter-content-wrap" data-bg-img="{{ asset('brancy/images/photos/bg1.webp') }}">
            <div class="newsletter-content">
                <div class="section-title mb-0">
                    <h2 class="title">Join with us</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam.</p>
                </div>
            </div>
            <div class="newsletter-form">
                <form action="{{ route('newsletter.subscribe') }}" method="POST" id="newsletter-form">
                    @csrf
                    <input type="email" class="form-control" name="email" placeholder="enter your email" required>
                    <button class="btn-submit" type="submit"><i class="fa fa-paper-plane"></i></button>
                </form>
                <div id="newsletter-message" class="mt-3" style="display: none;"></div>
            </div>
        </div>
    </div>
</section>
<!--== End News Letter Area Wrapper ==-->
@endsection
