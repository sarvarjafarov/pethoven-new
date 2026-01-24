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
                                {{-- Previous Button --}}
                                <li class="page-item">
                                    <a class="page-link previous" href="{{ $products->onFirstPage() ? '#' : $products->previousPageUrl() }}" aria-label="Previous" @if($products->onFirstPage()) style="opacity: 0.5; pointer-events: none;" @endif>
                                        <span class="fa fa-chevron-left" aria-hidden="true"></span>
                                    </a>
                                </li>

                                @php
                                    $currentPage = $products->currentPage();
                                    $lastPage = $products->lastPage();
                                    $showEllipsis = $lastPage > 5;
                                @endphp

                                {{-- Page Numbers with leading zeros --}}
                                @if($showEllipsis)
                                    @for($i = 1; $i <= min(3, $lastPage); $i++)
                                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                            @if($currentPage == $i)
                                                <span class="page-link">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</span>
                                            @else
                                                <a class="page-link" href="{{ $products->url($i) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</a>
                                            @endif
                                        </li>
                                    @endfor
                                    @if($lastPage > 3)
                                        <li class="page-item"><a class="page-link" href="{{ $products->url($lastPage) }}">…</a></li>
                                    @endif
                                @else
                                    @foreach($products->getUrlRange(1, $lastPage) as $page => $url)
                                        <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                                            @if($currentPage == $page)
                                                <span class="page-link">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</span>
                                            @else
                                                <a class="page-link" href="{{ $url }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                                            @endif
                                        </li>
                                    @endforeach
                                @endif

                                {{-- Next Button --}}
                                <li class="page-item">
                                    <a class="page-link next" href="{{ $products->hasMorePages() ? $products->nextPageUrl() : '#' }}" aria-label="Next" @if(!$products->hasMorePages()) style="opacity: 0.5; pointer-events: none;" @endif>
                                        <span class="fa fa-chevron-right" aria-hidden="true"></span>
                                    </a>
                                </li>
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
                            @if(request('min_price'))
                                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                            @endif
                            @if(request('max_price'))
                                <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                            @endif
                        </form>
                    </div>

                    <!-- Price Filter -->
                    <div class="product-widget">
                        <h4 class="product-widget-title">Price Filter</h4>
                        <div class="product-widget-range-slider">
                            <div class="slider-range" id="slider-range"></div>
                            <div class="slider-labels">
                                <span id="slider-range-value1">${{ number_format(request('min_price') ?: $minPrice, 0) }}</span>
                                <span>—</span>
                                <span id="slider-range-value2">${{ number_format(request('max_price') ?: $maxPrice, 0) }}</span>
                            </div>
                            <form action="{{ route('shop.index') }}" method="GET" id="price-filter-form" style="display: none;">
                                <input type="hidden" name="min_price" id="min-price-input" value="{{ request('min_price') ?: $minPrice }}">
                                <input type="hidden" name="max_price" id="max-price-input" value="{{ request('max_price') ?: $maxPrice }}">
                                @if(request('collection'))
                                    <input type="hidden" name="collection" value="{{ request('collection') }}">
                                @endif
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif
                            </form>
                        </div>
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

                    <!-- Popular Tags -->
                    @if(isset($popularTags) && !empty($popularTags))
                        <div class="product-widget">
                            <h4 class="product-widget-title">Popular Tags</h4>
                            <ul class="product-widget-tags">
                                @foreach($popularTags as $tag)
                                    <li>
                                        <a href="{{ route('shop.index', ['search' => $tag]) }}">{{ $tag }}</a>
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

<!--== Start Product Banner Area Wrapper ==-->
<section class="pb-10">
    <div class="container">
        <a href="{{ route('shop.index') }}" class="product-banner-item">
            <img src="https://template.hasthemes.com/brancy/brancy/assets/images/shop/banner/7.webp" width="1170" height="240" alt="Shop Banner">
        </a>
    </div>
</section>
<!--== End Product Banner Area Wrapper ==-->

<!--== Start Related Products Area Wrapper ==-->
@if($products->isNotEmpty())
<section class="section-space pt-0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2 class="title">Related Products</h2>
                    <p class="m-0">Discover more products you might like from our collection</p>
                </div>
            </div>
        </div>
        <div class="row mb-n10">
            <div class="col-12">
                <div class="swiper related-product-slide-container">
                    <div class="swiper-wrapper">
                        @foreach($products->take(6) as $relatedProduct)
                        <div class="swiper-slide mb-8">
                            @include('frontend.components.product-card', ['product' => $relatedProduct])
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!--== End Related Products Area Wrapper ==-->
@endsection

@push('styles')
<style>
    /* Product Banner Styling */
    .product-banner-item {
        display: block;
        overflow: hidden;
        border-radius: 8px;
    }
    .product-banner-item img {
        width: 100%;
        height: auto;
        transition: transform 0.4s ease;
    }
    .product-banner-item:hover img {
        transform: scale(1.02);
    }

    /* Section Title Styling */
    .section-title {
        text-align: center;
        margin-bottom: 40px;
    }
    .section-title .title {
        font-size: 36px;
        font-weight: 500;
        color: #231942;
        margin-bottom: 15px;
    }
    .section-title p {
        color: #747474;
        font-size: 16px;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Related Products Slider */
    .related-product-slide-container {
        overflow: hidden;
    }
    .related-product-slide-container .swiper-slide {
        height: auto;
    }

    /* Ensure widget styling matches template */
    .product-sidebar-widget .product-widget {
        background-color: #f7f7f7c2;
        border: 1px solid #eeeeeedb;
        border-radius: 0;
        margin-bottom: 40px;
        padding: 25px 30px 24px;
    }
    .product-sidebar-widget .product-widget-title {
        color: #231942;
        font-size: 18px;
        font-weight: 500;
        line-height: 1;
        display: inline-block;
        margin-bottom: 22px;
        position: relative;
        padding-left: 22px;
    }
    .product-sidebar-widget .product-widget-title:before {
        border: 2px solid #22C55E;
        border-radius: 50%;
        content: "";
        height: 11px;
        left: 0;
        position: absolute;
        top: 50%;
        width: 11px;
        transform: translate(0px, -50%);
    }
    /* Price Filter - EXACT match to original Brancy template */
    .product-sidebar-widget .product-widget-range-slider .noUi-target {
        background: #D6D7D9;
        border: none;
        border-radius: 0;
        box-shadow: none;
        width: 100%;
    }
    .product-sidebar-widget .product-widget-range-slider .noUi-horizontal {
        height: 4px;
    }
    .product-sidebar-widget .product-widget-range-slider .noUi-connect {
        background-color: #A8DADC;
    }
    .product-sidebar-widget .product-widget-range-slider .noUi-horizontal .noUi-handle {
        background-color: #457B9D;
        border: none;
        border-radius: 50%;
        box-shadow: none;
        cursor: pointer;
        width: 10px;
        height: 10px;
        top: 50%;
        right: -5px;
        transform: translateY(-50%);
    }
    .product-sidebar-widget .product-widget-range-slider .noUi-horizontal .noUi-handle::before,
    .product-sidebar-widget .product-widget-range-slider .noUi-horizontal .noUi-handle::after {
        display: none;
    }
    .product-sidebar-widget .product-widget-range-slider .slider-labels {
        margin-top: 14px;
    }
    .product-sidebar-widget .product-widget-range-slider .slider-labels span {
        font-weight: 500;
        font-size: 14px;
        color: #1D3557;
    }
    /* Category styling */
    .product-sidebar-widget .product-widget-category {
        margin-bottom: 0;
        padding-left: 0;
        list-style: none;
    }
    .product-sidebar-widget .product-widget-category li {
        display: block;
    }
    .product-sidebar-widget .product-widget-category li a {
        border-top: 1px solid #e7e7e7;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        color: #747474;
        transition: color 0.3s ease;
    }
    .product-sidebar-widget .product-widget-category li:first-child a {
        border-top: none;
        padding-top: 0;
    }
    .product-sidebar-widget .product-widget-category li:last-child a {
        padding-bottom: 0;
    }
    .product-sidebar-widget .product-widget-category li a:hover {
        color: #22C55E;
    }
    .product-sidebar-widget .product-widget-category li a.active {
        color: #22C55E;
        font-weight: 600;
    }
    .product-sidebar-widget .product-widget-category li a span {
        color: #747474;
    }

    /* Tags styling */
    .product-sidebar-widget .product-widget-tags {
        margin-bottom: 0;
        padding-left: 0;
        list-style: none;
    }
    .product-sidebar-widget .product-widget-tags li {
        display: inline-block;
        margin-right: 3px;
        margin-bottom: 6px;
    }
    .product-sidebar-widget .product-widget-tags li a {
        background-color: #f9f9f9;
        border: 1px solid #d9d9d9;
        border-radius: 0;
        display: inline-block;
        font-size: 13px;
        font-weight: 400;
        line-height: 1;
        padding: 9px 16px;
        text-transform: capitalize;
        color: #747474;
        transition: all 0.3s ease;
    }
    .product-sidebar-widget .product-widget-tags li a:hover {
        background-color: #22C55E;
        border-color: #22C55E;
        color: #fff;
    }
    /* Sort By select dropdown styling */
    .product-sidebar-widget .product-widget #sort-form .form-select,
    .product-sidebar-widget .product-widget select.form-select {
        width: 100%;
        padding: 12px 40px 12px 18px;
        font-size: 14px;
        font-weight: 400;
        color: #1c1c1c;
        background-color: #ffffff;
        border: 1px solid #ebeef5;
        border-radius: 4px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235a5a5a' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 18px center;
        background-size: 12px;
        cursor: pointer;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .product-sidebar-widget .product-widget #sort-form .form-select:focus,
    .product-sidebar-widget .product-widget select.form-select:focus {
        border-color: #a6b1c2;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
        outline: none;
    }
    .product-sidebar-widget .product-widget #sort-form .form-select:hover,
    .product-sidebar-widget .product-widget select.form-select:hover {
        border-color: #a6b1c2;
    }

    /* Search widget styling */
    .product-sidebar-widget .product-widget-search {
        margin-bottom: 40px;
        position: relative;
    }
    .product-sidebar-widget .product-widget-search input[type="search"] {
        background-color: #f7f7f7c2;
        border: 1px solid #eeeeeedb;
        border-radius: 0;
        color: #444444;
        font-size: 14px;
        height: 50px;
        padding: 5px 50px 5px 20px;
        width: 100%;
        transition: border-color 0.3s ease;
    }
    .product-sidebar-widget .product-widget-search input[type="search"]:focus {
        border-color: #22C55E;
        outline: none;
    }
    .product-sidebar-widget .product-widget-search input[type="search"]::placeholder {
        color: #747474;
    }
    .product-sidebar-widget .product-widget-search button[type="submit"] {
        border: none;
        background: none;
        position: absolute;
        font-size: 16px;
        color: #444444;
        cursor: pointer;
        padding: 0;
        height: 50px;
        line-height: 50px;
        width: 50px;
        right: 0;
        top: 0;
        transition: color 0.3s ease;
    }
    .product-sidebar-widget .product-widget-search button[type="submit"]:hover {
        color: #22C55E;
    }

    /* Pagination styling to match template */
    .pagination {
        gap: 8px;
    }
    .pagination .page-item .page-link {
        border: none;
        background: transparent;
        color: #747474;
        font-size: 16px;
        font-weight: 500;
        padding: 8px 12px;
        min-width: 40px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .pagination .page-item .page-link:hover {
        color: #22C55E;
        background: transparent;
    }
    .pagination .page-item.active .page-link {
        color: #22C55E;
        background: transparent;
        font-weight: 600;
    }
    .pagination .page-item .page-link.previous,
    .pagination .page-item .page-link.next {
        font-size: 14px;
    }
</style>
@endpush

@push('scripts')
<script>
// Initialize Related Products Swiper
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper !== 'undefined') {
        var relatedProductSwiper = new Swiper('.related-product-slide-container', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: false,
            breakpoints: {
                576: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                992: {
                    slidesPerView: 4,
                    spaceBetween: 30,
                },
            },
        });
    }
});
</script>
<script>
// CRITICAL: Prevent original range-slider.js auto-init by removing the element temporarily
// The original script runs $(document).ready() immediately when loaded, so we need to
// prevent it from finding the element OR override its ready handler BEFORE it executes
(function() {
    // Store reference to element
    var sliderEl = document.getElementById('slider-range');
    if (sliderEl) {
        // Remove from DOM temporarily to prevent original script from finding it
        var parent = sliderEl.parentNode;
        var nextSibling = sliderEl.nextSibling;
        parent.removeChild(sliderEl);
        window._sliderRangeElement = sliderEl;
        window._sliderRangeParent = parent;
        window._sliderRangeNextSibling = nextSibling;
    }
})();
</script>
<script src="{{ asset('brancy/js/plugins/range-slider.js') }}"></script>
<script>
// Restore element and initialize with our values
(function() {
    // Restore the element to DOM
    if (window._sliderRangeElement && window._sliderRangeParent) {
        if (window._sliderRangeNextSibling) {
            window._sliderRangeParent.insertBefore(window._sliderRangeElement, window._sliderRangeNextSibling);
        } else {
            window._sliderRangeParent.appendChild(window._sliderRangeElement);
        }
    }
    
    // Wait for DOM and jQuery to be ready
    if (typeof $ !== 'undefined') {
        $(document).ready(function() {
            // Small delay to ensure everything is loaded
            setTimeout(function() {
                var sliderRange = document.getElementById('slider-range');
                
                if (!sliderRange) {
                    console.error('[Price Filter] Slider element not found');
                    return;
                }
                
                if (typeof noUiSlider === 'undefined') {
                    console.error('[Price Filter] noUiSlider library not found');
                    return;
                }
                
                // Destroy any existing instance (from original script if it somehow ran)
                if (sliderRange.noUiSlider) {
                    try {
                        sliderRange.noUiSlider.destroy();
                    } catch(e) {
                        console.warn('[Price Filter] Error destroying existing slider:', e);
                    }
                }
                
                // Get price values from PHP - ensure they are numbers
                var minPrice = {{ $minPrice ?? 430 }};
                var maxPrice = {{ $maxPrice ?? 2500 }};
                var currentMin = {{ request('min_price') ? (int)request('min_price') : ($minPrice ?? 430) }};
                var currentMax = {{ request('max_price') ? (int)request('max_price') : ($maxPrice ?? 2500) }};
                
                // Validate values
                minPrice = parseInt(minPrice) || 430;
                maxPrice = parseInt(maxPrice) || 2500;
                currentMin = parseInt(currentMin) || minPrice;
                currentMax = parseInt(currentMax) || maxPrice;
                
                // Ensure values are within valid range
                if (minPrice <= 0) minPrice = 430;
                if (maxPrice <= minPrice) maxPrice = 2500;
                currentMin = Math.max(minPrice, Math.min(currentMin, maxPrice));
                currentMax = Math.max(minPrice, Math.min(currentMax, maxPrice));
                if (currentMin > currentMax) {
                    currentMin = minPrice;
                    currentMax = maxPrice;
                }
                
                // Create money formatter
                var moneyFormat;
                if (typeof wNumb !== 'undefined') {
                    moneyFormat = wNumb({
                        decimals: 0,
                        thousand: ',',
                        prefix: '$'
                    });
                } else {
                    moneyFormat = {
                        to: function(value) {
                            return '$' + Math.round(value);
                        },
                        from: function(value) {
                            return Number(value);
                        }
                    };
                }
                
                try {
                    // Create the slider
                    noUiSlider.create(sliderRange, {
                        start: [currentMin, currentMax],
                        step: 10,
                        range: {
                            'min': minPrice,
                            'max': maxPrice
                        },
                        connect: true,
                        format: moneyFormat
                    });
                    
                    // Update display labels on slider change
                    sliderRange.noUiSlider.on('update', function(values, handle) {
                        var formattedValue = values[handle];
                        var numericValue = moneyFormat.from(values[handle]);
                        
                        if (handle === 0) {
                            var el1 = document.getElementById('slider-range-value1');
                            var input1 = document.getElementById('min-price-input');
                            if (el1) el1.textContent = formattedValue;
                            if (input1) input1.value = numericValue;
                        } else {
                            var el2 = document.getElementById('slider-range-value2');
                            var input2 = document.getElementById('max-price-input');
                            if (el2) el2.textContent = formattedValue;
                            if (input2) input2.value = numericValue;
                        }
                    });
                    
                    // Auto-submit form when slider changes (debounced)
                    var submitTimeout;
                    sliderRange.noUiSlider.on('change', function(values) {
                        clearTimeout(submitTimeout);
                        submitTimeout = setTimeout(function() {
                            var form = document.getElementById('price-filter-form');
                            if (form) {
                                var minVal = moneyFormat.from(values[0]);
                                var maxVal = moneyFormat.from(values[1]);
                                var minInput = document.getElementById('min-price-input');
                                var maxInput = document.getElementById('max-price-input');
                                if (minInput) minInput.value = minVal;
                                if (maxInput) maxInput.value = maxVal;
                                form.submit();
                            }
                        }, 500);
                    });
                    
                    console.log('[Price Filter] Successfully initialized:', {
                        min: minPrice,
                        max: maxPrice,
                        current: [currentMin, currentMax]
                    });
                } catch (e) {
                    console.error('[Price Filter] Error initializing slider:', e);
                }
            }, 50);
        });
    } else {
        console.error('[Price Filter] jQuery not available');
    }
})();
</script>
@endpush

{{-- Add to cart handler is now in the global layout (app.blade.php) --}}
