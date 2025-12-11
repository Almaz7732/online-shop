<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

















































































































































































































































































































































































































































                <li>
                    <a href="<?php echo e(route('root')); ?>" class="waves-effect">
                        <i class="bx bxs-dashboard"></i>
                        <span key="t-dashboard">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/" class="waves-effect">
                        <i class="bx bxs-dashboard"></i>
                        <span key="t-dashboard">Гланвая страница</span>
                    </a>
                </li>
                <li class="menu-title" key="t-catalog">Управление каталогом</li>
                <li>
                    <a href="<?php echo e(route('products.index')); ?>" class="waves-effect">
                        <i class="bx bx-package"></i>
                        <span key="t-products">Товары</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('categories.index')); ?>" class="waves-effect">
                        <i class="bx bxl-pocket"></i>
                        <span key="t-categories">Категории</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('brands.index')); ?>" class="waves-effect">
                        <i class="bx bx-bookmark"></i>
                        <span key="t-brands">Бренды</span>
                    </a>
                </li>

                <li class="menu-title" key="t-orders">Управление заказами</li>
                <li>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="waves-effect">
                        <i class="bx bx-receipt"></i>
                        <span key="t-orders">Заказы</span>
                    </a>
                </li>

                <li class="menu-title" key="t-content">Управление контентом</li>
                <li>
                    <a href="<?php echo e(route('admin.about.index')); ?>" class="waves-effect">
                        <i class="bx bx-info-circle"></i>
                        <span key="t-about">О нас</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.map-settings.index')); ?>" class="waves-effect">
                        <i class="bx bx-map"></i>
                        <span key="t-map-settings">Настройки карты</span>
                    </a>
                </li>

                <li class="menu-title" key="t-site-settings">Настройки сайта</li>
                <li>
                    <a href="<?php echo e(route('site-settings.index')); ?>" class="waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-site-settings">Настройки сайта</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.seo.index')); ?>" class="waves-effect">
                        <i class="bx bx-search-alt"></i>
                        <span key="t-seo-settings">SEO настройки</span>
                    </a>
                </li>
            </ul>

        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
<?php /**PATH D:\projects\ident-admin\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>