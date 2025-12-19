<footer class="footer">
    <!-- Start Footer Top -->
{{--    <div class="footer-top">--}}
{{--        <div class="container">--}}
{{--            <div class="inner-content">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-lg-3 col-md-4 col-12">--}}
{{--                        <div class="footer-logo">--}}
{{--                            <a href="{{ route('shop.index') }}">--}}
{{--                                <img src="{{ asset('build/clients/images/logo/white-logo.svg') }}" alt="#">--}}
{{--                                <img src="{{ asset('build/images/removebg.png') }}" alt="#">--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-9 col-md-8 col-12">--}}
{{--                        <div class="footer-newsletter">--}}
{{--                            <h4 class="title">--}}
{{--                                Subscribe to our Newsletter--}}
{{--                                <span>Get all the latest information, Sales and Offers.</span>--}}
{{--                            </h4>--}}
{{--                            <div class="newsletter-form-head">--}}
{{--                                <form action="#" method="get" target="_blank" class="newsletter-form">--}}
{{--                                    <input name="EMAIL" placeholder="Email address here..." type="email">--}}
{{--                                    <div class="button">--}}
{{--                                        <button class="btn">Subscribe<span class="dir-part"></span></button>--}}
{{--                                    </div>--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <!-- End Footer Top -->

    <!-- Start Footer Middle -->
    <div class="footer-middle">
        <div class="container">
            <div class="bottom-inner">
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-md-6 col-12">
                        <!-- Single Widget -->
                        <div class="single-footer f-contact">
                            <h3>Свяжитесь с нами</h3>
                            @if(setting('site_phone'))
                                <p class="phone">Телефон: {{ setting('site_phone') }}</p>
                            @endif
                            <ul>
                                <li><span>Понедельник-Пятница: </span> 9:00 - 18:00 вечера</li>
                                <li><span>Суббота: </span> 9:00 - 18:00 вечера</li>
                            </ul>
                            @if(setting('site_email'))
                                <p class="mail">
                                    <a href="mailto:{{ setting('site_email') }}">{{ setting('site_email') }}</a>
                                </p>
                            @endif
                            @if(setting('site_address'))
                                <p class="address">
                                    <i class="lni lni-map-marker"></i> {{ setting('site_address') }}
                                </p>
                            @endif
                        </div>
                        <!-- End Single Widget -->
                    </div>
{{--                    <div class="col-lg-3 col-md-6 col-12">--}}
{{--                        <!-- Single Widget -->--}}
{{--                        <div class="single-footer our-app">--}}
{{--                            <h3>Our Mobile App</h3>--}}
{{--                            <ul class="app-btn">--}}
{{--                                <li>--}}
{{--                                    <a href="javascript:void(0)">--}}
{{--                                        <i class="lni lni-apple"></i>--}}
{{--                                        <span class="small-title">Download on the</span>--}}
{{--                                        <span class="big-title">App Store</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="javascript:void(0)">--}}
{{--                                        <i class="lni lni-play-store"></i>--}}
{{--                                        <span class="small-title">Download on the</span>--}}
{{--                                        <span class="big-title">Google Play</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                        <!-- End Single Widget -->--}}
{{--                    </div>--}}
                    <div class="col-lg-3 col-md-6 col-12">
                        <!-- Single Widget -->
                        <div class="single-footer f-link">
                            <h3>Информация</h3>
                            <ul>
                                <li><a href="{{ route('shop.products') }}">Товары</a></li>
                                <li><a href="{{ route('shop.about') }}">О нас</a></li>
                            </ul>
                        </div>
                        <!-- End Single Widget -->
                    </div>
{{--                    <div class="col-lg-3 col-md-6 col-12">--}}
{{--                        <!-- Single Widget -->--}}
{{--                        <div class="single-footer f-link">--}}
{{--                            <h3>Shop Departments</h3>--}}
{{--                            <ul>--}}
{{--                                <li><a href="javascript:void(0)">Computers & Accessories</a></li>--}}
{{--                                <li><a href="javascript:void(0)">Smartphones & Tablets</a></li>--}}
{{--                                <li><a href="javascript:void(0)">TV, Video & Audio</a></li>--}}
{{--                                <li><a href="javascript:void(0)">Cameras, Photo & Video</a></li>--}}
{{--                                <li><a href="javascript:void(0)">Headphones</a></li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                        <!-- End Single Widget -->--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Middle -->

    <!-- Start Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="inner-content">
                <div class="row align-items-center">
{{--                    <div class="col-lg-4 col-12">--}}
{{--                        <div class="payment-gateway">--}}
{{--                            <span>We Accept:</span>--}}
{{--                            <img src="{{ asset('build/clients/images/footer/credit-cards-footer.png') }}" alt="#">--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="col-lg-4 col-12">
                        @if(setting('site_facebook') || setting('site_twitter') || setting('site_instagram') || setting('site_youtube'))
                            <ul class="socila">
                                <li>
                                    <span>Подписывайтесь на нас:</span>
                                </li>
                                @if(setting('site_facebook'))
                                    <li><a href="{{ setting('site_facebook') }}" target="_blank"><i class="lni lni-facebook-filled"></i></a></li>
                                @endif
                                @if(setting('site_twitter'))
                                    <li><a href="{{ setting('site_twitter') }}" target="_blank"><i class="lni lni-twitter-original"></i></a></li>
                                @endif
                                @if(setting('site_instagram'))
                                    <li><a href="{{ setting('site_instagram') }}" target="_blank"><i class="lni lni-instagram"></i></a></li>
                                @endif
                                @if(setting('site_youtube'))
                                    <li><a href="{{ setting('site_youtube') }}" target="_blank"><i class="lni lni-youtube"></i></a></li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Bottom -->
</footer>
