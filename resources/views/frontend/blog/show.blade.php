@extends('frontend.layouts.app')

@section('title', ($post->title ?? 'Blog Post') . ' - ' . config('app.name'))

@section('content')
@php
    $cdn = 'https://template.hasthemes.com/brancy/brancy/assets/images';
    
    // Handle featured image (CDN or local)
    $postFeatured = $post->featured_image ?? null;
    $postFeaturedSrc = $postFeatured
        ? (\Illuminate\Support\Str::startsWith($postFeatured, ['http://', 'https://']) ? $postFeatured : asset($postFeatured))
        : "{$cdn}/blog/blog-detail1.webp";
    
    // Get previous/next posts for navigation
    $allPosts = \App\Models\BlogPost::published()->latest('published_at')->get();
    $currentIndex = $allPosts->search(function($p) use ($post) {
        return ($p->id ?? $p->slug ?? null) === ($post->id ?? $post->slug ?? null);
    });
    $prevPost = $currentIndex !== false && $currentIndex > 0 ? $allPosts[$currentIndex - 1] : null;
    $nextPost = $currentIndex !== false && $currentIndex < $allPosts->count() - 1 ? $allPosts[$currentIndex + 1] : null;
    
    // For demo posts, use demo navigation
    if (isset($post->slug) && \Illuminate\Support\Str::startsWith($post->slug, 'demo-')) {
        $prevPost = (object)['slug' => 'demo-1', 'title' => 'Lorem ipsum dolor amet, consectetur adipiscing.', 'formatted_date' => 'February 13, 2022'];
        $nextPost = (object)['slug' => 'demo-2', 'title' => 'Lorem ipsum dolor amet, consectetur adipiscing.', 'formatted_date' => 'February 13, 2022'];
    }
    
    // Parse content into paragraphs (for demo content structure)
    $content = $post->content ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vitae mauris, feugiat malesuada adipiscing est. Turpis at cras scelerisque cursus et enim. Tellus integer purus scelerisque convallis gravida volutpat elit. In purus amet, suspendisse et lorem. At in id et facilisi molestie interdum blandit elementum. Arcu lectus in ultrices mauris amet, volutpat arcu. Habitant ac vitae, quam egestas in sed. Dignissim odio nunc fermentum donec risus. Volutpat elementum aliquet nec ligula. Rhoncus sem condimentum egestas scelerisque. Ac commodo neque auctor porttitor enim, tristique mollis laoreet. Interdum tellus tortor senectus erat enim in. Penatibus odio sed in dui a id urna. Tellus odio adipiscing erat viverra tempor.';
    $paragraphs = explode("\n\n", $content);
    if (count($paragraphs) < 3) {
        $paragraphs = [
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vitae mauris, feugiat malesuada adipiscing est. Turpis at cras scelerisque cursus et enim. Tellus integer purus scelerisque convallis gravida volutpat elit. In purus amet, suspendisse et lorem. At in id et facilisi molestie interdum blandit elementum. Arcu lectus in ultrices mauris amet, volutpat arcu. Habitant ac vitae, quam egestas in sed. Dignissim odio nunc fermentum donec risus. Volutpat elementum aliquet nec ligula. Rhoncus sem condimentum egestas scelerisque. Ac commodo neque auctor porttitor enim, tristique mollis laoreet. Interdum tellus tortor senectus erat enim in. Penatibus odio sed in dui a id urna. Tellus odio adipiscing erat viverra tempor.',
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Gravida quis turpis feugiat sapien venenatis. Iaculis nunc nisl risus mattis elit id lobortis. Proin erat fermentum tempor elementum bibendum. Proin sed in nunc purus. Non duis eu pretium dictumst sed habitant sit vitae eget. Nisi sit lacus, fusce diam. Massa odio sit velit sed purus quis dolor.',
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Gravida quis turpis feugiat sapien venenatis. Iaculis nunc nisl risus mattis elit id lobortis. Proin erat fermentum tempor elementum bibendum. Proin sed in nunc purus. Non duis eu pretium dictumst sed habitant sit vitae eget. Nisi sit lacus, fusce diam. Massa odio sit velit sed purus quis dolor.',
        ];
    }
@endphp

<!--== Start Page Header Area Wrapper ==-->
<nav aria-label="breadcrumb" class="breadcrumb-style1">
    <div class="container">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Blog Detail</li>
        </ol>
    </div>
</nav>
<!--== End Page Header Area Wrapper ==-->

<!--== Start Blog Detail Area Wrapper ==-->
<section class="section-space pb-0">
    <div class="container">
        <div class="blog-detail">
            <h3 class="blog-detail-title">{{ $post->title ?? 'Lorem ipsum dolor sit amet, ctetur adipiscing elit' }}</h3>
            <div class="blog-detail-category">
                @if($post->category ?? null)
                    <a class="category" href="{{ route('blog.index', ['category' => $post->category]) }}">{{ $post->category }}</a>
                @else
                    <a class="category" href="{{ route('blog.index') }}">beauty</a>
                @endif
                <a class="category" data-bg-color="#957AFF" href="{{ route('blog.index') }}">Fashion</a>
            </div>
            <img class="blog-detail-img mb-7 mb-lg-10" src="{{ $postFeaturedSrc }}" width="1170" height="1012" alt="Image">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row">
                        <div class="col-md-7">
                            <ul class="blog-detail-meta">
                                <li class="meta-admin"><img src="{{ $cdn }}/blog/admin.webp" alt="Image"> {{ $post->author ?? 'Tomas Alva Addison' }}</li>
                                <li>{{ $post->formatted_date ?? ($post->published_at?->format('F d, Y') ?? 'February 13, 2022') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-5">
                            <div class="blog-detail-social">
                                <span>Share:</span>
                                <a href="https://www.pinterest.com/" target="_blank" rel="noopener"><i class="fa fa-pinterest-p"></i></a>
                                <a href="https://twitter.com/" target="_blank" rel="noopener"><i class="fa fa-twitter"></i></a>
                                <a href="https://www.facebook.com/" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a>
                            </div>
                        </div>
                    </div>
                    <p class="desc mt-4 mt-lg-7">{{ $paragraphs[0] ?? '' }}</p>
                    <p class="desc mb-6 mb-lg-10">{{ $paragraphs[1] ?? '' }}</p>
                </div>
            </div>
            <img class="blog-detail-img" src="{{ $cdn }}/blog/blog-detail2.webp" width="1170" height="700" alt="Image">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <p class="desc mt-6 mt-lg-10">{{ $paragraphs[2] ?? '' }}</p>
                    <ul class="blog-detail-list">
                        <li>• Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                        <li>• Massa odio sit velit sed purus quis dolor.</li>
                        <li>• Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                        <li>• Proin sed in nunc purus. Non duis eu pretium dictumst</li>
                    </ul>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <blockquote class="blog-detail-blockquote mt-6 mt-lg-10 mb-6 mb-lg-10">
                        <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris semper purus, at venenatis scelerisque nibh. Nisl sit convallis accumsan integer lorem. Nibh nunc in nulla quis pulvinar dictum. Eget nisi, praesent proin viverra.</p>
                        <span class="user-name">BY {{ $post->author ?? 'Momen de tomas' }}</span>
                        <img class="quote-icon" src="{{ $cdn }}/icons/quote1.webp" width="84" height="60" alt="Icon">
                    </blockquote>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <p class="desc mb-6 mb-lg-10">{{ $paragraphs[3] ?? ($paragraphs[2] ?? '') }}</p>
                    <img class="blog-detail-img" src="{{ $cdn }}/blog/blog-detail3.webp" width="1070" height="340" alt="Image">
                </div>
            </div>
        </div>

        <div class="section-space pb-0">
            <!--== Start Product Category Item ==-->
            <a href="{{ route('products.index') }}" class="product-banner-item">
                <img src="{{ $cdn }}/shop/banner/9.webp" width="1170" height="200" alt="Image-HasTech">
            </a>
            <!--== End Product Category Item ==-->
        </div>

        <div class="row justify-content-between align-items-center pt-10 mt-4 section-space">
            <div class="col-sm-6">
                @if($prevPost)
                    <a href="{{ route('blog.show', $prevPost->slug ?? 'demo-1') }}" class="blog-next-previous">
                        <div class="thumb">
                            <span class="arrow">PREV</span>
                            <img src="{{ $cdn }}/blog/next-previous1.webp" width="93" height="80" alt="Image">
                        </div>
                        <div class="content">
                            <h4 class="title">{{ $prevPost->title ?? 'Lorem ipsum dolor amet, consectetur adipiscing.' }}</h4>
                            <h5 class="post-date">{{ $prevPost->formatted_date ?? ($prevPost->published_at?->format('F d, Y') ?? 'February 13, 2022') }}</h5>
                        </div>
                    </a>
                @endif
            </div>
            <div class="col-sm-6">
                @if($nextPost)
                    <a href="{{ route('blog.show', $nextPost->slug ?? 'demo-2') }}" class="blog-next-previous blog-next">
                        <div class="thumb">
                            <span class="arrow">NEXT</span>
                            <img src="{{ $cdn }}/blog/next-previous2.webp" width="93" height="80" alt="Image">
                        </div>
                        <div class="content">
                            <h4 class="title">{{ $nextPost->title ?? 'Lorem ipsum dolor amet, consectetur adipiscing.' }}</h4>
                            <h5 class="post-date">{{ $nextPost->formatted_date ?? ($nextPost->published_at?->format('F d, Y') ?? 'February 13, 2022') }}</h5>
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
                            <a href="https://www.pinterest.com/" target="_blank" rel="noopener"><i class="fa fa-pinterest-p"></i></a>
                            <a href="https://twitter.com/" target="_blank" rel="noopener"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.facebook.com/" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a>
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
            @forelse($relatedPosts->take(3) as $index => $relatedPost)
                <div class="col-sm-6 col-lg-4 mb-8">
                    <!--== Start Blog Item ==-->
                    <div class="post-item">
                        @php
                            $relatedFeatured = $relatedPost->featured_image ?? null;
                            $relatedFeaturedSrc = $relatedFeatured
                                ? (\Illuminate\Support\Str::startsWith($relatedFeatured, ['http://', 'https://']) ? $relatedFeatured : asset($relatedFeatured))
                                : "{$cdn}/blog/" . (($index % 6) + 1) . ".webp";
                        @endphp
                        <a href="{{ route('blog.show', $relatedPost->slug ?? "demo-{$index}") }}" class="thumb">
                            <img src="{{ $relatedFeaturedSrc }}" width="370" height="320" alt="Image-HasTech">
                        </a>
                        <div class="content">
                            @php
                                $categoryColors = ['', 'post-category-two', 'post-category-three'];
                                $categoryColor = $categoryColors[$index % 3] ?? '';
                                $categoryBgColor = $index % 3 == 1 ? '#A49CFF' : ($index % 3 == 2 ? '#9CDBFF' : '');
                            @endphp
                            <a class="post-category {{ $categoryColor }}" @if($categoryBgColor) data-bg-color="{{ $categoryBgColor }}" @endif href="{{ route('blog.index') }}">{{ $relatedPost->category ?? 'beauty' }}</a>
                            <h4 class="title"><a href="{{ route('blog.show', $relatedPost->slug ?? "demo-{$index}") }}">{{ $relatedPost->title ?? 'Lorem ipsum dolor sit amet consectetur adipiscing.' }}</a></h4>
                            <ul class="meta">
                                <li class="author-info"><span>By:</span> <a href="{{ route('blog.index') }}">{{ $relatedPost->author ?? 'Tomas De Momen' }}</a></li>
                                <li class="post-date">{{ $relatedPost->formatted_date ?? ($relatedPost->published_at?->format('F d, Y') ?? 'February 13, 2022') }}</li>
                            </ul>
                        </div>
                    </div>
                    <!--== End Blog Item ==-->
                </div>
            @empty
                <!-- Demo related posts -->
                @for($i = 1; $i <= 3; $i++)
                    <div class="col-sm-6 col-lg-4 mb-8">
                        <div class="post-item">
                            <a href="{{ route('blog.show', "demo-{$i}") }}" class="thumb">
                                <img src="{{ $cdn }}/blog/{{ $i }}.webp" width="370" height="320" alt="Image-HasTech">
                            </a>
                            <div class="content">
                                @php
                                    $categoryColors = ['', 'post-category-two', 'post-category-three'];
                                    $categoryColor = $categoryColors[($i - 1) % 3] ?? '';
                                    $categoryBgColor = ($i - 1) % 3 == 1 ? '#A49CFF' : (($i - 1) % 3 == 2 ? '#9CDBFF' : '');
                                @endphp
                                <a class="post-category {{ $categoryColor }}" @if($categoryBgColor) data-bg-color="{{ $categoryBgColor }}" @endif href="{{ route('blog.index') }}">beauty</a>
                                <h4 class="title"><a href="{{ route('blog.show', "demo-{$i}") }}">Lorem ipsum dolor sit amet consectetur adipiscing.</a></h4>
                                <ul class="meta">
                                    <li class="author-info"><span>By:</span> <a href="{{ route('blog.index') }}">Tomas De Momen</a></li>
                                    <li class="post-date">February 13, 2022</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endfor
            @endforelse
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
