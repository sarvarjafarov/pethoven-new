@extends('frontend.layouts.app')

@section('title', 'Home - ' . config('app.name'))

@section('meta_description', setting('seo.meta_description', 'Premium beauty and cosmetic salon offering quality skincare, makeup, and spa products.'))
@section('meta_keywords', setting('seo.meta_keywords', 'beauty salon, cosmetic products, skincare, makeup, spa products, beauty care'))

@push('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "Organization",
    "name": "{{ config('app.name') }}",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('brancy/images/logo.png') }}",
    "description": "{{ setting('seo.meta_description', 'Premium beauty and cosmetic salon') }}",
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
            @forelse($sliders as $slider)
            <div class="swiper-slide hero-slide-item">
                <div class="container">
                    <div class="row align-items-center position-relative">
                        <div class="col-12 col-md-6">
                            <div class="hero-slide-content">
                                <div class="hero-slide-text-img"><img src="{{ asset('brancy/images/slider/text-theme.webp') }}" width="427" height="232" alt="Image"></div>
                                <h2 class="hero-slide-title">{{ $slider->title }}</h2>
                                @if($slider->description)
                                <p class="hero-slide-desc">{{ $slider->description }}</p>
                                @endif
                                <a class="btn btn-border-dark" href="{{ $slider->button_link ?: route('shop.index') }}">{{ $slider->button_text }}</a>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="hero-slide-thumb">
                                @if($slider->image)
                                    <img src="{{ asset('storage/' . $slider->image) }}" width="841" height="832" alt="{{ $slider->title }}">
                                @else
                                    <img src="{{ asset('brancy/images/slider/slider' . $loop->iteration . '.webp') }}" width="841" height="832" alt="{{ $slider->title }}">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero-slide-text-shape"><img src="{{ asset('brancy/images/slider/text1.webp') }}" width="70" height="955" alt="Image"></div>
                <div class="hero-slide-social-shape"></div>
            </div>
            @empty
            <div class="swiper-slide hero-slide-item">
                <div class="container">
                    <div class="row align-items-center position-relative">
                        <div class="col-12 col-md-6">
                            <div class="hero-slide-content">
                                <div class="hero-slide-text-img"><img src="{{ asset('brancy/images/slider/text-theme.webp') }}" width="427" height="232" alt="Image"></div>
                                <h2 class="hero-slide-title">CLEAN FRESH</h2>
                                <p class="hero-slide-desc">Discover our premium beauty collection.</p>
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
            @endforelse
        </div>
        <!--== Add Pagination ==-->
        <div class="hero-slider-pagination"></div>
    </div>
    <div class="hero-slide-social-media">
        <a href="{{ setting('social.pinterest', '#') }}" target="_blank" rel="noopener"><i class="fa fa-pinterest-p"></i></a>
        <a href="{{ setting('social.twitter', '#') }}" target="_blank" rel="noopener"><i class="fa fa-twitter"></i></a>
        <a href="{{ setting('social.facebook', '#') }}" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a>
    </div>
</section>
<!--== End Hero Area Wrapper ==-->

<!--== Start Product Category Area Wrapper ==-->
@php
    $categories = setting('home.categories', []);
    $defaultCategories = [
        ['name' => 'Hare care', 'icon' => '', 'bg_color' => '', 'badge' => 'new'],
        ['name' => 'Skin care', 'icon' => '', 'bg_color' => '#FFEDB4', 'badge' => ''],
        ['name' => 'Lip stick', 'icon' => '', 'bg_color' => '#DFE4FF', 'badge' => ''],
        ['name' => 'Face skin', 'icon' => '', 'bg_color' => '#FFEACC', 'badge' => 'sale'],
        ['name' => 'Blusher', 'icon' => '', 'bg_color' => '#FFDAE0', 'badge' => ''],
        ['name' => 'Natural', 'icon' => '', 'bg_color' => '#FFF3DA', 'badge' => ''],
    ];
    if (empty($categories)) $categories = $defaultCategories;
@endphp
<section class="section-space pb-0">
    <div class="container">
        <div class="row g-3 g-sm-6">
            @foreach($categories as $index => $cat)
            <div class="col-6 col-lg-4 col-lg-2 col-xl-2 {{ $index >= 2 ? 'mt-lg-0 mt-sm-6 mt-4' : '' }}">
                <a href="{{ $cat['link'] ?? route('shop.index') }}" class="product-category-item" @if(!empty($cat['bg_color'])) data-bg-color="{{ $cat['bg_color'] }}" @endif>
                    @if(!empty($cat['icon']))
                        <img class="icon" src="{{ asset('storage/' . $cat['icon']) }}" width="80" height="80" alt="{{ $cat['name'] }}">
                    @else
                        <img class="icon" src="{{ asset('brancy/images/shop/category/' . ($index + 1) . '.webp') }}" width="80" height="80" alt="{{ $cat['name'] }}">
                    @endif
                    <h3 class="title">{{ $cat['name'] }}</h3>
                    @if(!empty($cat['badge']))
                        <span class="flag-new">{{ $cat['badge'] }}</span>
                    @endif
                </a>
            </div>
            @endforeach
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
                    <h2 class="title">{{ setting('home.featured_title', 'Top sale') }}</h2>
                    <p>{{ setting('home.featured_description', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis') }}</p>
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
            @forelse($banners->take(3) as $banner)
            <div class="col-sm-6 col-lg-4 {{ !$loop->first ? 'mt-sm-0 mt-6' : '' }}">
                <a href="{{ $banner->link ?: route('shop.index') }}" class="product-banner-item">
                    @if($banner->image)
                        <img src="{{ asset('storage/' . $banner->image) }}" width="370" height="370" alt="{{ $banner->title }}">
                    @else
                        <img src="{{ asset('brancy/images/shop/banner/' . $loop->iteration . '.webp') }}" width="370" height="370" alt="{{ $banner->title }}">
                    @endif
                </a>
            </div>
            @empty
            @for($i = 1; $i <= 3; $i++)
            <div class="col-sm-6 col-lg-4 {{ $i > 1 ? 'mt-sm-0 mt-6' : '' }}">
                <a href="{{ route('shop.index') }}" class="product-banner-item">
                    <img src="{{ asset('brancy/images/shop/banner/' . $i . '.webp') }}" width="370" height="370" alt="Banner {{ $i }}">
                </a>
            </div>
            @endfor
            @endforelse
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
                    <h2 class="title">{{ setting('home.blog_title', 'Blog posts') }}</h2>
                    <p>{{ setting('home.blog_description', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis') }}</p>
                </div>
            </div>
        </div>
        <div class="row mb-n9">
            @php
                $categoryClasses = ['post-category', 'post-category post-category-two', 'post-category post-category-three'];
                $categoryColors = [null, '#A49CFF', '#9CDBFF'];
            @endphp
            @forelse($latestPosts as $index => $post)
            <div class="col-sm-6 col-lg-4 mb-8">
                <div class="post-item">
                    <a href="{{ route('blog.show', $post->slug) }}" class="thumb">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" width="370" height="320" alt="{{ $post->title }}">
                        @else
                            <img src="{{ asset('brancy/images/blog/' . ($index + 1) . '.webp') }}" width="370" height="320" alt="{{ $post->title }}">
                        @endif
                    </a>
                    <div class="content">
                        <a class="{{ $categoryClasses[$index % 3] }}" @if($categoryColors[$index % 3]) data-bg-color="{{ $categoryColors[$index % 3] }}" @endif href="{{ route('blog.index', ['category' => $post->category]) }}">{{ $post->category }}</a>
                        <h4 class="title"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h4>
                        <ul class="meta">
                            <li class="author-info"><span>By:</span> {{ $post->author }}</li>
                            <li class="post-date">{{ $post->formatted_date }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>No blog posts yet. Check back soon!</p>
            </div>
            @endforelse
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
                    <h2 class="title">{{ setting('newsletter.title', 'Join with us') }}</h2>
                    <p>{{ setting('newsletter.description', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam.') }}</p>
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
