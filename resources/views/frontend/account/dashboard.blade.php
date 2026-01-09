@extends('frontend.layouts.app')

@section('title', 'My Account - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">My Account</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>My Account</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area ==-->

<!--== Start Account Area ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                @include('frontend.account.partials.sidebar')
            </div>

            <div class="col-lg-9">
                <div class="account-content">
                    <h4 class="mb-5">Welcome, {{ $user->name }}!</h4>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row mb-8">
                        <div class="col-md-4">
                            <div class="card text-center p-4">
                                <div class="mb-3">
                                    <i class="fa fa-shopping-bag fa-3x text-primary"></i>
                                </div>
                                <h5>Total Orders</h5>
                                <h2 class="mb-0">{{ $recentOrders->count() }}</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center p-4">
                                <div class="mb-3">
                                    <i class="fa fa-user fa-3x text-success"></i>
                                </div>
                                <h5>Account</h5>
                                <p class="mb-0">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center p-4">
                                <div class="mb-3">
                                    <i class="fa fa-calendar fa-3x text-info"></i>
                                </div>
                                <h5>Member Since</h5>
                                <p class="mb-0">{{ $user->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($recentOrders->count() > 0)
                        <h5 class="mb-4">Recent Orders</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>#{{ $order->reference }}</td>
                                            <td>{{ $order->placed_at?->format('M d, Y') ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'payment-received' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->total->formatted }}</td>
                                            <td>
                                                <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('account.orders') }}" class="btn btn-primary">View All Orders</a>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <i class="fa fa-shopping-bag fa-4x mb-4 text-muted"></i>
                            <h5>No Orders Yet</h5>
                            <p class="text-muted">You haven't placed any orders yet.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary mt-4">
                                <i class="fa fa-shopping-bag me-2"></i>Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Account Area ==-->
@endsection
