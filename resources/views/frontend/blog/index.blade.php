@extends('frontend.layouts.app')

@section('title', 'Blog - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area Wrapper ==-->
<section class="page-header-area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7 col-lg-7 col-xl-5">
                <div class="page-header-content">
                    <div class="title-img"><img src="https://template.hasthemes.com/brancy/brancy/assets/images/photos/page-header-text1.webp" alt="Image"></div>
                    <h2 class="page-header-title">Whats the beauty secrets?</h2>
                    <p class="page-header-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.</p>
                </div>
            </div>
            <div class="col-md-5 col-lg-5 col-xl-7">
                <div class="page-header-thumb">
                    <img src="https://template.hasthemes.com/brancy/brancy/assets/images/photos/page-header1.webp" width="570" height="669" alt="Image">
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Page Header Area Wrapper ==-->

<!--== Start Blog Area Wrapper ==-->
<section class="section-space">
    <div class="container">
        <!--== Start New Posts Section ==-->
        <div class="row mb-10">
            <div class="col-12">
                <h3 class="section-title">New Posts</h3>
                <p class="section-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis</p>
            </div>
        </div>
        
        <div class="row mb-n9">
            @forelse($newPosts as $index => $post)
                <div class="col-md-6 col-lg-6 mb-9">
                    <!--== Start Blog Item ==-->
                    <div class="blog-item">
                        <div class="blog-thumb">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                @if($post->featured_image)
                                    <img src="{{ asset($post->featured_image) }}" width="570" height="400" alt="{{ $post->title }}">
                                @else
                                    <img src="https://template.hasthemes.com/brancy/brancy/assets/images/blog/{{ ($index % 6) + 1 }}.webp" width="570" height="400" alt="{{ $post->title }}">
                                @endif
                            </a>
                            @if($post->category)
                                <a href="{{ route('blog.index', ['category' => $post->category]) }}" class="blog-category">{{ $post->category }}</a>
                            @else
                                <a href="#" class="blog-category">beauty</a>
                            @endif
                        </div>
                        <div class="blog-content">
                            <h4 class="blog-title">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h4>
                            <ul class="blog-meta">
                                <li>By: {{ $post->author ?? 'Tomas De Momen' }}</li>
                                <li>{{ $post->formatted_date ?? $post->published_at?->format('F d, Y') ?? 'February 13, 2022' }}</li>
                            </ul>
                        </div>
                    </div>
                    <!--== End Blog Item ==-->
                </div>
            @empty
                <!-- Demo content when no posts -->
                <div class="col-md-6 col-lg-6 mb-9">
                    <div class="blog-item">
                        <div class="blog-thumb">
                            <a href="#">
                                <img src="https://template.hasthemes.com/brancy/brancy/assets/images/blog/1.webp" width="570" height="400" alt="Image">
                            </a>
                            <a href="#" class="blog-category">beauty</a>
                        </div>
                        <div class="blog-content">
                            <h4 class="blog-title">
                                <a href="#">Lorem ipsum dolor sit amet consectetur adipiscing.</a>
                            </h4>
                            <ul class="blog-meta">
                                <li>By: Tomas De Momen</li>
                                <li>February 13, 2022</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 mb-9">
                    <div class="blog-item">
                        <div class="blog-thumb">
                            <a href="#">
                                <img src="https://template.hasthemes.com/brancy/brancy/assets/images/blog/2.webp" width="570" height="400" alt="Image">
                            </a>
                            <a href="#" class="blog-category">beauty</a>
                        </div>
                        <div class="blog-content">
                            <h4 class="blog-title">
                                <a href="#">Benefit of Hot Ston Spa for your health & life.</a>
                            </h4>
                            <ul class="blog-meta">
                                <li>By: Tomas De Momen</li>
                                <li>February 13, 2022</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <!--== End New Posts Section ==-->

        <!--== Start Others Posts Section ==-->
        <div class="row mb-10 mt-10">
            <div class="col-12">
                <h3 class="section-title">Others Posts</h3>
                <p class="section-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis</p>
            </div>
        </div>
        
        <div class="row mb-n9">
            @forelse($othersPosts as $index => $post)
                <div class="col-md-6 col-lg-4 mb-9">
                    <!--== Start Blog Item ==-->
                    <div class="blog-item">
                        <div class="blog-thumb">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                @if($post->featured_image)
                                    <img src="{{ asset($post->featured_image) }}" width="370" height="270" alt="{{ $post->title }}">
                                @else
                                    <img src="https://template.hasthemes.com/brancy/brancy/assets/images/blog/{{ (($index + 2) % 6) + 1 }}.webp" width="370" height="270" alt="{{ $post->title }}">
                                @endif
                            </a>
                            @if($post->category)
                                <a href="{{ route('blog.index', ['category' => $post->category]) }}" class="blog-category">{{ $post->category }}</a>
                            @else
                                <a href="#" class="blog-category">beauty</a>
                            @endif
                        </div>
                        <div class="blog-content">
                            <h4 class="blog-title">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h4>
                            <ul class="blog-meta">
                                <li>By: {{ $post->author ?? 'Tomas De Momen' }}</li>
                                <li>{{ $post->formatted_date ?? $post->published_at?->format('F d, Y') ?? 'February 13, 2022' }}</li>
                            </ul>
                        </div>
                    </div>
                    <!--== End Blog Item ==-->
                </div>
            @empty
                <!-- Demo content when no posts -->
                @for($i = 1; $i <= 6; $i++)
                    <div class="col-md-6 col-lg-4 mb-9">
                        <div class="blog-item">
                            <div class="blog-thumb">
                                <a href="#">
                                    <img src="https://template.hasthemes.com/brancy/brancy/assets/images/blog/{{ $i }}.webp" width="370" height="270" alt="Image">
                                </a>
                                <a href="#" class="blog-category">beauty</a>
                            </div>
                            <div class="blog-content">
                                <h4 class="blog-title">
                                    <a href="#">Lorem ipsum dolor sit amet consectetur adipiscing.</a>
                                </h4>
                                <ul class="blog-meta">
                                    <li>By: Tomas De Momen</li>
                                    <li>February 13, 2022</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endfor
            @endforelse
        </div>
        <!--== End Others Posts Section ==-->
    </div>
</section>
<!--== End Blog Area Wrapper ==-->

<!--== Start Newsletter Area Wrapper ==-->
<section class="newsletter-area bg-img" style="background-image: url(https://template.hasthemes.com/brancy/brancy/assets/images/photos/bg1.webp);">
    <div class="container">
        <div class="newsletter-content text-center">
            <h2 class="newsletter-title">Join with us</h2>
            <p class="newsletter-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam.</p>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                @csrf
                <div class="newsletter-form-group">
                    <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                    <button type="submit" class="btn btn-theme">Subscribe</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!--== End Newsletter Area Wrapper ==-->
@endsection
