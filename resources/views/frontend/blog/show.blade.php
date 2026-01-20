@extends('frontend.layouts.app')

@section('title', $post->title . ' - ' . config('app.name'))

@section('content')
@php
    $postFeatured = $post->featured_image ?? null;
    $postFeaturedSrc = $postFeatured
        ? (\Illuminate\Support\Str::startsWith($postFeatured, ['http://', 'https://']) ? $postFeatured : asset($postFeatured))
        : null;
@endphp
<!--== Start Page Header Area ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">{{ $post->title }}</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li><a href="{{ route('blog.index') }}">Blog</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>{{ Str::limit($post->title, 30) }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area ==-->

<!--== Start Blog Detail Area ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="blog-detail">
                    @if($postFeaturedSrc)
                        <div class="blog-detail-thumb mb-6" style="border-radius: 15px; overflow: hidden;">
                            <img src="{{ $postFeaturedSrc }}" alt="{{ $post->title }}" class="w-100">
                        </div>
                    @endif

                    <div class="blog-detail-meta mb-4">
                        @if($post->category)
                            <span class="badge" style="background-color: {{ '#' . substr(md5($post->category), 0, 6) }}; color: white; padding: 8px 20px; border-radius: 20px; font-size: 12px;">
                                {{ strtoupper($post->category) }}
                            </span>
                        @endif
                        <div class="mt-3" style="color: #999;">
                            <i class="fa fa-user me-2"></i>{{ $post->author }}
                            <span class="mx-3">|</span>
                            <i class="fa fa-calendar me-2"></i>{{ $post->formatted_date }}
                        </div>
                    </div>

                    <h1 class="blog-detail-title mb-6" style="font-size: 36px; font-weight: 800; line-height: 1.3;">
                        {{ $post->title }}
                    </h1>

                    @if($post->excerpt)
                        <div class="blog-detail-excerpt mb-6" style="font-size: 18px; color: #666; font-style: italic; padding: 20px; background-color: #f8f8f8; border-left: 4px solid #E87B63;">
                            {{ $post->excerpt }}
                        </div>
                    @endif

                    <div class="blog-detail-content" style="font-size: 16px; line-height: 1.8; color: #333;">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <div class="blog-detail-footer mt-8 pt-6" style="border-top: 2px solid #f0f0f0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if($post->category)
                                    <a href="{{ route('blog.index', ['category' => $post->category]) }}" class="btn btn-outline-primary">
                                        <i class="fa fa-folder me-2"></i>More in {{ ucfirst($post->category) }}
                                    </a>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary">
                                    <i class="fa fa-arrow-left me-2"></i>Back to Blog
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-lg-4 mt-lg-0 mt-8">
                <aside class="blog-sidebar">
                    <div class="widget mb-6">
                        <h5 class="widget-title mb-4">Search</h5>
                        <form action="{{ route('blog.index') }}" method="GET">
                            <div class="input-group">
                                <input type="search" name="search" class="form-control" placeholder="Search blog...">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    @if($relatedPosts->isNotEmpty())
                        <div class="widget">
                            <h5 class="widget-title mb-4">Related Posts</h5>
                            <div class="related-posts">
                                @foreach($relatedPosts as $relatedPost)
                                    <div class="related-post-item mb-4 pb-4" style="border-bottom: 1px solid #eee;">
                                        @php
                                            $relatedFeatured = $relatedPost->featured_image ?? null;
                                            $relatedFeaturedSrc = $relatedFeatured
                                                ? (\Illuminate\Support\Str::startsWith($relatedFeatured, ['http://', 'https://']) ? $relatedFeatured : asset($relatedFeatured))
                                                : null;
                                        @endphp
                                        @if($relatedFeaturedSrc)
                                            <a href="{{ route('blog.show', $relatedPost->slug) }}" class="d-flex gap-3 text-decoration-none">
                                                <img
                                                    src="{{ $relatedFeaturedSrc }}"
                                                    alt="{{ $relatedPost->title }}"
                                                    style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;"
                                                >
                                                <div>
                                                    <h6 class="mb-2 text-dark">{{ Str::limit($relatedPost->title, 50) }}</h6>
                                                    <small class="text-muted">{{ $relatedPost->formatted_date }}</small>
                                                </div>
                                            </a>
                                        @else
                                            <a href="{{ route('blog.show', $relatedPost->slug) }}" class="text-decoration-none">
                                                <h6 class="mb-2 text-dark">{{ Str::limit($relatedPost->title, 60) }}</h6>
                                                <small class="text-muted">{{ $relatedPost->formatted_date }}</small>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</section>
<!--== End Blog Detail Area ==-->
@endsection
