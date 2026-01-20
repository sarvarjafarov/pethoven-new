@extends('frontend.layouts.app')

@section('title', 'Contact Us - ' . config('app.name'))

@section('content')
@php
    $cdn = 'https://template.hasthemes.com/brancy/brancy/assets/images';
@endphp

<!--== Start Contact Area Wrapper ==-->
<section class="contact-area">
    <div class="container">
        <div class="row">
            <div class="offset-lg-6 col-lg-6">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-6" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="section-title position-relative">
                    <h2 class="title">Get in touch</h2>
                    <p class="m-0">Lorem ipsum dolor sit amet, consectetur adipiscing aliquam, purus sit amet luctus venenatis</p>
                    <div class="line-left-style mt-4 mb-1"></div>
                </div>

                <!--== Start Contact Form ==-->
                <div class="contact-form">
                    {{-- Note: ID intentionally not "contact-form" so Brancy's JS AJAX handler does not override Laravel form submission --}}
                    <form id="contact-form-laravel" action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control @error('first_name') is-invalid @enderror" type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control @error('last_name') is-invalid @enderror" type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" placeholder="Email address" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea class="form-control @error('message') is-invalid @enderror" name="message" placeholder="Message" rows="5" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <button class="btn btn-sm" type="submit">SUBMIT</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--== End Contact Form ==-->

                <!--== Message Notification ==-->
                <div class="form-message"></div>
            </div>
        </div>
    </div>
    <div class="contact-left-img" data-bg-img="{{ $cdn }}/photos/contact.webp"></div>
</section>
<!--== End Contact Area Wrapper ==-->

<!--== Start Contact Area Wrapper ==-->
<section class="section-space">
    <div class="container">
        <div class="contact-info">
            <div class="contact-info-item">
                <img class="icon" src="{{ $cdn }}/icons/1.webp" width="30" height="30" alt="Icon">
                <a href="tel://+11020303023">+11 0203 03023</a>
                <a href="tel://+11020303023">+11 0203 03023</a>
            </div>
            <div class="contact-info-item">
                <img class="icon" src="{{ $cdn }}/icons/2.webp" width="30" height="30" alt="Icon">
                <a href="mailto://example@demo.com">example@demo.com</a>
                <a href="mailto://demo@example.com">demo@example.com</a>
            </div>
            <div class="contact-info-item mb-0">
                <img class="icon" src="{{ $cdn }}/icons/3.webp" width="30" height="30" alt="Icon">
                <p>Sunset Beach, North Carolina(NC), 28468</p>
            </div>
        </div>
    </div>
</section>
<!--== End Contact Area Wrapper ==-->

<div class="map-area">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d802879.9165497769!2d144.83475730949783!3d-38.180874157285366!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad646b5d2ba4df7%3A0x4045675218ccd90!2sMelbourne%20VIC%2C%20Australia!5e0!3m2!1sen!2sbd!4v1636803638401!5m2!1sen!2sbd" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</div>
@endsection
