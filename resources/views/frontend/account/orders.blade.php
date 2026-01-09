@extends('frontend.layouts.app')

@section('title', 'My Orders - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">My Orders</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li><a href="{{ route('account.dashboard') }}">My Account</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>Orders</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area ==-->

<!--== Start Orders Area ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                @include('frontend.account.partials.sidebar')
            </div>

            <div class="col-lg-9">
                <div class="orders-content">
                    <h4 class="mb-5">Order History</h4>

                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>#{{ $order->reference }}</td>
                                            <td>{{ $order->placed_at?->format('M d, Y') ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'payment-received' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->lines->sum('quantity') }}</td>
                                            <td>{{ $order->total->formatted }}</td>
                                            <td>
                                                <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $orders->links() }}
                    @else
                        <div class="text-center py-10">
                            <i class="fa fa-shopping-bag fa-4x mb-4 text-muted"></i>
                            <h5>No Orders Found</h5>
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
<!--== End Orders Area ==-->
@endsection
