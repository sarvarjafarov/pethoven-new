<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', config('app.name') . ' - Beauty & Cosmetic Salon')</title>
    <meta name="robots" content="@yield('robots', 'index, follow')" />
    <meta name="description" content="@yield('meta_description', 'Pethoven - Beauty & Cosmetic Salon')">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="@yield('meta_keywords', 'beauty, cosmetic, salon, spa, skincare, makeup')" />
    <meta name="author" content="{{ config('app.name') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:url" content="@yield('og_url', url()->current())" />
    <meta property="og:title" content="@yield('og_title', config('app.name') . ' - Beauty & Cosmetic Salon')" />
    <meta property="og:description" content="@yield('og_description', 'Pethoven - Premium beauty and cosmetic salon')" />
    <meta property="og:image" content="@yield('og_image', asset('brancy/images/logo.png'))" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="@yield('twitter_url', url()->current())" />
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name') . ' - Beauty & Cosmetic Salon')" />
    <meta name="twitter:description" content="@yield('twitter_description', 'Pethoven - Premium beauty and cosmetic salon')" />
    <meta name="twitter:image" content="@yield('twitter_image', asset('brancy/images/logo.png'))" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('brancy/images/favicon.webp') }}">

    <!-- CSS (Font, Vendor, Icon, Plugins & Style CSS files) -->

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS (Bootstrap) -->
    <link rel="stylesheet" href="{{ asset('brancy/css/vendor/bootstrap.min.css') }}">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('brancy/css/plugins/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('brancy/css/plugins/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('brancy/css/plugins/fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('brancy/css/plugins/nice-select.css') }}">

    <!-- Brancy Custom Style CSS -->
    <link rel="stylesheet" href="{{ asset('brancy/css/style.min.css') }}">

    <style>
        .nice-select.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 1px rgba(220, 53, 69, 0.25);
        }

        .nice-select.is-invalid .current {
            color: #dc3545;
        }

        .nice-select .list {
            max-height: 320px;
            overflow-y: auto;
        }

        .nice-select .list::-webkit-scrollbar {
            width: 6px;
        }

        .nice-select .list::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.15);
            border-radius: 3px;
        }
    </style>

    @stack('styles')

    <!-- Structured Data (JSON-LD) -->
    @stack('structured_data')
</head>

<body>

    <!--== Wrapper Start ==-->
    <div class="wrapper">

        <!--== Start Header ==-->
        @include('frontend.partials.header')
        <!--== End Header ==-->

        <!--== Start Main Content ==-->
        <main class="main-content">
            @yield('content')
        </main>
        <!--== End Main Content ==-->

        <!--== Start Footer ==-->
        @include('frontend.partials.footer')
        <!--== End Footer ==-->

        <!--== Scroll Top Button ==-->
        <div id="scroll-to-top" class="scroll-to-top"><span class="fa fa-angle-double-up"></span></div>

        <!--== Quick View Modal ==-->
        <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" id="quickViewContent">
                    <div class="text-center p-5">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--== Wrapper End ==-->

    <!-- JS Vendor, Plugins & Activation Script Files -->

    <!-- Vendors JS -->
    <script src="{{ asset('brancy/js/vendor/modernizr-3.11.7.min.js') }}"></script>
    <script src="{{ asset('brancy/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('brancy/js/vendor/jquery-migrate-3.3.2.min.js') }}"></script>
    <script src="{{ asset('brancy/js/vendor/bootstrap.bundle.min.js') }}"></script>

    <!-- Plugins JS -->
    <script src="{{ asset('brancy/js/plugins/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('brancy/js/plugins/fancybox.min.js') }}"></script>
    <script src="{{ asset('brancy/js/plugins/jquery.nice-select.min.js') }}"></script>

    <!-- Custom Main JS -->
    <script src="{{ asset('brancy/js/main.js') }}"></script>

    <!-- Quick View Script -->
    <script>
    $(document).ready(function() {
        // Setup CSRF token for all AJAX requests (after jQuery is loaded)
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Quick view modal trigger
        $(document).on('click', '.action-btn-quick-view', function(e) {
            e.preventDefault();

            const $productCard = $(this).closest('.product-item');
            let productSlug = null;

            // Try to get product slug from product link
            const $productLink = $productCard.find('.product-info .title a');
            if ($productLink.length) {
                const href = $productLink.attr('href');
                productSlug = href.split('/').pop();
            }

            if (!productSlug) {
                console.error('Could not find product slug');
                return;
            }

            // Show modal with loading state
            const $modal = $('#quickViewModal');
            const $content = $('#quickViewContent');

            $content.html('<div class="text-center p-5"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            $modal.modal('show');

            // Load quick view content via AJAX
            $.ajax({
                url: '/product/quick-view/' + productSlug,
                method: 'GET',
                success: function(response) {
                    $content.html(response);
                },
                error: function() {
                    $content.html('<div class="modal-header"><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="alert alert-danger">Error loading product details. Please try again.</div></div>');
                }
            });
        });
    });

    // Wishlist functionality
    $(document).on('click', '.action-btn-wishlist', function(e) {
        e.preventDefault();

        const $btn = $(this);
        const productId = $btn.data('product-id');
        const productName = $btn.data('product-name');
        const $icon = $btn.find('i');
        const isInWishlist = $icon.hasClass('fa-heart');

        if (!productId) {
            console.error('Product ID not found');
            return;
        }

        // Optimistic UI update
        $btn.prop('disabled', true);

        if (isInWishlist) {
            // Remove from wishlist
            $.ajax({
                url: '/wishlist/' + productId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $icon.removeClass('fa-heart').addClass('fa-heart-o');
                        $btn.attr('title', 'Add to Wishlist');

                        // Update wishlist count if exists
                        if (response.count !== undefined) {
                            $('.wishlist-count').text(response.count);
                            if (response.count === 0) {
                                $('.wishlist-count').hide();
                            }
                        }
                    }
                    $btn.prop('disabled', false);
                },
                error: function() {
                    alert('Failed to remove from wishlist');
                    $btn.prop('disabled', false);
                }
            });
        } else {
            // Add to wishlist
            $.ajax({
                url: '/wishlist/add',
                method: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $icon.removeClass('fa-heart-o').addClass('fa-heart');
                        $btn.attr('title', 'Remove from Wishlist');

                        // Update wishlist count if exists
                        if (response.count !== undefined) {
                            $('.wishlist-count').text(response.count).show();
                        }

                        // Optional: Show toast notification
                        if (productName) {
                            // You can add a toast notification here
                            console.log(productName + ' added to wishlist');
                        }
                    } else if (response.message) {
                        alert(response.message);
                    }
                    $btn.prop('disabled', false);
                },
                error: function() {
                    alert('Failed to add to wishlist');
                    $btn.prop('disabled', false);
                }
            });
        }
    });

    // Add to cart functionality (global handler for all pages)
    $(document).on('click', '.quick-add-to-cart', function(e) {
        e.preventDefault();

        const $btn = $(this);
        const variantId = $btn.data('variant-id');
        const productName = $btn.data('product-name');
        const productImage = $btn.data('product-image');
        const productUrl = $btn.data('product-url');

        if (!variantId) {
            alert('Product variant not available');
            return;
        }

        const originalHtml = $btn.html();
        $btn.prop('disabled', true);
        $btn.html('<i class="fa fa-spinner fa-spin"></i>');

        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                variant_id: variantId,
                quantity: 1
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count in header
                    const $cartBadge = $('.cart-count');
                    $cartBadge.text(response.cart_count);

                    if (response.cart_count > 0) {
                        $cartBadge.show();
                    }

                    // Show success modal
                    showAddToCartModal(productName, productImage, productUrl);

                    // Reset button
                    $btn.prop('disabled', false);
                    $btn.html(originalHtml);
                } else {
                    alert(response.message || 'Failed to add product to cart');
                    $btn.prop('disabled', false);
                    $btn.html(originalHtml);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error adding product to cart';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.status === 419) {
                    errorMsg = 'Session expired. Please refresh the page and try again.';
                }
                alert(errorMsg);
                $btn.prop('disabled', false);
                $btn.html(originalHtml);
            }
        });
    });

    </script>

    <script>
    $(document).ready(function() {
        function syncNiceSelectInvalidState() {
            $('select.is-invalid').each(function () {
                const $select = $(this);
                const $nice = $select.next('.nice-select');
                if ($nice.length) {
                    $nice.toggleClass('is-invalid', $select.hasClass('is-invalid'));
                }
            });
        }

        syncNiceSelectInvalidState();

        $(document).on('change', 'select', function () {
            syncNiceSelectInvalidState();
        });
    });
    </script>

    @stack('scripts')

    <!-- Add to Cart Success Modal -->
    <div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="addToCartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 540px;">
            <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
                <!-- Modal Header (Red) -->
                <div class="modal-header" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; padding: 18px 24px; position: relative;">
                    <h5 class="modal-title" id="addToCartModalLabel" style="font-size: 17px; font-weight: 700; letter-spacing: 0.3px; margin: 0; line-height: 1.3;">Added To Cart Successfully!</h5>
                    <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Close" style="color: #000; opacity: 1; text-shadow: none; font-size: 20px; line-height: 1; font-weight: 300; padding: 0; margin: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.2s ease; background: #ffffff; border: 1px solid #c8c8c8; box-shadow: 0 1px 3px rgba(0,0,0,0.1); cursor: pointer;" onmouseover="this.style.backgroundColor='#f5f5f5'; this.style.borderColor='#999';" onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='#c8c8c8';">
                        <span aria-hidden="true" style="display: block; line-height: 1;">&times;</span>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body" style="padding: 30px 24px; background: #ffffff;">
                    <div class="text-center">
                        <!-- Success Icon -->
                        <div style="margin-bottom: 24px;">
                            <div style="width: 64px; height: 64px; background: #4CAF50; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);">
                                <i class="fa fa-check" style="font-size: 32px; color: white; font-weight: bold;"></i>
                            </div>
                        </div>
                        <!-- Product Image Container -->
                        <div style="background: #fce4ec; padding: 28px; border-radius: 12px; margin-bottom: 20px; min-height: 220px; display: flex; align-items: center; justify-content: center; box-shadow: inset 0 2px 8px rgba(0,0,0,0.05);">
                            <img id="cart-modal-product-image" src="" alt="Product" style="max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 6px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1)); transition: transform 0.3s ease;" onerror="this.src='{{ asset('brancy/images/shop/1.webp') }}'" onload="this.style.opacity='1'">
                        </div>
                        <!-- Product Name -->
                        <h6 id="cart-modal-product-name" style="font-size: 18px; font-weight: 700; margin-bottom: 8px; color: #2c3e50; line-height: 1.4;"></h6>
                        <!-- Success Message -->
                        <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 0; line-height: 1.6; font-weight: 400;">Item has been added to your shopping cart.</p>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 20px 24px; background: #ffffff; display: flex; gap: 12px;">
                    <a href="{{ route('cart.index') }}" class="btn" style="flex: 1; padding: 12px 20px; background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; letter-spacing: 0.5px; text-transform: uppercase; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(231, 76, 60, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(231, 76, 60, 0.3)';">
                        <i class="fa fa-shopping-cart" style="font-size: 16px;"></i>
                        <span>VIEW CART</span>
                    </a>
                    <button type="button" class="btn" id="continueShoppingBtn" style="flex: 1; padding: 5px 20px; background: #ffffff; color: #c0392b; border: 1px solid #c0392b; border-radius: 8px; font-weight: 600; font-size: 14px; letter-spacing: 0.5px; text-transform: uppercase; transition: all 0.2s ease; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); outline: none;" onmouseover="this.style.boxShadow='0 3px 6px rgba(0,0,0,0.15)';" onmouseout="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)';">
                        CONTINUE SHOPPING
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        #addToCartModal .modal-content {
            animation: modalSlideIn 0.3s ease;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        #addToCartModal .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        #cart-modal-product-image {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        /* Override Bootstrap button styles for Continue Shopping button */
        #continueShoppingBtn.btn {
            background-color: #ffffff !important;
            color: #c0392b !important;
            border: 1px solid #c0392b !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            padding: 5px 20px !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
            transition: all 0.2s ease !important;
        }
        
        #continueShoppingBtn.btn:hover,
        #continueShoppingBtn.btn:focus,
        #continueShoppingBtn.btn:active {
            background-color: #ffffff !important;
            color: #c0392b !important;
            border-color: #c0392b !important;
            box-shadow: 0 3px 6px rgba(0,0,0,0.15) !important;
            outline: none !important;
        }
    </style>

    <script>
    // Function to show add to cart success modal
    function showAddToCartModal(productName, productImage, productUrl) {
        // Set modal content
        $('#cart-modal-product-name').text(productName || 'Product');
        var img = $('#cart-modal-product-image');
        img.css('opacity', '0');
        img.attr('src', productImage || '{{ asset('brancy/images/shop/1.webp') }}');
        
        // Show modal with animation
        $('#addToCartModal').modal('show');
        
        // Trigger image animation after modal is shown
        setTimeout(function() {
            $('#addToCartModal').addClass('show');
        }, 10);
        
        // Reset image opacity when new image loads
        img.on('load', function() {
            $(this).css('opacity', '1');
        });
    }

    // Handle Continue Shopping button click
    $(document).on('click', '#continueShoppingBtn', function(e) {
        e.preventDefault();
        $('#addToCartModal').modal('hide');
    });
    
    // Handle close button click (backup for Bootstrap compatibility)
    $(document).on('click', '.btn-close-modal', function(e) {
        e.preventDefault();
        $('#addToCartModal').modal('hide');
    });
    
    // Reset modal state when hidden
    $('#addToCartModal').on('hidden.bs.modal', function() {
        $(this).removeClass('show');
        $('#cart-modal-product-image').css('opacity', '0');
    });
    
    // Ensure green logo is always used (prevent any white logo from showing)
    $(document).ready(function() {
        var $logo = $('.header-logo img.logo-main');
        var greenLogoUrl = '{{ asset('brancy/images/logo.png') }}';
        
        if ($logo.length) {
            // Force green logo on page load
            var currentSrc = $logo.attr('src');
            if (currentSrc.indexOf('logo-white') !== -1) {
                $logo.attr('src', greenLogoUrl);
            }
            
            // Watch for any changes and force green logo
            if (typeof MutationObserver !== 'undefined') {
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'src') {
                            var src = $logo.attr('src');
                            if (src && src.indexOf('logo-white') !== -1) {
                                $logo.attr('src', greenLogoUrl);
                            }
                        }
                    });
                });
                
                observer.observe($logo[0], {
                    attributes: true,
                    attributeFilter: ['src']
                });
            }
        }
    });
    </script>

</body>

</html>
