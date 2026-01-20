@extends('frontend.layouts.app')

@section('title', 'Shop - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area Wrapper ==-->
<section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="page-header-st3-content text-center text-md-start">
                    <ol class="breadcrumb justify-content-center justify-content-md-start">
                        <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Products</li>
                    </ol>
                    <h2 class="page-header-title">All Products</h2>
                </div>
            </div>
            <div class="col-md-7">
                <h5 class="showing-pagination-results mt-5 mt-md-9 text-center text-md-end">
                    Showing {{ $products->total() }} Results
                </h5>
            </div>
        </div>
    </div>
</section>
<!--== End Page Header Area Wrapper ==-->

<!--== Start Product Area Wrapper ==-->
<section class="section-space">
    <div class="container">
        <div class="row justify-content-between flex-xl-row-reverse">
            <div class="col-xl-9">
                <div class="row g-3 g-sm-6">
                    @forelse($products as $product)
                        <div class="col-6 col-lg-4 col-xl-4 mb-4 mb-sm-8">
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

                    @if($products->hasPages())
                        <div class="col-12">
                            <ul class="pagination justify-content-center me-auto ms-auto mt-5 mb-10">
                                @if($products->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link previous" aria-label="Previous">
                                            <span class="fa fa-chevron-left" aria-hidden="true"></span>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link previous" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                                            <span class="fa fa-chevron-left" aria-hidden="true"></span>
                                        </a>
                                    </li>
                                @endif

                                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                    @if($page == $products->currentPage())
                                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach

                                @if($products->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link next" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                                            <span class="fa fa-chevron-right" aria-hidden="true"></span>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link next" aria-label="Next">
                                            <span class="fa fa-chevron-right" aria-hidden="true"></span>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xl-3">
                <div class="product-sidebar-widget">
                    <div class="product-widget-search">
                        <form action="{{ route('shop.index') }}" method="GET">
                            <input type="search" name="search" placeholder="Search Here" value="{{ request('search') }}">
                            <button type="submit"><i class="fa fa-search"></i></button>
                            @if(request('collection'))
                                <input type="hidden" name="collection" value="{{ request('collection') }}">
                            @endif
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                        </form>
                    </div>

                    @if($collections->isNotEmpty())
                        <div class="product-widget">
                            <h4 class="product-widget-title">Categories</h4>
                            <ul class="product-widget-category">
                                <li>
                                    <a href="{{ route('shop.index') }}" class="{{ !request('collection') ? 'active' : '' }}">
                                        All Products <span>({{ $totalProducts }})</span>
                                    </a>
                                </li>
                                @foreach($collections as $collection)
                                    <li>
                                        <a href="{{ route('shop.index', ['collection' => $collection->slug]) }}"
                                           class="{{ request('collection') == $collection->slug ? 'active' : '' }}">
                                            {{ $collection->translateAttribute('name') }}
                                            <span>({{ $collection->products_count ?? 0 }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="product-widget mb-0">
                        <h4 class="product-widget-title">Sort By</h4>
                        <form action="{{ route('shop.index') }}" method="GET" id="sort-form">
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Newest First</option>
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
        </div>
    </div>
</section>
<!--== End Product Area Wrapper ==-->
@endsection

{{-- Add to cart handler is now in the global layout (app.blade.php) --}}
