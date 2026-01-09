@extends('frontend.layouts.app')

@section('title', 'Shop - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">Shop</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>Shop</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area ==-->

<!--== Start Product Area ==-->
<section class="section-space">
    <div class="container">
        <div class="row mb-6">
            <div class="col-lg-8">
                <div class="product-result-info">
                    <p>Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="product-sorting">
                    <form action="{{ route('shop.index') }}" method="GET">
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="name" {{ $sort == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="price_low" {{ $sort == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ $sort == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('collection'))
                            <input type="hidden" name="collection" value="{{ request('collection') }}">
                        @endif
                    </form>
                </div>
            </div>
        </div>

        @if($collections->isNotEmpty())
            <div class="row mb-8">
                <div class="col-12">
                    <div class="product-categories">
                        <h5 class="mb-4">Filter by Category:</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('shop.index') }}"
                               class="btn {{ !request('collection') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                All Products
                            </a>
                            @foreach($collections as $collection)
                                <a href="{{ route('shop.index', ['collection' => $collection->slug]) }}"
                                   class="btn {{ request('collection') == $collection->slug ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    {{ $collection->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4 g-sm-5">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('frontend.components.product-card', ['product' => $product])
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-10">
                        <i class="fa fa-shopping-bag fa-4x mb-4 text-muted"></i>
                        <h4>No Products Found</h4>
                        <p class="text-muted">Try adjusting your filters or search terms.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary mt-4">View All Products</a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="row mt-10">
                <div class="col-12">
                    <div class="pagination-area">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
<!--== End Product Area ==-->
@endsection
