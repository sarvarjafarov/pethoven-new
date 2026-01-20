@extends('frontend.layouts.app')

@section('title', 'Blog - ' . config('app.name'))

@section('content')
@php
    /** @var \Illuminate\Support\Collection|\App\Models\BlogPost[] $posts */
    $cdn = 'https://template.hasthemes.com/brancy/brancy/assets/images';

    $postLink = function ($post, string $demoSlug) {
        return route('blog.show', $post?->slug ?: $demoSlug);
    };

    $postTitle = function ($post, $fallback) {
        return $post?->title ?: $fallback;
    };

    $postDesc = function ($post, $fallback) {
        return $post?->excerpt ?: $fallback;
    };

    $postAuthor = function ($post) {
        return $post?->author ?: 'Tomas De Momen';
    };

    $postDate = function ($post) {
        return $post?->formatted_date ?: ($post?->published_at?->format('F d, Y') ?: 'February 13, 2022');
    };
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
                    <h2 class="page-header-title">Whats the beauty secrets?</h2>
                    <p class="page-header-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Page Header Area Wrapper ==-->

<!--== Start Blog Area Wrapper ==-->
<section class="section-space pb-0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h2 class="title">New Posts</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis</p>
                </div>
            </div>
        </div>

        <div class="row mb-n9">
            <div class="col-sm-6 col-lg-4 mb-8">
                <!--== Start Blog Item ==-->
                <div class="post-item">
                    <a href="{{ $postLink($posts[0] ?? null, 'demo-1') }}" class="thumb">
                        <img src="{{ $cdn }}/blog/1.webp" width="370" height="320" alt="Image-HasTech">
                    </a>
                    <div class="content">
                        <a class="post-category" href="{{ route('blog.index') }}">beauty</a>
                        <h4 class="title"><a href="{{ $postLink($posts[0] ?? null, 'demo-1') }}">{{ $postTitle($posts[0] ?? null, 'Lorem ipsum dolor sit amet consectetur adipiscing.') }}</a></h4>
                        <ul class="meta">
                            <li class="author-info"><span>By:</span> <a href="{{ route('blog.index') }}">{{ $postAuthor($posts[0] ?? null) }}</a></li>
                            <li class="post-date">{{ $postDate($posts[0] ?? null) }}</li>
                        </ul>
                    </div>
                </div>
                <!--== End Blog Item ==-->
            </div>

            <div class="col-sm-6 col-lg-4 mb-8">
                <!--== Start Blog Item ==-->
                <div class="post-item">
                    <a href="{{ $postLink($posts[1] ?? null, 'demo-2') }}" class="thumb">
                        <img src="{{ $cdn }}/blog/4.webp" width="370" height="320" alt="Image-HasTech">
                    </a>
                    <div class="content">
                        <a class="post-category post-category-two" data-bg-color="#A49CFF" href="{{ route('blog.index') }}">beauty</a>
                        <h4 class="title"><a href="{{ $postLink($posts[1] ?? null, 'demo-2') }}">{{ $postTitle($posts[1] ?? null, 'Benefit of Hot Ston Spa for your health & life.') }}</a></h4>
                        <ul class="meta">
                            <li class="author-info"><span>By:</span> <a href="{{ route('blog.index') }}">{{ $postAuthor($posts[1] ?? null) }}</a></li>
                            <li class="post-date">{{ $postDate($posts[1] ?? null) }}</li>
                        </ul>
                    </div>
                </div>
                <!--== End Blog Item ==-->
            </div>

            <div class="col-sm-12 col-lg-4 mb-8">
                <div class="row mb-n10">
                    <div class="col-md-6 col-lg-12 mb-10">
                        <!--== Start Blog Item ==-->
                        <div class="post-item">
                            <div class="content">
                                <h4 class="title mt-0"><a href="{{ $postLink($posts[2] ?? null, 'demo-1') }}">{{ $postTitle($posts[2] ?? null, 'Lorem ipsum dolor sit amet, consectetur adipiscing') }}</a></h4>
                                <p class="desc">{{ $postDesc($posts[2] ?? null, 'Lorem ipsum dolor sit amet, conseur adipiscing elit ut aliqua, purus sit amet luctus venenatis.') }}</p>
                                <a class="btn-link" href="{{ $postLink($posts[2] ?? null, 'demo-1') }}">Learn more</a>
                            </div>
                        </div>
                        <!--== End Blog Item ==-->
                    </div>
                    <div class="col-md-6 col-lg-12 mb-10 pt-0 pt-lg-4">
                        <!--== Start Blog Item ==-->
                        <div class="post-item">
                            <div class="content">
                                <h4 class="title mt-0"><a href="{{ $postLink($posts[3] ?? null, 'demo-3') }}">{{ $postTitle($posts[3] ?? null, 'Facial Scrub is natural treatment for face.') }}</a></h4>
                                <p class="desc">{{ $postDesc($posts[3] ?? null, 'Lorem ipsum dolor sit amet, conseur adipiscing elit ut aliqua, purus.') }}</p>
                                <a class="btn-link" href="{{ $postLink($posts[3] ?? null, 'demo-3') }}">Learn more</a>
                            </div>
                        </div>
                        <!--== End Blog Item ==-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Blog Area Wrapper ==-->

<!--== Start Blog Area Wrapper ==-->
<section class="section-space">
    <div class="container">
        <div class="row mb-n9">
            <div class="col-sm-6 mb-8">
                <!--== Start Blog Item ==-->
                <div class="post-item">
                    <a href="{{ $postLink($posts[4] ?? null, 'demo-3') }}" class="thumb">
                        <img src="{{ $cdn }}/blog/col6-1.webp" width="570" height="340" alt="Image-HasTech">
                    </a>
                    <div class="content">
                        <h4 class="title"><a href="{{ $postLink($posts[4] ?? null, 'demo-3') }}">{{ $postTitle($posts[4] ?? null, 'Facial Scrub is natural treatment for face.') }}</a></h4>
                        <p class="desc">{{ $postDesc($posts[4] ?? null, 'Lorem ipsum dolor sit amet, conseur adipiscing elit ut aliqua, purus sit amet luctus venenatis.') }}</p>
                        <ul class="meta">
                            <li class="author-info"><span>By:</span> <a href="{{ route('blog.index') }}">{{ $postAuthor($posts[4] ?? null) }}</a></li>
                            <li class="post-date">{{ $postDate($posts[4] ?? null) }}</li>
                        </ul>
                    </div>
                </div>
                <!--== End Blog Item ==-->
            </div>

            <div class="col-sm-6 mb-8">
                <!--== Start Blog Item ==-->
                <div class="post-item">
                    <a href="{{ $postLink($posts[5] ?? null, 'demo-2') }}" class="thumb">
                        <img src="{{ $cdn }}/blog/col6-2.webp" width="570" height="340" alt="Image-HasTech">
                    </a>
                    <div class="content">
                        <h4 class="title"><a href="{{ $postLink($posts[5] ?? null, 'demo-2') }}">{{ $postTitle($posts[5] ?? null, 'Benefit of Hot Ston Spa for your health') }}</a></h4>
                        <p class="desc">{{ $postDesc($posts[5] ?? null, 'Lorem ipsum dolor sit amet, conseur adipiscing elit ut aliqua, purus sit amet luctus venenatis.') }}</p>
                        <ul class="meta">
                            <li class="author-info"><span>By:</span> <a href="{{ route('blog.index') }}">{{ $postAuthor($posts[5] ?? null) }}</a></li>
                            <li class="post-date">{{ $postDate($posts[5] ?? null) }}</li>
                        </ul>
                    </div>
                </div>
                <!--== End Blog Item ==-->
            </div>
        </div>
    </div>
</section>
<!--== End Blog Area Wrapper ==-->

<!--== Start Blog Area Wrapper ==-->
@php
    $othersImages = ['1.webp', '2.webp', '3.webp', '5.webp', '6.webp', '7.webp'];
@endphp
<section class="section-space pt-0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h2 class="title">Others Posts</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis</p>
                </div>
            </div>
        </div>
        <div class="row mb-n9">
            @foreach($othersImages as $i => $img)
                @php
                    $p = $posts[6 + $i] ?? null;
                    $categoryClass = $i % 3 === 1 ? 'post-category post-category-two' : ($i % 3 === 2 ? 'post-category post-category-three' : 'post-category');
                    $bg = $i % 3 === 1 ? '#A49CFF' : ($i % 3 === 2 ? '#9CDBFF' : null);
                @endphp
                <div class="col-sm-6 col-lg-4 mb-8">
                    <!--== Start Blog Item ==-->
                    <div class="post-item">
                        <a href="{{ $postLink($p, 'demo-1') }}" class="thumb">
                            <img src="{{ $cdn }}/blog/{{ $img }}" width="370" height="320" alt="Image-HasTech">
                        </a>
                        <div class="content">
                            <a class="{{ $categoryClass }}" @if($bg) data-bg-color="{{ $bg }}" @endif href="{{ route('blog.index') }}">beauty</a>
                            <h4 class="title"><a href="{{ $postLink($p, 'demo-1') }}">{{ $postTitle($p, 'Lorem ipsum dolor sit amet consectetur adipiscing.') }}</a></h4>
                            <ul class="meta">
                                <li class="author-info"><span>By:</span> <a href="{{ route('blog.index') }}">{{ $postAuthor($p) }}</a></li>
                                <li class="post-date">{{ $postDate($p) }}</li>
                            </ul>
                        </div>
                    </div>
                    <!--== End Blog Item ==-->
                </div>
            @endforeach
        </div>
    </div>
</section>
<!--== End Blog Area Wrapper ==-->

<!--== Start Product Banner Area Wrapper ==-->
<section class="section-space pt-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-lg-8">
                <a href="{{ route('products.index') }}" class="product-banner-item">
                    <img src="{{ $cdn }}/shop/banner/8.webp" width="770" height="250" alt="Image-HasTech">
                </a>
            </div>
        </div>
    </div>
</section>
<!--== End Product Banner Area Wrapper ==-->

<!--== Start Blog Area Wrapper ==-->
@php
    $moreImages = ['1.webp', '2.webp', '3.webp'];
@endphp
<section class="section-space pt-0">
    <div class="container">
        <div class="row mb-n9">
            @foreach($moreImages as $i => $img)
                @php
                    $p = $posts[12 + $i] ?? null;
                    $categoryClass = $i === 1 ? 'post-category post-category-two' : ($i === 2 ? 'post-category post-category-three' : 'post-category');
                    $bg = $i === 1 ? '#A49CFF' : ($i === 2 ? '#9CDBFF' : null);
                @endphp
                <div class="col-sm-6 col-lg-4 mb-8">
                    <div class="post-item">
                        <a href="{{ $postLink($p, 'demo-1') }}" class="thumb">
                            <img src="{{ $cdn }}/blog/{{ $img }}" width="370" height="320" alt="Image-HasTech">
                        </a>
                        <div class="content">
                            <a class="{{ $categoryClass }}" @if($bg) data-bg-color="{{ $bg }}" @endif href="{{ route('blog.index') }}">beauty</a>
                            <h4 class="title"><a href="{{ $postLink($p, 'demo-1') }}">{{ $postTitle($p, 'Lorem ipsum dolor sit amet consectetur adipiscing.') }}</a></h4>
                            <ul class="meta">
                                <li class="author-info"><span>By:</span> <a href="{{ route('blog.index') }}">{{ $postAuthor($p) }}</a></li>
                                <li class="post-date">{{ $postDate($p) }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!--== End Blog Area Wrapper ==-->

<!--== Start News Letter Area Wrapper ==-->
<section class="section-space pt-0">
    <div class="container">
        <div class="newsletter-content-wrap" data-bg-img="{{ $cdn }}/photos/bg1.webp">
            <div class="newsletter-content">
                <div class="section-title mb-0">
                    <h2 class="title">Join with us</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam.</p>
                </div>
            </div>
            <div class="newsletter-form">
                <form action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <input type="email" name="email" class="form-control" placeholder="enter your email" required>
                    <button class="btn-submit" type="submit"><i class="fa fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
</section>
<!--== End News Letter Area Wrapper ==-->
@endsection
