@extends('frontend.layouts.app')

@section('title', 'About Us - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area Wrapper ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">About Us</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>About Us</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area Wrapper ==-->

<!--== Start About Area Wrapper ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="about-content">
                    <div class="section-title text-start">
                        <h2 class="title">Welcome to {{ config('app.name') }}</h2>
                        <p>Your trusted beauty and cosmetic destination</p>
                    </div>
                    <p>We are dedicated to providing you with the highest quality beauty and cosmetic products. Our mission is to help you feel confident and beautiful every day.</p>

                    <h4 class="mt-7 mb-4">Our Values</h4>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="fa fa-check-circle text-primary me-2"></i> Quality Products</li>
                        <li class="mb-3"><i class="fa fa-check-circle text-primary me-2"></i> Customer Satisfaction</li>
                        <li class="mb-3"><i class="fa fa-check-circle text-primary me-2"></i> Expert Knowledge</li>
                        <li class="mb-3"><i class="fa fa-check-circle text-primary me-2"></i> Sustainable Practices</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-thumb">
                    <img src="{{ asset('brancy/images/photos/about1.webp') }}" alt="About Us" class="img-fluid">
                </div>
            </div>
        </div>

        <div class="row mt-10">
            <div class="col-lg-4">
                <div class="feature-icon-box text-center p-6">
                    <div class="icon mb-4">
                        <img src="{{ asset('brancy/images/icons/feature1.webp') }}" alt="Quality">
                    </div>
                    <h4>Premium Quality</h4>
                    <p>We source only the finest ingredients for our products</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="feature-icon-box text-center p-6">
                    <div class="icon mb-4">
                        <img src="{{ asset('brancy/images/icons/feature2.webp') }}" alt="Expert">
                    </div>
                    <h4>Expert Care</h4>
                    <p>Our team provides personalized beauty advice</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="feature-icon-box text-center p-6">
                    <div class="icon mb-4">
                        <img src="{{ asset('brancy/images/icons/feature3.webp') }}" alt="Satisfaction">
                    </div>
                    <h4>100% Satisfaction</h4>
                    <p>Your satisfaction is our top priority</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End About Area Wrapper ==-->
@endsection
