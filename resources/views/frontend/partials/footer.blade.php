<!--== Start Footer Area Wrapper ==-->
<footer class="footer-area">
    <!--== Start Footer Main ==-->
    <div class="footer-main">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="widget-item">
                        <div class="widget-about">
                            <a class="widget-logo" href="{{ route('home') }}">
                                <img src="{{ asset('brancy/images/logo.webp') }}" width="95" height="68" alt="{{ config('app.name') }}">
                            </a>
                            <p class="desc">{{ config('app.name') }} - Your trusted beauty and cosmetic destination. Quality products for your beautiful skin.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5 mt-md-0 mt-9">
                    <div class="widget-item">
                        <h4 class="widget-title">Information</h4>
                        <ul class="widget-nav">
                            <li><a href="{{ route('blog.index') }}">Blog</a></li>
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                            <li><a href="{{ route('faq') }}">FAQs</a></li>
                            <li><a href="{{ route('shop.index') }}">Shop</a></li>
                            @auth
                                <li><a href="{{ route('account.dashboard') }}">My Account</a></li>
                            @else
                                <li><a href="{{ route('login') }}">Login</a></li>
                            @endauth
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mt-lg-0 mt-6">
                    <div class="widget-item">
                        <h4 class="widget-title">Social Info</h4>
                        <div class="widget-social">
                            <a href="https://twitter.com/" target="_blank" rel="noopener"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.facebook.com/" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a>
                            <a href="https://www.pinterest.com/" target="_blank" rel="noopener"><i class="fa fa-pinterest-p"></i></a>
                            <a href="https://www.instagram.com/" target="_blank" rel="noopener"><i class="fa fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--== End Footer Main ==-->

    <!--== Start Footer Bottom ==-->
    <div class="footer-bottom">
        <div class="container pt-0 pb-0">
            <div class="footer-bottom-content">
                <p class="copyright">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
    <!--== End Footer Bottom ==-->
</footer>
<!--== End Footer Area Wrapper ==-->
