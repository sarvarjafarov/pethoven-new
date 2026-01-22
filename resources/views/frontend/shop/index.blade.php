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
                            <div id="slider-range" class="noUi-target"></div>
                            <div class="slider-labels">
                                <span id="slider-range-value1">${{ number_format(request('min_price', $minPrice), 0) }}</span>
                                <span> — </span>
                                <span id="slider-range-value2">${{ number_format(request('max_price', $maxPrice), 0) }}</span>
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
@endsection

@push('styles')
<style>
    /* Ensure widget styling matches template */
    .product-sidebar-widget .product-widget {
        background-color: #ffffff;
        border: 1px solid #eeeeee;
        border-radius: 8px;
        margin-bottom: 40px;
        padding: 25px 30px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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
    /* Price Filter - match original template exactly */
    .product-sidebar-widget .product-widget-range-slider .noUi-connect {
        background-color: #A8DADC;
    }
    .product-sidebar-widget .product-widget-range-slider .noUi-horizontal {
        height: 4px;
    }
    .product-sidebar-widget .product-widget-range-slider .noUi-horizontal .noUi-handle {
        background-color: #457B9D;
        cursor: pointer;
        width: 10px;
        height: 10px;
        top: 50%;
        transform: translate(0px, -50%);
        -webkit-transform: translate(0px, -50%);
        -moz-transform: translate(0px, -50%);
        -ms-transform: translate(0px, -50%);
        -o-transform: translate(0px, -50%);
    }
    .product-sidebar-widget .product-widget-range-slider .noUi-target {
        border-radius: 0;
        width: 100%;
    }
    .product-sidebar-widget .product-widget-range-slider .slider-labels {
        margin-top: 14px;
    }
    .product-sidebar-widget .product-widget-range-slider .slider-labels span {
        font-weight: 500;
        font-size: 14px;
        color: #1D3557;
    }
    /* Category active state */
    .product-sidebar-widget .product-widget-category li a.active {
        color: #22C55E;
        font-weight: 600;
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
</style>
@endpush

@push('scripts')
<script>
// Store original jQuery ready function to prevent auto-init
var originalJQueryReady = null;
if (typeof $ !== 'undefined' && $.fn.ready) {
    // We'll handle this differently
}
</script>
<script src="{{ asset('brancy/js/plugins/range-slider.js') }}"></script>
<script>
// Immediately after range-slider.js loads, prevent its auto-init
(function() {
    // Store reference to slider element before auto-init can run
    var sliderElement = document.getElementById('slider-range');
    if (sliderElement && sliderElement.noUiSlider) {
        try {
            sliderElement.noUiSlider.destroy();
        } catch(e) {
            // Ignore
        }
    }
})();

$(document).ready(function() {
    // Wait for DOM and scripts to be fully ready
    setTimeout(function() {
        // Initialize price range slider
        var sliderRange = document.getElementById('slider-range');
        
        // Check if slider exists
        if (!sliderRange) {
            console.error('Slider element not found');
            return;
        }
        
        // Check if noUiSlider is available
        if (typeof noUiSlider === 'undefined') {
            console.error('noUiSlider not found');
            return;
        }
        
        // Destroy existing slider if it exists (from original range-slider.js auto-init)
        if (sliderRange.noUiSlider) {
            try {
                sliderRange.noUiSlider.destroy();
            } catch(e) {
                // Ignore destroy errors
            }
        }
        
        // Get price values - ensure they are numbers
        var minPrice = {{ $minPrice }};
        var maxPrice = {{ $maxPrice }};
        var currentMin = {{ request('min_price') ? request('min_price') : $minPrice }};
        var currentMax = {{ request('max_price') ? request('max_price') : $maxPrice }};
        
        // Ensure values are valid numbers
        minPrice = parseInt(minPrice) || 430;
        maxPrice = parseInt(maxPrice) || 2500;
        currentMin = parseInt(currentMin) || minPrice;
        currentMax = parseInt(currentMax) || maxPrice;
        
        // Ensure values are within range
        currentMin = Math.max(minPrice, Math.min(currentMin, maxPrice));
        currentMax = Math.max(minPrice, Math.min(currentMax, maxPrice));
        if (currentMin > currentMax) {
            currentMin = minPrice;
            currentMax = maxPrice;
        }
        
        // Use wNumb formatter (included in range-slider.js)
        var moneyFormat;
        if (typeof wNumb !== 'undefined') {
            moneyFormat = wNumb({
                decimals: 0,
                thousand: ',',
                prefix: '$'
            });
        } else {
            // Fallback formatter
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
            
            // Update display values on slider change
            sliderRange.noUiSlider.on('update', function(values, handle) {
                var formattedValue = moneyFormat.to(values[handle]);
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
            
            // Submit form when slider changes (with debounce)
            var timeout;
            sliderRange.noUiSlider.on('change', function(values) {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    var form = document.getElementById('price-filter-form');
                    if (form) {
                        // Update form values before submit
                        var minVal = moneyFormat.from(values[0]);
                        var maxVal = moneyFormat.from(values[1]);
                        document.getElementById('min-price-input').value = minVal;
                        document.getElementById('max-price-input').value = maxVal;
                        form.submit();
                    }
                }, 500);
            });
        } catch (e) {
            console.error('Error initializing slider:', e);
        }
    }, 400);
});
</script>
@endpush

{{-- Add to cart handler is now in the global layout (app.blade.php) --}}
