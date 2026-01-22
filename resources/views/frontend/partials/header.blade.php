<!--== Start Header Wrapper ==-->
@php
    $isHomePage = request()->routeIs('home');
    $headerClass = $isHomePage ? 'header-area sticky-header header-transparent' : 'header-area sticky-header';
    $logoCols = $isHomePage ? 'col-5 col-lg-2 col-xl-1' : 'col-5 col-sm-6 col-lg-3';
    $navCols = $isHomePage ? 'col-lg-7 col-xl-7' : 'col-lg-6';
    $navPadding = $isHomePage ? 'ps-7' : '';
    $actionCols = $isHomePage ? 'col-7 col-lg-3 col-xl-4' : 'col-7 col-sm-6 col-lg-3';
@endphp
<header class="{{ $headerClass }}">
    <div class="container">
        <div class="row align-items-center">
            <div class="{{ $logoCols }}">
                <div class="header-logo">
                    <a href="{{ route('home') }}">
                        @if($isHomePage)
                            <img class="logo-main" src="{{ asset('brancy/images/logo-white.png') }}" width="95" height="68" alt="{{ config('app.name') }}" />
                        @else
                            <img class="logo-main" src="{{ asset('brancy/images/logo.png') }}" width="95" height="68" alt="{{ config('app.name') }}" />
                        @endif
                    </a>
                </div>
            </div>
            <div class="{{ $navCols }} d-none d-lg-block">
                <div class="header-navigation {{ $navPadding }}">
                    <ul class="main-nav justify-content-start">
                        <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">home</a></li>
                        <li class="{{ request()->routeIs('about') ? 'active' : '' }}"><a href="{{ route('about') }}">about</a></li>
                        <li class="{{ request()->routeIs(['products.*', 'shop.*', 'cart.*']) ? 'active' : '' }}"><a href="{{ route('products.index') }}">shop</a></li>
                        <li class="{{ request()->routeIs('blog.*') ? 'active' : '' }}"><a href="{{ route('blog.index') }}">blog</a></li>
                        <li class="{{ request()->routeIs('contact') ? 'active' : '' }}"><a href="{{ route('contact') }}">contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="{{ $actionCols }}">
                <div class="header-action justify-content-end">
                    <button class="header-action-btn ms-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#AsideOffcanvasSearch" aria-controls="AsideOffcanvasSearch">
                        <span class="icon">
                            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <rect class="icon-rect" width="30" height="30" fill="url(#pattern1)"/>
                                <defs>
                                    <pattern id="pattern1" patternContentUnits="objectBoundingBox" width="1" height="1">
                                        <use xlink:href="#image0_504:11" transform="scale(0.0333333)"/>
                                    </pattern>
                                    <image id="image0_504:11" width="30" height="30" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAABiUlEQVRIie2Wu04CQRSGP0G2EUtIbHwA8B3EQisLIcorEInx8hbEZ9DKy6toDI1oAgalNFpDoYWuxZzJjoTbmSXERP7kZDbZ859vdmb27MJcf0gBUAaugRbQk2gBV3IvmDa0BLwA4Zh4BorTACaAU6fwPXAI5IAliTxwBDScvJp4vWWhH0BlTLEEsC+5Fu6lkgNdV/gKDnxHCw2I9rSiNQNV8baBlMZYJtpTn71KAg9SY3dUYn9xezLPgG8P8BdwLteq5X7CzDbnAbXKS42WxtQVUzoGeFlqdEclxXrnhmhhkqR+8KuMqzHA1vumAddl3IwB3pLxVmOyr1NjwKQmURJ4lBp7GmOAafghpg1qdSDeDrCoNReJWmZB4dsAPsW7rYVa1Rx4FbOEw5TEPKmFvgMZX3DCgYeYNniMaQ5piTXghGhPLdTmZ33hYNpem98f/UHRwSxvhqhXx4anMA3/EmhiOlJPJnSBOb3uQcpOE65VhujPpAms/Bu4u+x3swRbeB24mTV4LgB+AFuLedkPkcumAAAAAElFTkSuQmCC"/>
                                </defs>
                            </svg>
                        </span>
                    </button>

                    @auth
                        <a class="header-action-btn" href="{{ route('account.dashboard') }}">
                            <span class="icon">
                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <rect class="icon-rect" width="30" height="30" fill="url(#pattern3)"/>
                                    <defs>
                                        <pattern id="pattern3" patternContentUnits="objectBoundingBox" width="1" height="1">
                                            <use xlink:href="#image0_504:10" transform="scale(0.0333333)"/>
                                        </pattern>
                                        <image id="image0_504:10" width="30" height="30" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAABEUlEQVRIie3UMUoDYRDF8Z8psqUpLBRrBS+gx7ATD6E5iSjeQQ/gJUzEwmChnZZaKZiQ0ljsLkhQM5/5Agr74DX7DfOfgZ1Hoz+qAl30Parcx2H1thCtY4DJN76parKqmAH9DM+6eTcArX2QE3yVAO7lBA8TwMNIw6UgeJI46My+rWCjUQL0LVIUBd8lgEO1UfBZAvg8oXamCuWNRu64nRNMmUo/wReSXLXayoDoKc9miMvqW/ZNG2VRNLla2MYudrCFTvX2intlnl/gGu/zDraGYzyLZ/UTjrD6G2AHpxgnAKc9xgmWo9BNPM4BnPYDNiLg24zQ2oNpyFdZvRKZLlGhnvvKPzXXti/Yy7hEo3+iD9EHtgdqxQnwAAAAAElFTkSuQmCC"/>
                                    </defs>
                                </svg>
                            </span>
                        </a>
                    @else
                        <a class="header-action-btn" href="{{ route('login') }}">
                            <span class="icon">
                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <rect class="icon-rect" width="30" height="30" fill="url(#pattern3)"/>
                                    <defs>
                                        <pattern id="pattern3" patternContentUnits="objectBoundingBox" width="1" height="1">
                                            <use xlink:href="#image0_504:10" transform="scale(0.0333333)"/>
                                        </pattern>
                                        <image id="image0_504:10" width="30" height="30" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAABEUlEQVRIie3UMUoDYRDF8Z8psqUpLBRrBS+gx7ATD6E5iSjeQQ/gJUzEwmChnZZaKZiQ0ljsLkhQM5/5Agr74DX7DfOfgZ1Hoz+qAl30Narcx2H1thCtY4DJN76parKqmAH9DM+6eTcArX2QE3yVAO7lBA8TwMNIw6UgeJI46My+rWCjUQL0LVIUBd8lgEO1UfBZAvg8oXamCuWNRu64nRNMmUo/wReSXLXayoDoKc9miMvqW/ZNG2VRNLla2MYudrCFTvX2intlnl/gGu/zDraGYzyLZ/UTjrD6G2AHpxgnAKc9xgmWo9BNPM4BnPYDNiLg24zQ2oNpyFdZvRKZLlGhnvvKPzXXti/Yy7hEo3+iD9EHtgdqxQnwAAAAAElFTkSuQmCC"/>
                                    </defs>
                                </svg>
                            </span>
                        </a>
                    @endauth

                    <a class="header-action-btn" href="{{ route('wishlist.index') }}">
                        <span class="icon">
                            <i class="fa fa-heart-o"></i>
                        </span>
                        @php
                            $wishlistCount = auth()->check() 
                                ? \App\Models\Wishlist::where('user_id', auth()->id())->count()
                                : \App\Models\Wishlist::where('session_id', session()->getId())->count();
                        @endphp
                        @if($wishlistCount > 0)
                            <span class="badge wishlist-count">{{ $wishlistCount }}</span>
                        @endif
                    </a>

                    <button class="header-action-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#AsideOffcanvasCart" aria-controls="AsideOffcanvasCart">
                        <span class="icon">
                            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <rect class="icon-rect" width="30" height="30" fill="url(#pattern2)"/>
                                <defs>
                                    <pattern id="pattern2" patternContentUnits="objectBoundingBox" width="1" height="1">
                                        <use xlink:href="#image0_504:9" transform="scale(0.0333333)"/>
                                    </pattern>
                                    <image id="image0_504:9" width="30" height="30" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAABFUlEQVRIie2VMU7DMBSGvwAqawaYuAmKxCW4A1I5Qg4AA93KBbp1ZUVUlQJSVVbCDVhgzcTQdLEVx7WDQ2xLRfzSvzzb+d6zn2MYrkugBBYevuWsHKiFn2JBMwH8Bq6Aw1jgBwHOYwGlPgT4LDZ4I8BJDNiEppl034UEJ8DMAJ0DByHBACPgUYEugePQUKkUWAmnsaB/Ry/YO9aXCwlT72AdrqaWEohwBWxSwc8ReIVtYIr5bM5pXqO+Men7rozGlkVSv4lJj1WQfsbvXVkNVNk1eEK4ik9/yuwzAPhLh5iuU4jtftMDR4ZJJXChxTJ2H3zXGDgWc43/X2Wro8G81a8u2fXU2nXiLVAxvNIKuPGW/r/2SltF+a3Rkw4pmwAAAABJRU5ErkJggg=="/>
                                </defs>
                            </svg>
                        </span>
                        @php
                            try {
                                $cart = \Lunar\Facades\CartSession::current();
                                $cartCount = $cart ? $cart->lines->sum('quantity') : 0;
                            } catch (\Exception $e) {
                                $cartCount = 0;
                            }
                        @endphp
                        <span class="badge cart-count">{{ $cartCount }}</span>
                    </button>

                    <button class="header-menu-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#AsideOffcanvasMenu" aria-controls="AsideOffcanvasMenu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
<!--== End Header Wrapper ==-->

<!--== Start Aside Search Form ==-->
<aside class="aside-search-box-wrapper offcanvas offcanvas-top" tabindex="-1" id="AsideOffcanvasSearch" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
        <h5 class="d-none" id="offcanvasTopLabel">Search</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="container pt--0 pb--0">
            <div class="search-box-form-wrap">
                <div class="search-note">
                    <p>Start typing and press Enter to search</p>
                </div>
                <form action="{{ route('products.index') }}" method="get">
                    <div class="aside-search-form position-relative">
                        <label for="SearchInput" class="visually-hidden">Search</label>
                        <input id="SearchInput" type="search" name="search" class="form-control" placeholder="Search products…" value="{{ request('search') }}">
                        <button class="search-button" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</aside>
<!--== End Aside Search Form ==-->

<!--== Start Aside Cart ==-->
<aside class="aside-cart-wrapper offcanvas offcanvas-end" tabindex="-1" id="AsideOffcanvasCart" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h1 class="d-none" id="offcanvasRightLabel">Shopping Cart</h1>
        <button class="btn-aside-cart-close" data-bs-dismiss="offcanvas" aria-label="Close">Shopping Cart <i class="fa fa-chevron-right"></i></button>
    </div>
    <div class="offcanvas-body">
        @php
            try {
                $cart = \Lunar\Facades\CartSession::current();
                $cartLines = $cart ? $cart->lines : collect();
                $cartTotal = $cart ? $cart->total : 0;
            } catch (\Exception $e) {
                $cart = null;
                $cartLines = collect();
                $cartTotal = 0;
            }
        @endphp
        @if($cartLines->isNotEmpty())
            <ul class="aside-cart-product-list">
                @foreach($cartLines as $line)
                    <li class="aside-product-list-item">
                        <a href="{{ route('cart.remove', $line->id) }}" class="remove" onclick="event.preventDefault(); document.getElementById('remove-cart-{{ $line->id }}').submit();">×</a>
                        <form id="remove-cart-{{ $line->id }}" action="{{ route('cart.remove', $line->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <a href="{{ route('shop.product.show', $line->purchasable->product->defaultUrl?->slug ?? $line->purchasable->product->id) }}">
                            @if($line->purchasable->product->thumbnail)
                                <img src="{{ $line->purchasable->product->thumbnail->getUrl('small') }}" width="68" height="84" alt="{{ $line->purchasable->product->translateAttribute('name') }}">
                            @else
                                <img src="{{ asset('brancy/images/shop/cart1.webp') }}" width="68" height="84" alt="{{ $line->purchasable->product->translateAttribute('name') }}">
                            @endif
                            <span class="product-title">{{ $line->purchasable->product->translateAttribute('name') }}</span>
                        </a>
                        <span class="product-price">{{ $line->quantity }} × {{ $line->subTotal->formatted }}</span>
                    </li>
                @endforeach
            </ul>
            <p class="cart-total"><span>Subtotal:</span><span class="amount">{{ $cartTotal->formatted }}</span></p>
            <a class="btn-total" href="{{ route('cart.index') }}">View cart</a>
            <a class="btn-total" href="{{ route('checkout.index') }}">Checkout</a>
        @else
            <div class="text-center py-5">
                <p>Your cart is empty</p>
                <a class="btn-total" href="{{ route('products.index') }}">Continue Shopping</a>
            </div>
        @endif
    </div>
</aside>
<!--== End Aside Cart ==-->

<!--== Start Mobile Menu ==-->
<aside class="aside-menu-wrapper offcanvas offcanvas-end" tabindex="-1" id="AsideOffcanvasMenu" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h1 class="d-none" id="offcanvasRightLabel">Aside Menu</h1>
        <button class="btn-menu-close" data-bs-dismiss="offcanvas" aria-label="Close">Menu <i class="fa fa-chevron-right"></i></button>
    </div>
    <div class="offcanvas-body">
        <div id="offcanvasNav" class="offcanvas-menu-nav">
            <ul>
                <li><a class="offcanvas-nav-item" href="{{ route('home') }}">home</a></li>
                <li><a class="offcanvas-nav-item" href="{{ route('about') }}">about</a></li>
                <li><a class="offcanvas-nav-item" href="{{ route('products.index') }}">products</a></li>
                <li><a class="offcanvas-nav-item" href="{{ route('blog.index') }}">blog</a></li>
                <li><a class="offcanvas-nav-item" href="{{ route('contact') }}">contact</a></li>
            </ul>
        </div>
    </div>
</aside>
<!--== End Mobile Menu ==-->
