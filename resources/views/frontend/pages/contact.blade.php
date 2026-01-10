@extends('frontend.layouts.app')

@section('title', 'Contact Us - ' . config('app.name'))

@section('content')
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
                    <p class="m-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.</p>
                    <div class="line-left-style mt-4 mb-1"></div>
                </div>

                <!--== Start Contact Form ==-->
                <div class="contact-form">
                    <form id="contact-form" action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-4">
                                    <label for="name" class="visually-hidden">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name *" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-4">
                                    <label for="email" class="visually-hidden">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email *" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-4">
                                    <label for="subject" class="visually-hidden">Subject *</label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" placeholder="Subject *" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-5">
                                    <label for="message" class="visually-hidden">Message *</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="Message *" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!--== End Contact Form ==-->
            </div>
        </div>
    </div>
    <div class="contact-left-img" data-bg-img="{{ asset('brancy/images/photos/contact.webp') }}"></div>
</section>
<!--== End Contact Area Wrapper ==-->

<!--== Start Contact Info Area Wrapper ==-->
<section class="section-space">
    <div class="container">
        <div class="row mb-n6">
            <div class="col-sm-6 col-lg-4 mb-6">
                <!--== Start Contact Info Item ==-->
                <div class="contact-info-item text-center">
                    <div class="icon">
                        <img src="{{ asset('brancy/images/icons/contact1.webp') }}" width="85" height="85" alt="Icon">
                    </div>
                    <h5 class="title">Location</h5>
                    <p>123 Beauty Street<br>New York, NY 10001</p>
                </div>
                <!--== End Contact Info Item ==-->
            </div>
            <div class="col-sm-6 col-lg-4 mb-6">
                <!--== Start Contact Info Item ==-->
                <div class="contact-info-item text-center">
                    <div class="icon">
                        <img src="{{ asset('brancy/images/icons/contact2.webp') }}" width="85" height="85" alt="Icon">
                    </div>
                    <h5 class="title">Email</h5>
                    <p><a href="mailto:info@{{ strtolower(str_replace(' ', '', config('app.name'))) }}.com">info@{{ strtolower(str_replace(' ', '', config('app.name'))) }}.com</a></p>
                </div>
                <!--== End Contact Info Item ==-->
            </div>
            <div class="col-sm-6 col-lg-4 mb-6">
                <!--== Start Contact Info Item ==-->
                <div class="contact-info-item text-center">
                    <div class="icon">
                        <img src="{{ asset('brancy/images/icons/contact3.webp') }}" width="85" height="85" alt="Icon">
                    </div>
                    <h5 class="title">Phone</h5>
                    <p><a href="tel:+15551234567">+1 (555) 123-4567</a></p>
                </div>
                <!--== End Contact Info Item ==-->
            </div>
        </div>
    </div>
</section>
<!--== End Contact Info Area Wrapper ==-->

<!--== Start Google Map Area Wrapper ==-->
<div class="google-map-area">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.9476519598093!2d-73.99185368459395!3d40.74117197932881!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e0!3m2!1sen!2sus!4v1645634738367!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</div>
<!--== End Google Map Area Wrapper ==-->
@endsection
