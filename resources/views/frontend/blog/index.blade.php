@extends('frontend.layouts.app')

@section('title', 'Blog - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">Blog</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>Blog</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area ==-->

<!--== Start Blog Area ==-->
<section class="section-space">
    <div class="container">
        <div class="row mb-6">
            <div class="col-lg-8">
                @if(request('search'))
                    <div class="alert alert-info">
                        <strong>Search results for:</strong> "{{ request('search') }}"
                        <a href="{{ route('blog.index') }}" class="float-end text-decoration-none">Clear search</a>
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <form action="{{ route('blog.index') }}" method="GET" class="d-flex gap-2">
                    <input type="search" name="search" class="form-control" placeholder="Search blog..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        @if($categories->isNotEmpty())
            <div class="row mb-8">
                <div class="col-12">
                    <div class="blog-categories">
                        <h5 class="mb-4">Filter by Category:</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('blog.index') }}"
                               class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                All Posts
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('blog.index', ['category' => $category]) }}"
                                   class="btn {{ request('category') == $category ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    {{ ucfirst($category) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="blog-card" style="background: white; border-radius: 15px; overflow: hidden; height: 100%;">
                        <div class="blog-thumb">
                            @if($post->featured_image)
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-100" style="height: 250px; object-fit: cover;">
                                </a>
                            @else
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <img src="{{ asset('brancy/images/blog/1.webp') }}" alt="{{ $post->title }}" class="w-100" style="height: 250px; object-fit: cover;">
                                </a>
                            @endif
                        </div>
                        <div class="blog-content p-4">
                            @if($post->category)
                                <span class="badge" style="background-color: {{ '#' . substr(md5($post->category), 0, 6) }}; color: white; padding: 8px 20px; border-radius: 20px; font-size: 12px; margin-bottom: 15px; display: inline-block;">
                                    {{ strtoupper($post->category) }}
                                </span>
                            @endif
                            <h4 style="font-size: 20px; font-weight: 700; margin-bottom: 15px;">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">
                                    {{ $post->title }}
                                </a>
                            </h4>
                            @if($post->excerpt)
                                <p style="color: #666; font-size: 14px; margin-bottom: 15px;">
                                    {{ Str::limit($post->excerpt, 120) }}
                                </p>
                            @endif
                            <p style="color: #999; font-size: 14px;">
                                BY: {{ strtoupper($post->author) }} &nbsp;&nbsp; {{ $post->formatted_date }}
                            </p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-sm btn-outline-primary mt-2">
                                Read More <i class="fa fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-10">
                        <i class="fa fa-file-text-o fa-4x mb-4 text-muted"></i>
                        <h4>No Blog Posts Found</h4>
                        <p class="text-muted">Check back later for new articles.</p>
                        @if(request('search') || request('category'))
                            <a href="{{ route('blog.index') }}" class="btn btn-primary mt-4">View All Posts</a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="row mt-10">
                <div class="col-12">
                    <div class="pagination-area">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
<!--== End Blog Area ==-->
@endsection
