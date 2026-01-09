@extends('frontend.layouts.app')

@section('title', 'Profile - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">Profile Settings</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li><a href="{{ route('account.dashboard') }}">My Account</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>Profile</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area ==-->

<!--== Start Profile Area ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                @include('frontend.account.partials.sidebar')
            </div>

            <div class="col-lg-9">
                <div class="profile-content">
                    <h4 class="mb-5">Profile Information</h4>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body p-6">
                            <form method="POST" action="{{ route('account.profile.update') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="name" class="form-label">Full Name</label>
                                            <input
                                                type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                id="name"
                                                name="name"
                                                value="{{ old('name', $user->name) }}"
                                                required
                                                autofocus
                                            >
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input
                                                type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                id="email"
                                                name="email"
                                                value="{{ old('email', $user->email) }}"
                                                required
                                            >
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle me-2"></i>
                                            <strong>Account Created:</strong> {{ $user->created_at->format('F d, Y') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-3 mt-6">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save me-2"></i>Save Changes
                                    </button>
                                    <a href="{{ route('account.dashboard') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-6">
                        <div class="card-body p-6">
                            <h5 class="mb-4">Change Password</h5>
                            <p class="text-muted mb-4">To change your password, please use the password reset feature.</p>
                            <a href="{{ route('password.request') }}" class="btn btn-outline-primary">
                                <i class="fa fa-lock me-2"></i>Reset Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Profile Area ==-->
@endsection
