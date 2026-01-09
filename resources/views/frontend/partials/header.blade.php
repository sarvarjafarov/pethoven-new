<!--== Start Header Wrapper ==-->
<header class="header-area sticky-header header-transparent">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-5 col-lg-2 col-xl-1">
                <div class="header-logo">
                    <a href="{{ route('home') }}">
                        <img class="logo-main" src="{{ asset('brancy/images/logo.webp') }}" width="95" height="68" alt="{{ config('app.name') }}" />
                    </a>
                </div>
            </div>
            <div class="col-lg-7 col-xl-7 d-none d-lg-block">
                <div class="header-navigation ps-7">
                    <ul class="main-nav justify-content-start">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li class="has-submenu position-static"><a href="{{ route('shop.index') }}">Shop</a>
                            <ul class="submenu-nav-mega">
                                <li><a href="#/" class="mega-title">Shop Layout</a>
                                    <ul>
                                        <li><a href="{{ route('shop.index') }}">Shop Grid</a></li>
                                        <li><a href="{{ route('shop.index') }}">Shop with Sidebar</a></li>
                                    </ul>
                                </li>
                                <li><a href="#/" class="mega-title">Shop Pages</a>
                                    <ul>
                                        <li><a href="{{ route('cart.index') }}">Shopping Cart</a></li>
                                        <li><a href="{{ route('checkout.index') }}">Checkout</a></li>
                                        <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-7 col-lg-3 col-xl-4">
                <div class="header-action justify-content-end">
                    <button class="header-action-btn ms-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#AsideOffcanvasSearch" aria-controls="AsideOffcanvasSearch">
                        <span class="icon">
                            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect class="icon-rect" width="30" height="30" fill="currentColor"/>
                            </svg>
                        </span>
                    </button>

                    <a class="header-action-btn" href="{{ route('cart.index') }}">
                        <span class="icon">
                            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect class="icon-rect" width="30" height="30" fill="currentColor"/>
                            </svg>
                        </span>
                        @if(session()->has('cart_count') && session('cart_count') > 0)
                            <span class="badge">{{ session('cart_count') }}</span>
                        @endif
                    </a>

                    @auth
                        <a class="header-action-btn" href="{{ route('account.dashboard') }}">
                            <span class="icon">
                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect class="icon-rect" width="30" height="30" fill="currentColor"/>
                                </svg>
                            </span>
                        </a>
                    @else
                        <a class="header-action-btn" href="{{ route('login') }}">
                            <span class="icon">
                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect class="icon-rect" width="30" height="30" fill="currentColor"/>
                                </svg>
                            </span>
                        </a>
                    @endauth

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
                <form action="{{ route('shop.index') }}" method="get">
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

<!--== Start Mobile Menu ==-->
<aside class="aside-menu-wrapper offcanvas offcanvas-end" tabindex="-1" id="AsideOffcanvasMenu" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h1 class="d-none" id="offcanvasRightLabel">Aside Menu</h1>
        <button class="btn-menu-close" data-bs-dismiss="offcanvas" aria-label="Close">Menu <i class="fa fa-chevron-right"></i></button>
    </div>
    <div class="offcanvas-body">
        <div id="offcanvasNav" class="offcanvas-menu-nav">
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('about') }}">About</a></li>
                <li class="offcanvas-nav-parent">
                    <a class="offcanvas-nav-item" href="{{ route('shop.index') }}">Shop</a>
                </li>
                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
                @auth
                    <li><a href="{{ route('account.dashboard') }}">My Account</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-start w-100">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</aside>
<!--== End Mobile Menu ==-->
