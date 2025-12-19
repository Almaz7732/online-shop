@extends('clients.layouts.app')

@section('title', config('app.name', 'Example'))

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!-- Start Hero Area -->
<section class="hero-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-12 custom-padding-right">
                <div class="slider-head">
                    <!-- Start Hero Slider -->
                    <div class="hero-slider">
                        @forelse($carouselSlides as $slide)
                            <!-- Start Single Slider -->
                            <div class="single-slider"
                                 @if($slide->image_path)
                                     style="background-image: url({{ Storage::url($slide->image_path) }});"
                                 @else
                                     style="background-image: url({{ asset('build/images/default-image.png') }});"
                                 @endif
                            >
                                @php
                                    $textColor = $slide->text_color ? "color: {$slide->text_color}" : ''
                                @endphp
                                <div class="content" style="{{ $textColor }}">

                                    <h2 style="{{ $textColor }}">@if($slide->title)<span style="{{ $textColor }}">{{ $slide->title }}</span> @endif
                                        @if($slide->subtitle) {{ $slide->subtitle }} @endif
                                    </h2>
                                    @if($slide->description)<p>{{ $slide->description }}</p>@endif
                                    @if($slide->button_text && $slide->button_url)
                                        <div class="button">
                                            <a href="{{ $slide->button_url }}" class="btn">{{ $slide->button_text }}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- End Single Slider -->
                        @empty
                            <!-- Fallback slider when no slides exist -->
{{--                            <div class="single-slider" style="background-image: url({{ asset('build/clients/images/hero/slider-bg1.jpg') }});">--}}
{{--                                <div class="content">--}}
{{--                                    <h2><span>Welcome to our store</span>--}}
{{--                                        Best Products Awaiting You--}}
{{--                                    </h2>--}}
{{--                                    <p>Discover amazing products with great deals and excellent quality.</p>--}}
{{--                                    <div class="button">--}}
{{--                                        <a href="#" class="btn">Shop Now</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        @endforelse
                    </div>
                    <!-- End Hero Slider -->
                </div>
            </div>
{{--            <div class="col-lg-4 col-12">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-lg-12 col-md-6 col-12 md-custom-padding">--}}
{{--                        <!-- Start Small Banner -->--}}
{{--                        <div class="hero-small-banner" style="background-image: url('{{ asset('build/clients/images/hero/slider-bnr.jpg') }}');">--}}
{{--                            <div class="content">--}}
{{--                                <h2>--}}
{{--                                    <span>New line required</span>--}}
{{--                                    iPhone 12 Pro Max--}}
{{--                                </h2>--}}
{{--                                <h3>$259.99</h3>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- End Small Banner -->--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-12 col-md-6 col-12">--}}
{{--                        <!-- Start Small Banner -->--}}
{{--                        <div class="hero-small-banner style2">--}}
{{--                            <div class="content">--}}
{{--                                <h2>Weekly Sale!</h2>--}}
{{--                                <p>Saving up to 50% off all online store items this week.</p>--}}
{{--                                <div class="button">--}}
{{--                                    <a class="btn" href="#">Shop Now</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- Start Small Banner -->--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
</section>
<!-- End Hero Area -->

<!-- Start Trending Product Area -->
<section class="trending-product section" style="margin-top: 12px;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>СТОМАТОЛОГИЧЕСКОЕ ОБОРУДОВАНИЕ И МАТЕРИАЛЫ</h2>
{{--                    <p>There are many variations of passages of Lorem Ipsum available, but the majority have--}}
{{--                        suffered alteration in some form.</p>--}}
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <!-- Product Items -->
            @forelse($trendingProducts as $product)
                <div class="col-lg-3 col-md-6 col-6">
                    <!-- Start Single Product -->
                    <div class="single-product">
                        <div class="product-image">
                            @if($product->primaryImage && $product->primaryImage->image_path)
                                <img src="{{ Storage::url($product->primaryImage->image_path) }}" alt="{{ $product->name }}">
                            @else
                                <img src="{{URL::asset('build/images/default-image.png')}}" alt="{{ $product->name }}">
                            @endif

{{--                            @php--}}
{{--                                $random = rand(1, 10);--}}
{{--                            @endphp--}}
{{--                            @if($random <= 2)--}}
{{--                                <span class="new-tag">New</span>--}}
{{--                            @elseif($random <= 4)--}}
{{--                                <span class="sale-tag">-{{ rand(10, 50) }}%</span>--}}
{{--                            @endif--}}

                            <div class="button">
                                <a href="javascript:void(0)" class="btn add-to-cart" data-product-id="{{ $product->id }}" title="Добавить в корзину"><i class="lni lni-cart"></i> В корзину</a>
                                <a href="{{ route('shop.product-details', $product->slug) }}" class="btn mt-2" title="Показать">Показать</a>
                            </div>
                            <div class="wishlist-btn">
                                <a href="javascript:void(0)" class="wishlist-toggle" data-wishlist-id="{{ $product->id }}" title="Добавить в избранное">
                                    <i class="lni lni-heart"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-info">
                            <span class="category">
                                {{ $product->category ? $product->category->name : 'Без категории' }}
                            </span>
                            <h4 class="title">
                                <a href="{{ route('shop.product-details', $product->slug) }}">
                                    {{ $product->name }}
                                </a>
                            </h4>
{{--                            <ul class="review">--}}
{{--                                @php--}}
{{--                                    $rating = rand(4, 5);--}}
{{--                                @endphp--}}
{{--                                @for ($j = 1; $j <= 5; $j++)--}}
{{--                                    @if($j <= $rating)--}}
{{--                                        <li><i class="lni lni-star-filled"></i></li>--}}
{{--                                    @else--}}
{{--                                        <li><i class="lni lni-star"></i></li>--}}
{{--                                    @endif--}}
{{--                                @endfor--}}
{{--                                <li><span>{{ $rating }}.0 Review(s)</span></li>--}}
{{--                            </ul>--}}
                            <div class="price">
                                <span>{{ number_format($product->price, 2) }} СОМ</span>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Product -->
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">В данный момент нет трендовых товаров.</p>
                </div>
            @endforelse
        </div>

        <!-- Show More Button -->
        <div class="row mt-4 text-center">
            <div class="button wow fadeInUp" data-wow-delay=".8s">
                <a href="{{ route('shop.products') }}" class="btn">
                    Показать больше товаров
                    <i class="lni lni-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

    </div>
</section>
<!-- End Trending Product Area -->

<!-- Start Call Action Area -->
<section class="call-action section">
    <div class="container">
        <div class="row ">
            <div class="col-lg-8 offset-lg-2 col-12">
                <div class="inner">
                    <div class="content">
                        <h2 class="wow fadeInUp" data-wow-delay=".4s">Нужна помощь с заказом?<br>
                            Свяжитесь с нашей службой поддержки</h2>
                        <p class="wow fadeInUp" data-wow-delay=".6s">
                            @if($siteSettings['phone'])
                                Позвоните нам по номеру {{ $siteSettings['phone'] }} или
                            @endif
                            @if($siteSettings['email'])
                                напишите нам на {{ $siteSettings['email'] }}
                            @endif
                            <br>Мы здесь, чтобы помочь вам с любыми вопросами!
                        </p>
                        <div class="button wow fadeInUp" data-wow-delay=".8s">
                            <a href="{{ route('shop.products') }}" class="btn">Купить сейчас</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Call Action Area -->

<!-- Start Banner Area -->
{{--<section class="banner section">--}}
{{--    <div class="container">--}}
{{--        <div class="row">--}}
{{--            <div class="col-lg-6 col-md-6 col-12">--}}
{{--                <div class="single-banner" style="background-image:url('{{ asset('build/clients/images/banner/banner-1-bg.jpg') }}')">--}}
{{--                    <div class="content">--}}
{{--                        <h2>Smart Watch 2.0</h2>--}}
{{--                        <p>Space Gray Aluminum Case with <br>Black/Volt Real Sport Band </p>--}}
{{--                        <div class="button">--}}
{{--                            <a href="#" class="btn">View Details</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-lg-6 col-md-6 col-12">--}}
{{--                <div class="single-banner custom-responsive-margin" style="background-image:url('{{ asset('build/clients/images/banner/banner-2-bg.jpg') }}')">--}}
{{--                    <div class="content">--}}
{{--                        <h2>Smart Headphone</h2>--}}
{{--                        <p>Lorem ipsum dolor sit amet, <br>eiusmod tempor--}}
{{--                            incididunt ut labore.</p>--}}
{{--                        <div class="button">--}}
{{--                            <a href="#" class="btn">Shop Now</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}
<!-- End Banner Area -->

<!-- Start Shipping Info -->
<section class="shipping-info">
    <div class="container">
        <ul>
            <!-- Free Shipping -->
            <li>
                <div class="media-icon">
                    <i class="lni lni-delivery"></i>
                </div>
                <div class="media-body">
                    <h5>Бесплатная доставка</h5>
                    <span>При заказе свыше 2000 СОМ</span>
                </div>
            </li>
            <!-- Support -->
            <li>
                <div class="media-icon">
                    <i class="lni lni-support"></i>
                </div>
                <div class="media-body">
                    <h5>Поддержка 24/7</h5>
                    <span>
                        @if($siteSettings['phone'])
                            Звоните {{ $siteSettings['phone'] }}
                        @else
                            Чат или звонок
                        @endif
                    </span>
                </div>
            </li>
            <!-- Support 24/7 -->
            <li>
                <div class="media-icon">
                    <i class="lni lni-credit-cards"></i>
                </div>
                <div class="media-body">
                    <h5>Онлайн оплата</h5>
                    <span>Безопасные платежные сервисы</span>
                </div>
            </li>
            <!-- Safe Payment -->
            <li>
                <div class="media-icon">
                    <i class="lni lni-reload"></i>
                </div>
                <div class="media-body">
                    <h5>Легкий возврат</h5>
                    <span>Покупки без хлопот</span>
                </div>
            </li>
        </ul>
    </div>
</section>

<section>
    <div class="map-section my-5">
        <div class="container">
            <div class="row">
                <div>
                    @include('clients.shop.map')
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Shipping Info -->
@endsection

@section('scripts')
<script>
    //========= Hero Slider
    tns({
        container: '.hero-slider',
        slideBy: 'page',
        autoplay: true,
        autoplayButtonOutput: false,
        mouseDrag: true,
        gutter: 0,
        items: 1,
        nav: false,
        controls: true,
        controlsText: ['<i class="lni lni-chevron-left"></i>', '<i class="lni lni-chevron-right"></i>'],
    });

    //======== Brand Slider
    // tns({
    //     container: '.brands-logo-carousel',
    //     autoplay: true,
    //     autoplayButtonOutput: false,
    //     mouseDrag: true,
    //     gutter: 15,
    //     nav: false,
    //     controls: false,
    //     responsive: {
    //         0: {
    //             items: 1,
    //         },
    //         540: {
    //             items: 3,
    //         },
    //         768: {
    //             items: 5,
    //         },
    //         992: {
    //             items: 6,
    //         }
    //     }
    // });
</script>
@endsection
