<?php $__env->startSection('title', 'Ident'); ?>

<?php $__env->startSection('content'); ?>
<?php
    use Illuminate\Support\Facades\Storage;
?>
<!-- Start Hero Area -->
<section class="hero-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-12 custom-padding-right">
                <div class="slider-head">
                    <!-- Start Hero Slider -->
                    <div class="hero-slider">
                        <?php $__empty_1 = true; $__currentLoopData = $carouselSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <!-- Start Single Slider -->
                            <div class="single-slider"
                                 <?php if($slide->image_path): ?>
                                     style="background-image: url(<?php echo e(Storage::url($slide->image_path)); ?>);"
                                 <?php else: ?>
                                     style="background-image: url(<?php echo e(asset('build/images/default-image.png')); ?>);"
                                 <?php endif; ?>
                            >
                                <?php
                                    $textColor = $slide->text_color ? "color: {$slide->text_color}" : ''
                                ?>
                                <div class="content" style="<?php echo e($textColor); ?>">

                                    <h2 style="<?php echo e($textColor); ?>"><?php if($slide->title): ?><span style="<?php echo e($textColor); ?>"><?php echo e($slide->title); ?></span> <?php endif; ?>
                                        <?php if($slide->subtitle): ?> <?php echo e($slide->subtitle); ?> <?php endif; ?>
                                    </h2>
                                    <?php if($slide->description): ?><p><?php echo e($slide->description); ?></p><?php endif; ?>
                                    <?php if($slide->button_text && $slide->button_url): ?>
                                        <div class="button">
                                            <a href="<?php echo e($slide->button_url); ?>" class="btn"><?php echo e($slide->button_text); ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- End Single Slider -->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <!-- Fallback slider when no slides exist -->











                        <?php endif; ?>
                    </div>
                    <!-- End Hero Slider -->
                </div>
            </div>






























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


                </div>
            </div>
        </div>
        <div class="row mb-5">
            <!-- Product Items -->
            <?php $__empty_1 = true; $__currentLoopData = $trendingProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-lg-3 col-md-6 col-6">
                    <!-- Start Single Product -->
                    <div class="single-product">
                        <div class="product-image">
                            <?php if($product->primaryImage && $product->primaryImage->image_path): ?>
                                <img src="<?php echo e(Storage::url($product->primaryImage->image_path)); ?>" alt="<?php echo e($product->name); ?>">
                            <?php else: ?>
                                <img src="<?php echo e(URL::asset('build/images/default-image.png')); ?>" alt="<?php echo e($product->name); ?>">
                            <?php endif; ?>








                            <div class="button">
                                <a href="javascript:void(0)" class="btn add-to-cart" data-product-id="<?php echo e($product->id); ?>" title="Добавить в корзину"><i class="lni lni-cart"></i> В корзину</a>
                                <a href="<?php echo e(route('shop.product-details', $product->slug)); ?>" class="btn mt-2" title="Показать">Показать</a>
                            </div>
                            <div class="wishlist-btn">
                                <a href="javascript:void(0)" class="wishlist-toggle" data-wishlist-id="<?php echo e($product->id); ?>" title="Добавить в избранное">
                                    <i class="lni lni-heart"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-info">
                            <span class="category">
                                <?php echo e($product->category ? $product->category->name : 'Без категории'); ?>

                            </span>
                            <h4 class="title">
                                <a href="<?php echo e(route('shop.product-details', $product->slug)); ?>">
                                    <?php echo e($product->name); ?>

                                </a>
                            </h4>











                            <div class="price">
                                <span><?php echo e(number_format($product->price, 2)); ?> СОМ</span>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Product -->
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">В данный момент нет трендовых товаров.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Show More Button -->
        <div class="row mt-4 text-center">
            <div class="button wow fadeInUp" data-wow-delay=".8s">
                <a href="<?php echo e(route('shop.products')); ?>" class="btn">
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
                            <?php if($siteSettings['phone']): ?>
                                Позвоните нам по номеру <?php echo e($siteSettings['phone']); ?> или
                            <?php endif; ?>
                            <?php if($siteSettings['email']): ?>
                                напишите нам на <?php echo e($siteSettings['email']); ?>

                            <?php endif; ?>
                            <br>Мы здесь, чтобы помочь вам с любыми вопросами!
                        </p>
                        <div class="button wow fadeInUp" data-wow-delay=".8s">
                            <a href="<?php echo e(route('shop.products')); ?>" class="btn">Купить сейчас</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Call Action Area -->

<!-- Start Banner Area -->





























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
                        <?php if($siteSettings['phone']): ?>
                            Звоните <?php echo e($siteSettings['phone']); ?>

                        <?php else: ?>
                            Чат или звонок
                        <?php endif; ?>
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
                    <script charset="utf-8" src="https://widgets.2gis.com/js/DGWidgetLoader.js"></script>
                    <script charset="utf-8">
                        new DGWidgetLoader({
                            "width":'100%',
                            "borderColor":"#a3a3a3",
                            "pos": {
                                "lat":42.84447503993618,
                                "lon":74.59834814071657,
                                "zoom":16
                            },
                            "opt":{
                                "city":"bishkek"
                            },
                            "org":[
                                {"id":"70000001067873205"}
                            ]
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Shipping Info -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clients.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\ident-admin\resources\views/clients/shop/index.blade.php ENDPATH**/ ?>