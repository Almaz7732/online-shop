<header class="header navbar-area">
    <!-- Start Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-4 col-12">
                    <div class="top-left">
                        <ul class="menu-top-link">

                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-12">
                    <div class="top-middle">
                        <ul class="useful-links">
                            <li><a href="<?php echo e(route('shop.index')); ?>">Главная</a></li>
                            <li><a href="<?php echo e(route('shop.about')); ?>">О нас</a></li>
                        </ul>
                    </div>
                </div>
















            </div>
        </div>
    </div>
    <!-- End Topbar -->

    <!-- Start Header Middle -->
    <div class="header-middle">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3 col-md-3 col-7">
                    <!-- Start Header Logo -->
                    <a class="navbar-brand" href="<?php echo e(route('shop.index')); ?>">

                        <img src="<?php echo e(asset('build/images/site-logo.png')); ?>" alt="Logo">
                    </a>
                    <!-- End Header Logo -->
                </div>
                <div class="col-lg-5 col-md-7 d-xs-none">
                    <!-- Start Main Menu Search -->
                    <div class="main-menu-search">
                        <!-- navbar search start -->
                        <form action="<?php echo e(route('shop.products')); ?>" method="GET" class="navbar-search search-style-5">
                            <div class="search-input">
                                <input type="text" name="search" id="search-input" placeholder="Поиск товаров..." value="<?php echo e(request('search')); ?>" autocomplete="off">
                                <!-- Search Results Dropdown -->
                                <div class="search-results" id="search-results" style="display: none;">
                                    <div class="search-loading" id="search-loading" style="display: none;">
                                        <div class="text-center p-3">
                                            <i class="bx bx-loader-alt bx-spin"></i> Поиск...
                                        </div>
                                    </div>
                                    <div class="search-items" id="search-items"></div>
                                    <div class="search-footer" id="search-footer" style="display: none;">
                                        <a href="#" class="view-all-results">Показать все результаты</a>
                                    </div>
                                </div>
                            </div>
                            <div class="search-btn">
                                <button type="submit"><i class="lni lni-search-alt"></i></button>
                            </div>
                        </form>
                        <!-- navbar search Ends -->
                    </div>
                    <!-- End Main Menu Search -->
                </div>
                <div class="col-lg-4 col-md-2 col-5">
                    <div class="middle-right-area">
                        <div class="nav-hotline">
                            <i class="lni lni-phone"></i>
                            <h3>Горячая линия:
                                <span><?php echo e(setting('site_phone', '(+100) 123 456 7890')); ?></span>
                            </h3>
                        </div>
                        <div class="navbar-cart">
                            <div class="wishlist">
                                <a href="<?php echo e(route('shop.wishlist')); ?>" id="wishlist-link">
                                    <i class="lni lni-heart"></i>
                                    <span class="total-items">0</span>
                                </a>
                            </div>
                            <div class="cart-items">
                                <a href="javascript:void(0)" class="main-btn">
                                    <i class="lni lni-cart"></i>
                                    <span class="total-items">0</span>
                                </a>
                                <!-- Shopping Item -->
                                <div class="shopping-item">
                                    <div class="dropdown-cart-header">
                                        <span>0 товаров</span>
                                        <a href="<?php echo e(route('shop.cart')); ?>">Корзина</a>
                                    </div>
                                    <ul class="shopping-list">
                                        <li style="text-align: center; padding: 20px; color: #999;">Корзина пуста</li>
                                    </ul>
                                    <div class="bottom">
                                        <div class="total">
                                            <span>Итого</span>
                                            <span class="total-amount">0.00 СОМ</span>
                                        </div>
                                        <div class="button">
                                            <a href="<?php echo e(route('shop.cart')); ?>" class="btn animate">Оформить заказ</a>
                                        </div>
                                    </div>
                                </div>
                                <!--/ End Shopping Item -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Mobile Search (shown only when main search is hidden) -->
                <div class="col-12 d-md-none mt-2">
                    <div class="mobile-search">
                        <form action="<?php echo e(route('shop.products')); ?>" method="GET" class="navbar-search search-style-5">
                            <div class="search-input">
                                <input type="text" name="search" id="mobile-search-input" placeholder="Поиск товаров..." value="<?php echo e(request('search')); ?>" autocomplete="off">
                                <!-- Mobile Search Results Dropdown -->
                                <div class="search-results" id="mobile-search-results" style="display: none;">
                                    <div class="search-loading" id="mobile-search-loading" style="display: none;">
                                        <div class="text-center p-3">
                                            <i class="bx bx-loader-alt bx-spin"></i> Поиск...
                                        </div>
                                    </div>
                                    <div class="search-items" id="mobile-search-items"></div>
                                    <div class="search-footer" id="mobile-search-footer" style="display: none;">
                                        <a href="#" class="view-all-results">Показать все результаты</a>
                                    </div>
                                </div>
                            </div>
                            <div class="search-btn">
                                <button type="submit"><i class="lni lni-search-alt"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Header Middle -->
</header>

<!-- Start Sticky Header Bottom -->
<div class="header header-bottom-sticky" id="header-category">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-6 col-12">
                <div class="nav-inner">
                    <!-- Start Mega Category Menu -->
                    <div class="mega-category-menu">
                        <span class="cat-button"><i class="lni lni-menu"></i>Все категории</span>
                        <?php
                            $headerCategories = \App\Models\Category::where('parent_id', null)
                                ->with('children')
                                ->orderBy('name')
                                ->limit(10)
                                ->get();
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($headerCategories->count() > 0): ?>
                            <ul class="sub-category">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $headerCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <a href="<?php echo e(route('shop.products.category', $category->slug)); ?>"><?php echo e($category->name); ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->children->count() > 0): ?>
                                                <i class="lni lni-chevron-right"></i>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </a>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->children->count() > 0): ?>
                                            <ul class="inner-sub-category">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><a href="<?php echo e(route('shop.products.category', $child->slug)); ?>"><?php echo e($child->name); ?></a></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </ul>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </ul>
                        <?php else: ?>
                            <ul class="sub-category">
                                <li><a href="#">Категории недоступны</a></li>
                            </ul>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <!-- End Mega Category Menu -->

                    <!-- Start Navbar -->
                    <nav class="navbar navbar-expand-lg">
                        <button class="navbar-toggler mobile-menu-btn" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="toggler-icon"></span>
                            <span class="toggler-icon"></span>
                            <span class="toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                            <ul id="nav" class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <a href="<?php echo e(route('shop.index')); ?>" class="<?php echo e(request()->routeIs('shop.index') ? 'active' : ''); ?>" aria-label="Toggle navigation">Главная</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('shop.products')); ?>" class="<?php echo e(request()->routeIs('shop.products*') ? 'active' : ''); ?>" aria-label="Toggle navigation">Товары</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('shop.about')); ?>" class="<?php echo e(request()->routeIs('shop.about*') ? 'active' : ''); ?>" aria-label="Toggle navigation">О нас</a>
                                </li>
                            </ul>
                        </div> <!-- navbar collapse -->
                    </nav>
                    <!-- End Navbar -->
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <!-- Start Nav Social -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_facebook') || setting('site_twitter') || setting('site_instagram') || setting('site_youtube')): ?>
                    <div class="nav-social">
                        <h5 class="title">Подписывайтесь:</h5>
                        <ul>
                            <?php if(setting('site_facebook')): ?>
                                <li>
                                    <a href="<?php echo e(setting('site_facebook')); ?>" target="_blank"><i class="lni lni-facebook-filled"></i></a>
                                </li>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_twitter')): ?>
                                <li>
                                    <a href="<?php echo e(setting('site_twitter')); ?>" target="_blank"><i class="lni lni-twitter-original"></i></a>
                                </li>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_instagram')): ?>
                                <li>
                                    <a href="<?php echo e(setting('site_instagram')); ?>" target="_blank"><i class="lni lni-instagram"></i></a>
                                </li>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_youtube')): ?>
                                <li>
                                    <a href="<?php echo e(setting('site_youtube')); ?>" target="_blank"><i class="lni lni-youtube"></i></a>
                                </li>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <!-- End Nav Social -->
            </div>
        </div>
    </div>
</div>
<!-- End Sticky Header Bottom -->
<?php /**PATH /var/www/html/resources/views/clients/partials/header.blade.php ENDPATH**/ ?>