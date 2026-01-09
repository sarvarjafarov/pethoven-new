@extends('frontend.layouts.app')

@section('title', 'Home - ' . config('app.name'))

@section('content')
<!--== Start Hero Area Wrapper ==-->
<section class="hero-slider-area position-relative" style="padding: 100px 0; background: linear-gradient(135deg, #E8F5E9 0%, #E3F2FD 50%, #F3E5F5 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-6 mb-lg-0 mb-8">
                <div class="hero-slide-content">
                    <div style="position: relative; margin-bottom: 40px;">
                        <span style="position: absolute; top: -20px; left: 0; font-size: 80px; color: #F4A29B; font-family: 'Pacifico', cursive; font-weight: 400; line-height: 1; opacity: 0.3;">Best</span>
                        <h5 style="position: relative; color: #F4A29B; font-size: 64px; font-family: 'Pacifico', cursive; font-weight: 400; line-height: 1; margin-bottom: 0; z-index: 1;">Best</h5>
                    </div>
                    <h2 style="font-size: 80px; font-weight: 800; line-height: 0.9; margin-bottom: 30px; color: #1a1a2e; letter-spacing: -2px;">CLEAN FRESH</h2>
                    <p style="font-size: 17px; color: #666; margin-bottom: 40px; line-height: 1.8; max-width: 480px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.</p>
                    <a href="{{ route('shop.index') }}" style="display: inline-block; padding: 18px 45px; border: 2px solid #1a1a2e; border-radius: 50px; font-weight: 600; letter-spacing: 3px; color: #1a1a2e; text-decoration: none; text-transform: uppercase; transition: all 0.3s ease; font-size: 14px;" onmouseover="this.style.backgroundColor='#1a1a2e'; this.style.color='white'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#1a1a2e'">Buy Now</a>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="hero-slide-thumb text-end">
                    <img src="{{ asset('brancy/images/slider/slider1.webp') }}" alt="Hero Image" style="max-width: 100%; height: auto;">
                </div>
            </div>
        </div>
        <div style="position: absolute; left: 30px; top: 50%; transform: translateY(-50%); display: flex; flex-direction: column; gap: 15px; z-index: 10;">
            <span style="font-size: 18px; color: #1a1a2e; font-weight: 600; writing-mode: vertical-lr; transform: rotate(180deg);">01 / 02</span>
        </div>
    </div>
</section>
<!--== End Hero Area Wrapper ==-->

<!--== Start Product Category Area Wrapper ==-->
<section style="padding: 80px 0 40px;">
    <div class="container">
        <div class="row g-4">
            <div class="col-6 col-lg-2">
                <a href="{{ route('shop.index') }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; background: #E8F5E9; border-radius: 20px; text-decoration: none; transition: transform 0.3s ease; position: relative; overflow: hidden;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span style="position: absolute; top: 15px; right: 15px; background: #FF6B6B; color: white; padding: 6px 18px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase;">new</span>
                    <img src="{{ asset('brancy/images/shop/category/1.webp') }}" width="70" height="80" alt="Hair Care" style="margin-bottom: 15px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #1a1a2e; margin: 0; text-align: center;">Hare care</h3>
                </a>
            </div>
            <div class="col-6 col-lg-2">
                <a href="{{ route('shop.index') }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; background: #FFEDB4; border-radius: 20px; text-decoration: none; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                    <img src="{{ asset('brancy/images/shop/category/2.webp') }}" width="80" height="80" alt="Skin Care" style="margin-bottom: 15px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #1a1a2e; margin: 0; text-align: center;">Skin care</h3>
                </a>
            </div>
            <div class="col-6 col-lg-2">
                <a href="{{ route('shop.index') }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; background: #DFE4FF; border-radius: 20px; text-decoration: none; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                    <img src="{{ asset('brancy/images/shop/category/3.webp') }}" width="80" height="80" alt="Lipstick" style="margin-bottom: 15px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #1a1a2e; margin: 0; text-align: center;">Lip stick</h3>
                </a>
            </div>
            <div class="col-6 col-lg-2">
                <a href="{{ route('shop.index') }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; background: #FFEACC; border-radius: 20px; text-decoration: none; transition: transform 0.3s ease; position: relative; overflow: hidden;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span style="position: absolute; top: 15px; right: 15px; background: #9B59B6; color: white; padding: 6px 18px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase;">sale</span>
                    <img src="{{ asset('brancy/images/shop/category/4.webp') }}" width="80" height="80" alt="Face Skin" style="margin-bottom: 15px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #1a1a2e; margin: 0; text-align: center;">Face skin</h3>
                </a>
            </div>
            <div class="col-6 col-lg-2">
                <a href="{{ route('shop.index') }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; background: #FFDAE0; border-radius: 20px; text-decoration: none; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                    <img src="{{ asset('brancy/images/shop/category/5.webp') }}" width="80" height="80" alt="Blusher" style="margin-bottom: 15px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #1a1a2e; margin: 0; text-align: center;">Blusher</h3>
                </a>
            </div>
            <div class="col-6 col-lg-2">
                <a href="{{ route('shop.index') }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; background: #FFF3DA; border-radius: 20px; text-decoration: none; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                    <img src="{{ asset('brancy/images/shop/category/6.webp') }}" width="80" height="80" alt="Natural" style="margin-bottom: 15px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #1a1a2e; margin: 0; text-align: center;">Natural</h3>
                </a>
            </div>
        </div>
    </div>
</section>
<!--== End Product Category Area Wrapper ==-->

<!--== Start Top Sale Section ==-->
<section style="padding: 80px 0; background-color: white;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center" style="margin-bottom: 60px;">
                    <h2 style="font-size: 52px; font-weight: 800; margin-bottom: 15px; color: #1a1a2e;">Top Sale</h2>
                    <p style="color: #666; font-size: 17px; line-height: 1.8;">Lorem ipsum dolor sit amet, consectetur adipiscing elit<br>ut aliquam, purus sit amet luctus venenatis</p>
                </div>
            </div>
        </div>
        <div class="row g-4">
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
<section style="padding: 80px 0; background-color: #f8f8f8;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center" style="margin-bottom: 60px;">
                    <h2 style="font-size: 52px; font-weight: 800; margin-bottom: 15px; color: #1a1a2e;">Blog Posts</h2>
                    <p style="color: #666; font-size: 17px; line-height: 1.8;">Lorem ipsum dolor sit amet, consectetur adipiscing elit<br>ut aliquam, purus sit amet luctus venenatis</p>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="blog-card" style="background: white; border-radius: 20px; overflow: hidden; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="blog-thumb">
                        <img src="{{ asset('brancy/images/blog/1.webp') }}" alt="Blog" class="w-100" style="height: 280px; object-fit: cover;">
                    </div>
                    <div class="blog-content" style="padding: 30px;">
                        <span class="badge" style="background-color: #FFB8B8; color: white; padding: 8px 20px; border-radius: 20px; font-size: 11px; font-weight: 600; margin-bottom: 15px; display: inline-block; text-transform: uppercase;">Beauty</span>
                        <h4 style="font-size: 20px; font-weight: 700; margin-bottom: 15px; line-height: 1.4; color: #1a1a2e;">Lorem ipsum dolor sit amet consectetur adipiscing.</h4>
                        <p style="color: #999; font-size: 13px; margin: 0; text-transform: uppercase; letter-spacing: 1px;">By: Tomas De Momen &nbsp;•&nbsp; February 13, 2022</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="blog-card" style="background: white; border-radius: 20px; overflow: hidden; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="blog-thumb">
                        <img src="{{ asset('brancy/images/blog/2.webp') }}" alt="Blog" class="w-100" style="height: 280px; object-fit: cover;">
                    </div>
                    <div class="blog-content" style="padding: 30px;">
                        <span class="badge" style="background-color: #C4B5FD; color: white; padding: 8px 20px; border-radius: 20px; font-size: 11px; font-weight: 600; margin-bottom: 15px; display: inline-block; text-transform: uppercase;">Beauty</span>
                        <h4 style="font-size: 20px; font-weight: 700; margin-bottom: 15px; line-height: 1.4; color: #1a1a2e;">Facial Scrub is natural treatment for face.</h4>
                        <p style="color: #999; font-size: 13px; margin: 0; text-transform: uppercase; letter-spacing: 1px;">By: Tomas De Momen &nbsp;•&nbsp; February 13, 2022</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="blog-card" style="background: white; border-radius: 20px; overflow: hidden; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="blog-thumb">
                        <img src="{{ asset('brancy/images/blog/3.webp') }}" alt="Blog" class="w-100" style="height: 280px; object-fit: cover;">
                    </div>
                    <div class="blog-content" style="padding: 30px;">
                        <span class="badge" style="background-color: #9DD6FF; color: white; padding: 8px 20px; border-radius: 20px; font-size: 11px; font-weight: 600; margin-bottom: 15px; display: inline-block; text-transform: uppercase;">Beauty</span>
                        <h4 style="font-size: 20px; font-weight: 700; margin-bottom: 15px; line-height: 1.4; color: #1a1a2e;">Benefit of Hot Ston Spa for your health & life.</h4>
                        <p style="color: #999; font-size: 13px; margin: 0; text-transform: uppercase; letter-spacing: 1px;">By: Tomas De Momen &nbsp;•&nbsp; February 13, 2022</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Blog Section ==-->

<!--== Start Newsletter Section ==-->
<section style="padding: 80px 0;">
    <div class="container">
        <div style="background: linear-gradient(135deg, #FFDEE2 0%, #FFE5D4 25%, #FFF8E1 50%, #E8F5FF 75%, #F3E5F5 100%); border-radius: 30px; padding: 60px 80px;">
            <div class="row align-items-center">
                <div class="col-md-6 mb-md-0 mb-6">
                    <h2 style="font-size: 52px; font-weight: 800; margin-bottom: 20px; color: #1a1a2e; line-height: 1.2;">Join with us</h2>
                    <p style="color: #666; font-size: 18px; line-height: 1.6; margin-bottom: 0;">Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit ut aliquam.</p>
                </div>
                <div class="col-md-6">
                    <form class="newsletter-form" action="#" method="POST">
                        @csrf
                        <div style="position: relative; display: flex; align-items: center; background: white; border-radius: 60px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); overflow: hidden;">
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="enter your email"
                                required
                                style="border: none; padding: 24px 160px 24px 35px; font-size: 16px; color: #666; background: transparent; box-shadow: none; outline: none;"
                            >
                            <button
                                class="btn"
                                type="submit"
                                style="position: absolute; right: 8px; background-color: #E87B63; color: white; border: none; padding: 18px 40px; border-radius: 50px; font-size: 16px; transition: all 0.3s ease; cursor: pointer;"
                                onmouseover="this.style.backgroundColor='#d86a54'"
                                onmouseout="this.style.backgroundColor='#E87B63'"
                            >
                                <i class="fa fa-paper-plane" style="font-size: 18px;"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Newsletter Section ==-->
@endsection
