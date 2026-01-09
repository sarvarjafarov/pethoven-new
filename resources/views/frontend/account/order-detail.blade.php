@extends('frontend.layouts.app')

@section('title', 'Order Details - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">Order Details</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li><a href="{{ route('account.dashboard') }}">My Account</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li><a href="{{ route('account.orders') }}">Orders</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>#{{ $order->reference }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area ==-->

<!--== Start Order Detail Area ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                @include('frontend.account.partials.sidebar')
            </div>

            <div class="col-lg-9">
                <div class="order-detail-content">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div>
                            <h4 class="mb-2">Order #{{ $order->reference }}</h4>
                            <p class="text-muted mb-0">Placed on {{ $order->placed_at?->format('F d, Y \a\t g:i A') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="badge bg-{{ $order->status === 'payment-received' ? 'success' : ($order->status === 'awaiting-payment' ? 'warning' : 'info') }} fs-6 px-4 py-2">
                                {{ ucwords(str_replace('-', ' ', $order->status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card mb-6">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Order Items</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->lines as $line)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($line->purchasable && method_exists($line->purchasable, 'getThumbnail') && $line->purchasable->getThumbnail())
                                                            <img
                                                                src="{{ $line->purchasable->getThumbnail()->getUrl('small') }}"
                                                                alt="{{ $line->description }}"
                                                                class="me-3"
                                                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"
                                                            >
                                                        @endif
                                                        <div>
                                                            <strong>{{ $line->description }}</strong>
                                                            @if($line->identifier)
                                                                <br><small class="text-muted">SKU: {{ $line->identifier }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $line->quantity }}</td>
                                                <td>{{ $line->unit_price->formatted }}</td>
                                                <td><strong>{{ $line->total->formatted }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="border-top">
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                            <td><strong>{{ $order->sub_total->formatted }}</strong></td>
                                        </tr>
                                        @if($order->shipping_total->value > 0)
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                                <td><strong>{{ $order->shipping_total->formatted }}</strong></td>
                                            </tr>
                                        @endif
                                        @if($order->tax_total->value > 0)
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                                <td><strong>{{ $order->tax_total->formatted }}</strong></td>
                                            </tr>
                                        @endif
                                        <tr class="bg-light">
                                            <td colspan="3" class="text-end"><h5 class="mb-0">Total:</h5></td>
                                            <td><h5 class="mb-0 text-primary">{{ $order->total->formatted }}</h5></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Shipping Address -->
                        @if($order->shippingAddress)
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Shipping Address</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-1"><strong>{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</strong></p>
                                        @if($order->shippingAddress->line_one)
                                            <p class="mb-1">{{ $order->shippingAddress->line_one }}</p>
                                        @endif
                                        @if($order->shippingAddress->line_two)
                                            <p class="mb-1">{{ $order->shippingAddress->line_two }}</p>
                                        @endif
                                        @if($order->shippingAddress->city)
                                            <p class="mb-1">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postcode }}</p>
                                        @endif
                                        @if($order->shippingAddress->country)
                                            <p class="mb-1">{{ $order->shippingAddress->country->name ?? $order->shippingAddress->country }}</p>
                                        @endif
                                        @if($order->shippingAddress->contact_email)
                                            <p class="mb-0"><i class="fa fa-envelope me-2"></i>{{ $order->shippingAddress->contact_email }}</p>
                                        @endif
                                        @if($order->shippingAddress->contact_phone)
                                            <p class="mb-0"><i class="fa fa-phone me-2"></i>{{ $order->shippingAddress->contact_phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Billing Address -->
                        @if($order->billingAddress)
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Billing Address</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-1"><strong>{{ $order->billingAddress->first_name }} {{ $order->billingAddress->last_name }}</strong></p>
                                        @if($order->billingAddress->line_one)
                                            <p class="mb-1">{{ $order->billingAddress->line_one }}</p>
                                        @endif
                                        @if($order->billingAddress->line_two)
                                            <p class="mb-1">{{ $order->billingAddress->line_two }}</p>
                                        @endif
                                        @if($order->billingAddress->city)
                                            <p class="mb-1">{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->postcode }}</p>
                                        @endif
                                        @if($order->billingAddress->country)
                                            <p class="mb-1">{{ $order->billingAddress->country->name ?? $order->billingAddress->country }}</p>
                                        @endif
                                        @if($order->billingAddress->contact_email)
                                            <p class="mb-0"><i class="fa fa-envelope me-2"></i>{{ $order->billingAddress->contact_email }}</p>
                                        @endif
                                        @if($order->billingAddress->contact_phone)
                                            <p class="mb-0"><i class="fa fa-phone me-2"></i>{{ $order->billingAddress->contact_phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Payment Information -->
                    @if($order->transactions->count() > 0)
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Payment Information</h5>
                            </div>
                            <div class="card-body">
                                @foreach($order->transactions as $transaction)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <strong>{{ ucfirst($transaction->type) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $transaction->created_at->format('M d, Y \a\t g:i A') }}</small>
                                        </div>
                                        <div>
                                            <span class="badge bg-{{ $transaction->status === 'success' ? 'success' : ($transaction->status === 'failed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('account.orders') }}" class="btn btn-outline-primary">
                            <i class="fa fa-arrow-left me-2"></i>Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Order Detail Area ==-->
@endsection
