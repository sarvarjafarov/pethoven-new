@extends('frontend.layouts.app')

@section('title', ($post->title ?? 'Blog Post') . ' - ' . config('app.name'))

@section('content')
@php
    $cdn = 'https://template.hasthemes.com/brancy/brancy/assets/images';

    // Handle featured image
    $postFeaturedSrc = $post->featured_image
        ? media_url($post->featured_image)
        : "{$cdn}/blog/blog-detail1.webp";

    // Get previous/next posts for navigation
    $allPosts = \App\Models\BlogPost::published()->latest('published_at')->get();
    $currentIndex = $allPosts->search(fn($p) => $p->id === $post->id);
    $prevPost = $currentIndex !== false && $currentIndex > 0 ? $allPosts[$currentIndex - 1] : null;
    $nextPost = $currentIndex !== false && $currentIndex < $allPosts->count() - 1 ? $allPosts[$currentIndex + 1] : null;

    // Parse content into paragraphs
    $content = $post->content ?? '';
    $paragraphs = array_filter(explode("\n\n", $content));
    $paragraphs = array_values($paragraphs);
@endphp

<!--== Start Page Header Area Wrapper ==-->
<nav aria-label="breadcrumb" class="breadcrumb-style1">
    <div class="container">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 40) }}</li>
        </ol>
    </div>
</nav>
<!--== End Page Header Area Wrapper ==-->

<!--== Start Blog Detail Area Wrapper ==-->
<section class="section-space pb-0">
    <div class="container">
        <div class="blog-detail">
            <h3 class="blog-detail-title">{{ $post->title }}</h3>
            <div class="blog-detail-category">
                <a class="category" href="{{ route('blog.index', ['category' => $post->category]) }}">{{ $post->category }}</a>
            </div>
            <img class="blog-detail-img mb-7 mb-lg-10" src="{{ $postFeaturedSrc }}" width="1170" height="1012" alt="{{ $post->title }}">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row">
                        <div class="col-md-7">
                            <ul class="blog-detail-meta">
                                <li class="meta-admin"><img src="{{ $cdn }}/blog/admin.webp" alt="Image"> {{ $post->author }}</li>
                                <li>{{ $post->formatted_date }}</li>
                            </ul>
                        </div>
                        <div class="col-md-5">
                            <div class="blog-detail-social">
                                <span>Share:</span>
                                <a href="{{ setting('social.pinterest', '#') }}" target="_blank" rel="noopener"><i class="fa fa-pinterest-p"></i></a>
                                <a href="{{ setting('social.twitter', '#') }}" target="_blank" rel="noopener"><i class="fa fa-twitter"></i></a>
                                <a href="{{ setting('social.facebook', '#') }}" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="blog-detail-content mt-4 mt-lg-7">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="section-space pb-0">
            <!--== Start Product Banner ==-->
            <a href="{{ route('shop.index') }}" class="product-banner-item">
                @php $blogBanner = setting('blog.banner_image'); @endphp
                @if($blogBanner)
                    <img src="{{ media_url($blogBanner) }}" width="1170" height="200" alt="Shop Banner">
                @else
                    <img src="{{ $cdn }}/shop/banner/9.webp" width="1170" height="200" alt="Shop Banner">
                @endif
            </a>
            <!--== End Product Banner ==-->
        </div>

        <div class="row justify-content-between align-items-center pt-10 mt-4 section-space">
            <div class="col-sm-6">
                @if($prevPost)
                    <a href="{{ route('blog.show', $prevPost->slug) }}" class="blog-next-previous">
                        <div class="thumb">
                            <span class="arrow">PREV</span>
                            <img src="{{ $cdn }}/blog/next-previous1.webp" width="93" height="80" alt="Image">
                        </div>
                        <div class="content">
                            <h4 class="title">{{ $prevPost->title }}</h4>
                            <h5 class="post-date">{{ $prevPost->formatted_date }}</h5>
                        </div>
                    </a>
                @endif
            </div>
            <div class="col-sm-6">
                @if($nextPost)
                    <a href="{{ route('blog.show', $nextPost->slug) }}" class="blog-next-previous blog-next">
                        <div class="thumb">
                            <span class="arrow">NEXT</span>
                            <img src="{{ $cdn }}/blog/next-previous2.webp" width="93" height="80" alt="Image">
                        </div>
                        <div class="content">
                            <h4 class="title">{{ $nextPost->title }}</h4>
                            <h5 class="post-date">{{ $nextPost->formatted_date }}</h5>
                        </div>
                    </a>
                @endif
            </div>
        </div>

        <div class="blog-comment-form-wrap">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h4 class="blog-comment-form-title">Comment</h4>
                    <div class="blog-comment-form-info">
                        <div class="blog-comment-form-social">
                            <span>Share:</span>
                            <a href="{{ setting('social.pinterest', '#') }}" target="_blank" rel="noopener"><i class="fa fa-pinterest-p"></i></a>
                            <a href="{{ setting('social.twitter', '#') }}" target="_blank" rel="noopener"><i class="fa fa-twitter"></i></a>
                            <a href="{{ setting('social.facebook', '#') }}" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a>
                        </div>
                        <select class="blog-comment-form-select">
                            <option selected>Short By Newest</option>
                            <option value="1">Best</option>
                            <option value="2">Newest</option>
                            <option value="3">Oldest</option>
                        </select>
                    </div>
                    <div class="blog-comment-form">
                        <img class="blog-comment-img" src="{{ $cdn }}/blog/form1.webp" width="110" height="110" alt="Image">
                        <textarea class="blog-comment-control" placeholder="type your comment"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Blog Detail Area Wrapper ==-->

<!--== Start Related Blog Area Wrapper ==-->
@if($relatedPosts->count() > 0)
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
            @foreach($relatedPosts->take(3) as $index => $relatedPost)
                <div class="col-sm-6 col-lg-4 mb-8">
                    <div class="post-item">
                        <a href="{{ route('blog.show', $relatedPost->slug) }}" class="thumb">
                            @if($relatedPost->featured_image)
                                <img src="{{ media_url($relatedPost->featured_image) }}" width="370" height="320" alt="{{ $relatedPost->title }}">
                            @else
                                <img src="{{ $cdn }}/blog/{{ ($index % 6) + 1 }}.webp" width="370" height="320" alt="{{ $relatedPost->title }}">
                            @endif
                        </a>
                        <div class="content">
                            <a class="{{ $categoryClasses[$index % 3] }}" @if($categoryColors[$index % 3]) data-bg-color="{{ $categoryColors[$index % 3] }}" @endif href="{{ route('blog.index', ['category' => $relatedPost->category]) }}">{{ $relatedPost->category }}</a>
                            <h4 class="title"><a href="{{ route('blog.show', $relatedPost->slug) }}">{{ $relatedPost->title }}</a></h4>
                            <ul class="meta">
                                <li class="author-info"><span>By:</span> <a href="{{ route('blog.index') }}">{{ $relatedPost->author }}</a></li>
                                <li class="post-date">{{ $relatedPost->formatted_date }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
<!--== End Related Blog Area Wrapper ==-->

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
