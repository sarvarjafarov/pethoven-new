@extends('frontend.layouts.app')

@section('title', 'Order Confirmation - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">Order Confirmation</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>Order Confirmation</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area ==-->

<!--== Start Order Confirmation Area ==-->
<section class="section-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Message -->
                <div class="alert alert-success text-center py-5 mb-8">
                    <div class="mb-4">
                        <i class="fa fa-check-circle fa-4x text-success"></i>
                    </div>
                    <h3 class="mb-3">Thank you for your order!</h3>
                    <p class="mb-0">Your order has been placed successfully. We've sent a confirmation email to <strong>{{ $order->shippingAddress->contact_email ?? 'your email' }}</strong></p>
                </div>

                <!-- Order Details -->
                <div class="order-details-wrapper mb-6">
                    <h4 class="mb-4">Order Details</h4>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box p-4 bg-light rounded h-100">
                                <h6 class="mb-3">Order Information</h6>
                                <p class="mb-2"><strong>Order Number:</strong> #{{ $order->reference }}</p>
                                <p class="mb-2"><strong>Order Date:</strong> {{ $order->placed_at->format('M d, Y') }}</p>
                                <p class="mb-0"><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($order->status) }}</span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box p-4 bg-light rounded h-100">
                                <h6 class="mb-3">Payment Information</h6>
                                @if($order->transactions->first())
                                    @php
                                        $transaction = $order->transactions->first();
                                    @endphp
                                    <p class="mb-2"><strong>Payment Method:</strong> Credit Card</p>
                                    @if($transaction->card_type)
                                        <p class="mb-2"><strong>Card:</strong> {{ ucfirst($transaction->card_type) }} ending in {{ $transaction->last_four }}</p>
                                    @endif
                                    <p class="mb-0"><strong>Amount Paid:</strong> {{ $order->total->formatted }}</p>
                                @else
                                    <p class="mb-0">Payment information not available</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box p-4 bg-light rounded h-100">
                                <h6 class="mb-3">Shipping Address</h6>
                                @if($order->shippingAddress)
                                    <p class="mb-1">{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                                    <p class="mb-1">{{ $order->shippingAddress->line_one }}</p>
                                    @if($order->shippingAddress->line_two)
                                        <p class="mb-1">{{ $order->shippingAddress->line_two }}</p>
                                    @endif
                                    <p class="mb-1">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postcode }}</p>
                                    <p class="mb-0">{{ $order->shippingAddress->country->name ?? '' }}</p>
                                    @if($order->shippingAddress->contact_phone)
                                        <p class="mb-0 mt-2"><strong>Phone:</strong> {{ $order->shippingAddress->contact_phone }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box p-4 bg-light rounded h-100">
                                <h6 class="mb-3">Billing Address</h6>
                                @if($order->billingAddress)
                                    <p class="mb-1">{{ $order->billingAddress->first_name }} {{ $order->billingAddress->last_name }}</p>
                                    <p class="mb-1">{{ $order->billingAddress->line_one }}</p>
                                    @if($order->billingAddress->line_two)
                                        <p class="mb-1">{{ $order->billingAddress->line_two }}</p>
                                    @endif
                                    <p class="mb-1">{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->postcode }}</p>
                                    <p class="mb-0">{{ $order->billingAddress->country->name ?? '' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="order-items-wrapper">
                    <h4 class="mb-4">Order Items</h4>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->lines as $line)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $purchasable = $line->purchasable;
                                                    $product = $purchasable->product ?? null;
                                                    $image = $product?->thumbnail?->getUrl('small') ?? asset('brancy/images/shop/1.webp');
                                                @endphp
                                                <img src="{{ $image }}" alt="{{ $line->description }}" style="width: 50px; height: 50px; object-fit: cover;" class="me-3 rounded">
                                                <div>
                                                    <h6 class="mb-0">{{ $line->description }}</h6>
                                                    @if($purchasable->sku)
                                                        <small class="text-muted">SKU: {{ $purchasable->sku }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $line->quantity }}</td>
                                        <td class="text-end">{{ $line->unit_price->formatted }}</td>
                                        <td class="text-end">{{ $line->sub_total->formatted }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">{{ $order->sub_total->formatted }}</td>
                                </tr>
                                @if($order->tax_total->value > 0)
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                        <td class="text-end">{{ $order->tax_total->formatted }}</td>
                                    </tr>
                                @endif
                                <tr class="table-active">
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong class="text-primary fs-5">{{ $order->total->formatted }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Actions -->
                <div class="text-center mt-8">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fa fa-home me-2"></i>Continue Shopping
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-primary ms-3">
                        <i class="fa fa-print me-2"></i>Print Order
                    </button>
                </div>

                <!-- Additional Info -->
                <div class="alert alert-info mt-6">
                    <h6 class="mb-2">What's Next?</h6>
                    <ul class="mb-0">
                        <li>You will receive an order confirmation email shortly</li>
                        <li>We'll send you a shipping confirmation once your order ships</li>
                        <li>Track your order status in your account dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Order Confirmation Area ==-->
@endsection
