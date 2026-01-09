@extends('frontend.layouts.app')

@section('title', 'Contact Us - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area Wrapper ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">Contact Us</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>Contact</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area Wrapper ==-->

<!--== Start Contact Area Wrapper ==-->
<section class="section-space">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="contact-form">
                    <h3 class="title mb-6">Get In Touch</h3>
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="name" class="form-label">Your Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="email" class="form-label">Your Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-4">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-4">
                                    <label for="message" class="form-label">Your Message *</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-info mt-lg-0 mt-8">
                    <h3 class="title mb-6">Contact Information</h3>
                    <div class="contact-info-item mb-6">
                        <h5 class="mb-3"><i class="fa fa-map-marker me-2"></i> Address</h5>
                        <p>123 Beauty Street<br>New York, NY 10001</p>
                    </div>
                    <div class="contact-info-item mb-6">
                        <h5 class="mb-3"><i class="fa fa-phone me-2"></i> Phone</h5>
                        <p>+1 (555) 123-4567</p>
                    </div>
                    <div class="contact-info-item mb-6">
                        <h5 class="mb-3"><i class="fa fa-envelope me-2"></i> Email</h5>
                        <p>info@{{ strtolower(str_replace(' ', '', config('app.name'))) }}.com</p>
                    </div>
                    <div class="contact-info-item">
                        <h5 class="mb-3"><i class="fa fa-clock-o me-2"></i> Hours</h5>
                        <p>Mon - Fri: 9:00 AM - 6:00 PM<br>Sat - Sun: 10:00 AM - 4:00 PM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Contact Area Wrapper ==-->
@endsection
