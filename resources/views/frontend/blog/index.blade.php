@extends('frontend.layouts.app')

@section('title', 'Blog - ' . config('app.name'))

@section('content')
@php
    $cdn = 'https://template.hasthemes.com/brancy/brancy/assets/images';
    $categoryClasses = ['post-category', 'post-category post-category-two', 'post-category post-category-three'];
    $categoryColors = [null, '#A49CFF', '#9CDBFF'];
    $blogImages = ['1.webp', '2.webp', '3.webp', '4.webp', '5.webp', '6.webp', '7.webp'];
@endphp

<!--== Start Page Header Area Wrapper ==-->
<nav aria-label="breadcrumb" class="breadcrumb-style1 mb-10">
    <div class="container">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Blog</li>
        </ol>
    </div>
</nav>

<section class="page-header-area page-header-style2-area" data-bg-img="{{ $cdn }}/photos/page-header1.webp">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="page-header-content page-header-st2-content">
                    <div class="title-img"><img src="{{ $cdn }}/photos/page-header-text1.webp" alt="Image"></div>
                    <h2 class="page-header-title">{{ setting('blog.header_title', "Whats the beauty secrets?") }}</h2>
                    <p class="page-header-desc">{{ setting('blog.header_description', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Page Header Area Wrapper ==-->

@if($posts->count() > 0)
<!--== Start Blog Area Wrapper ==-->
<section class="section-space pb-0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h2 class="title">{{ setting('blog.new_posts_title', 'New Posts') }}</h2>
                    <p>{{ setting('blog.new_posts_description', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis') }}</p>
                </div>
            </div>
        </div>

        <div class="row mb-n9">
            @foreach($posts->take(2) as $index => $post)
            <div class="col-sm-6 col-lg-4 mb-8">
                <div class="post-item">
                    <a href="{{ route('blog.show', $post->slug) }}" class="thumb">
                        @if($post->featured_image)
                            <img src="{{ media_url($post->featured_image) }}" width="370" height="320" alt="{{ $post->title }}">
                        @else
                            <img src="{{ $cdn }}/blog/{{ $blogImages[$index % count($blogImages)] }}" width="370" height="320" alt="{{ $post->title }}">
                        @endif
                    </a>
                    <div class="content">
                        <a class="{{ $categoryClasses[$index % 3] }}" @if($categoryColors[$index % 3]) data-bg-color="{{ $categoryColors[$index % 3] }}" @endif href="{{ route('blog.index', ['category' => $post->category]) }}">{{ $post->category }}</a>
                        <h4 class="title"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h4>
                        <ul class="meta">
                            <li class="author-info"><span>By:</span> <a href="{{ route('blog.index') }}">{{ $post->author }}</a></li>
                            <li class="post-date">{{ $post->formatted_date }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach

            @if($posts->count() > 2)
            <div class="col-sm-12 col-lg-4 mb-8">
                <div class="row mb-n10">
                    @foreach($posts->slice(2, 2) as $post)
                    <div class="col-md-6 col-lg-12 mb-10 {{ !$loop->first ? 'pt-0 pt-lg-4' : '' }}">
                        <div class="post-item">
                            <div class="content">
                                <h4 class="title mt-0"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h4>
                                <p class="desc">{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}</p>
                                <a class="btn-link" href="{{ route('blog.show', $post->slug) }}">Learn more</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
<!--== End Blog Area Wrapper ==-->

@if($posts->count() > 4)
<!--== Start Blog Area Wrapper ==-->
<section class="section-space">
    <div class="container">
        <div class="row mb-n9">
            @foreach($posts->slice(4, 2) as $index => $post)
            <div class="col-sm-6 mb-8">
                <div class="post-item">
                    <a href="{{ route('blog.show', $post->slug) }}" class="thumb">
                        @if($post->featured_image)
                            <img src="{{ media_url($post->featured_image) }}" width="570" height="340" alt="{{ $post->title }}">
                        @else
                            <img src="{{ $cdn }}/blog/col6-{{ $index + 1 }}.webp" width="570" height="340" alt="{{ $post->title }}">
                        @endif
                    </a>
                    <div class="content">
                        <h4 class="title"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h4>
                        <p class="desc">{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}</p>
                        <ul class="meta">
                            <li class="author-info"><span>By:</span> <a href="{{ route('blog.index') }}">{{ $post->author }}</a></li>
                            <li class="post-date">{{ $post->formatted_date }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!--== End Blog Area Wrapper ==-->
@endif

@if($posts->count() > 6)
<!--== Start Blog Area Wrapper ==-->
<section class="section-space pt-0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h2 class="title">{{ setting('blog.others_title', 'Others Posts') }}</h2>
                    <p>{{ setting('blog.others_description', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis') }}</p>
                </div>
            </div>
        </div>
        <div class="row mb-n9">
            @foreach($posts->slice(6) as $index => $post)
                <div class="col-sm-6 col-lg-4 mb-8">
                    <div class="post-item">
                        <a href="{{ route('blog.show', $post->slug) }}" class="thumb">
                            @if($post->featured_image)
                                <img src="{{ media_url($post->featured_image) }}" width="370" height="320" alt="{{ $post->title }}">
                            @else
                                <img src="{{ $cdn }}/blog/{{ $blogImages[$index % count($blogImages)] }}" width="370" height="320" alt="{{ $post->title }}">
                            @endif
                        </a>
                        <div class="content">
                            <a class="{{ $categoryClasses[$index % 3] }}" @if($categoryColors[$index % 3]) data-bg-color="{{ $categoryColors[$index % 3] }}" @endif href="{{ route('blog.index', ['category' => $post->category]) }}">{{ $post->category }}</a>
                            <h4 class="title"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h4>
                            <ul class="meta">
                                <li class="author-info"><span>By:</span> <a href="{{ route('blog.index') }}">{{ $post->author }}</a></li>
                                <li class="post-date">{{ $post->formatted_date }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!--== End Blog Area Wrapper ==-->
@endif

@else
<!--== No Posts ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h3>No blog posts yet</h3>
                <p>Check back soon for new content!</p>
            </div>
        </div>
    </div>
</section>
@endif

<!--== Start Product Banner Area Wrapper ==-->
<section class="section-space pt-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-lg-8">
                <a href="{{ route('shop.index') }}" class="product-banner-item">
                    @php $blogBanner = setting('blog.banner_image'); @endphp
                    @if($blogBanner)
                        <img src="{{ media_url($blogBanner) }}" width="770" height="250" alt="Shop Banner">
                    @else
                        <img src="{{ $cdn }}/shop/banner/8.webp" width="770" height="250" alt="Shop Banner">
                    @endif
                </a>
            </div>
        </div>
    </div>
</section>
<!--== End Product Banner Area Wrapper ==-->

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
                    <input type="email" name="email" class="form-control" placeholder="enter your email" required>
                    <button class="btn-submit" type="submit"><i class="fa fa-paper-plane"></i></button>
                </form>
                <div id="newsletter-message" class="mt-3" style="display: none;"></div>
            </div>
        </div>
    </div>
</section>
<!--== End News Letter Area Wrapper ==-->
@endsection
