<!DOCTYPE html>
<html class="no-js" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="google-site-verification" content="t_d6_PSkG5EqkyXiVqUwp5TRBNOgLIOrLbgyNUlw66s" />
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($seoData)): ?>
        <?php echo \App\Helpers\SeoHelper::renderMetaTags($seoData); ?>

    <?php else: ?>
        <title><?php echo $__env->yieldContent('title', 'Ident - Стоматологическое оборудование и материалы'); ?></title>
        <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'Поставка стоматологического оборудования и материалов в Кыргызстан'); ?>" />
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('build/images/site-logo-x-icon.jpg')); ?>" />

    <!-- ========================= CSS here ========================= -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/clients/css/app.css']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <link href="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />

    <!-- Mobile Menu Styles -->
    <style>
        /* Mobile menu button active state */
        .mobile-menu-btn.active .toggler-icon:nth-child(1) {
            transform: rotate(90deg) translate(5px, 5px);
        }
        .mobile-menu-btn.active .toggler-icon:nth-child(2) {
            opacity: 0;
        }
        .mobile-menu-btn.active .toggler-icon:nth-child(3) {
            transform: rotate(90deg) translate(7px, -6px);
        }

        /* Smooth transitions for burger animation */
        .mobile-menu-btn .toggler-icon {
            transition: all 0.3s ease;
        }

        /* Mobile menu backdrop */
        .navbar-collapse.show {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Ensure menu is visible on mobile */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                z-index: 1000;
                border-radius: 0 0 10px 10px;
                padding: 20px;
            }

            .navbar-nav {
                width: 100%;
            }

            .navbar-nav .nav-item {
                margin: 5px 0;
            }

            .navbar-nav .nav-item a {
                padding: 12px 15px;
                display: block;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .navbar-nav .nav-item a:hover {
                background-color: #f8f9fa;
            }
        }

        /* Search Dropdown Styles */
        .main-menu-search {
            position: relative;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
        }

        .search-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            text-decoration: none;
            color: #333;
            transition: background-color 0.2s ease;
        }

        .search-item:hover {
            background-color: #f8f9fa;
            text-decoration: none;
            color: #333;
        }

        .search-item:last-child {
            border-bottom: none;
        }

        .search-item-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 12px;
        }

        .search-item-info {
            flex: 1;
        }

        .search-item-name {
            font-weight: 500;
            margin-bottom: 2px;
            font-size: 14px;
        }

        .search-item-category {
            font-size: 12px;
            color: #666;
            margin-bottom: 2px;
        }

        .search-item-price {
            font-weight: 600;
            color: #e74c3c;
            font-size: 14px;
        }

        .search-footer {
            padding: 12px 15px;
            background-color: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }

        .view-all-results {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .view-all-results:hover {
            text-decoration: underline;
        }

        .search-no-results {
            padding: 20px;
            text-align: center;
            color: #666;
        }

        /* Loading animation */
        .bx-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Mobile Search Styles */
        .mobile-search {
            position: relative;
        }

        .mobile-search .search-input {
            position: relative;
            width: 100%;
        }

        .mobile-search .search-input input {
            width: 100%;
            padding: 12px 50px 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            background: #fff;
        }

        .mobile-search .search-btn {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            /*padding: 0 15px;*/
            /*border: none;*/
            /*background: #0167f3;*/
            /*border-radius: 0 5px 5px 0;*/
            /*color: white;*/
            /*cursor: pointer;*/
        }

        .mobile-search .search-btn:hover {
            background: #081828;
        }

        /* Mobile search results styling */
        .mobile-search .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
        }

        /* Header Styles */
        .header {
            position: relative;
            width: 100%;
            background: #fff;
            z-index: 998;
        }

        /* Header middle */
        .header .header-middle {
            position: relative;
            width: 100%;
            padding: 20px 0;
            transition: padding 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Topbar styles */
        .header .topbar {
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }

        /* Sticky Header Bottom (Categories + Navigation) */
        .header-bottom-sticky {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            background: #fff;
            border-top: 1px solid #eee;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Add shadow when sticky is active */
        .header-bottom-sticky.is-stuck {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            background-color: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
        }

        /* Smooth padding transition */
        .header-bottom-sticky .container {
            /*padding-top: 15px;*/
            /*padding-bottom: 15px;*/
            transition: padding 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .header-bottom-sticky.is-stuck .container {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        /* Body padding */
        body {
            padding-top: 0;
        }

        /* Burger Menu Animation */
        .mobile-menu-btn {
            position: relative;
            width: 30px;
            height: 30px;
            border: none;
            background: transparent;
            cursor: pointer;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .mobile-menu-btn .toggler-icon {
            display: block;
            width: 22px;
            height: 2px;
            background-color: #333;
            margin: 3px 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }

        /* Active state - transform to X */
        .mobile-menu-btn.active .toggler-icon:nth-child(1) {
            transform: rotate(90deg) translate(7px, 5px);
            background-color: #333;
        }

        .mobile-menu-btn.active .toggler-icon:nth-child(2) {
            opacity: 0;
            transform: scale(0);
        }

        .mobile-menu-btn.active .toggler-icon:nth-child(3) {
            transform: rotate(90deg) translate(5px, -5px);
            background-color: #333;
        }

        /* Mobile menu backdrop */
        .navbar-collapse.show {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 0 0 10px 10px;
        }

        /* Ensure menu is visible on mobile */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                z-index: 1000;
                padding: 20px;
            }

            .navbar-nav {
                width: 100%;
            }

            .navbar-nav .nav-item {
                margin: 5px 0;
            }

            .navbar-nav .nav-item a {
                padding: 12px 15px;
                display: block;
                border-radius: 5px;
                transition: background-color 0.3s ease;
                color: #333;
                text-decoration: none;
            }

            .navbar-nav .nav-item a:hover,
            .navbar-nav .nav-item a.active {
                background-color: #f8f9fa;
                color: #0167f3;
            }
        }

    </style>
</head>

<body>
    <!--[if lte IE 9]>
      <p class="browserupgrade">
        You are using an <strong>outdated</strong> browser. Please
        <a href="https://browsehappy.com/">upgrade your browser</a> to improve
        your experience and security.
      </p>
    <![endif]-->

    <!-- Preloader -->
    <div class="preloader" style="opacity: 0; display: none;">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- /End Preloader -->

    <!-- Start Header Area -->
    <?php echo $__env->make('clients.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- End Header Area -->

    <!-- Main Content -->
    <?php echo $__env->yieldContent('content'); ?>

    <!-- Start Footer Area -->
    <?php echo $__env->make('clients.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!--/ End Footer Area -->

    <!-- ========================= scroll-top ========================= -->
    <a href="#" class="scroll-top">
        <i class="lni lni-chevron-up"></i>
    </a>

    <!-- ========================= JS here ========================= -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/clients/js/app.js']); ?>

    <!-- ShopGrids JS Files -->
    <script src="<?php echo e(asset('build/clients/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/clients/js/tiny-slider.js')); ?>"></script>
    <script src="<?php echo e(asset('build/clients/js/glightbox.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/clients/js/main.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>

    <!-- Enhanced Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navbarCollapse = document.querySelector('#navbarSupportedContent');

            // if (mobileMenuBtn && navbarCollapse) {
            //     // Initialize button state based on collapse state
            //     function syncButtonState() {
            //         const isOpen = navbarCollapse.classList.contains('show');
            //         if (isOpen) {
            //             mobileMenuBtn.classList.add('active');
            //             mobileMenuBtn.setAttribute('aria-expanded', 'true');
            //         } else {
            //             mobileMenuBtn.classList.remove('active');
            //             mobileMenuBtn.setAttribute('aria-expanded', 'false');
            //         }
            //     }
            //
            //     // Bootstrap collapse events (if available) - Primary method
            //     if (window.bootstrap) {
            //         navbarCollapse.addEventListener('show.bs.collapse', function() {
            //             mobileMenuBtn.classList.add('active');
            //             mobileMenuBtn.setAttribute('aria-expanded', 'true');
            //             document.body.style.overflow = 'hidden';
            //         });
            //
            //         navbarCollapse.addEventListener('hide.bs.collapse', function() {
            //             mobileMenuBtn.classList.remove('active');
            //             mobileMenuBtn.setAttribute('aria-expanded', 'false');
            //             document.body.style.overflow = '';
            //         });
            //
            //         // Sync initial state
            //         syncButtonState();
            //     } else {
            //         // Fallback for when Bootstrap is not available
            //         mobileMenuBtn.addEventListener('click', function(e) {
            //             e.preventDefault();
            //
            //             // Toggle the collapse class
            //             const isOpen = navbarCollapse.classList.contains('show');
            //
            //             if (isOpen) {
            //                 closeMenu();
            //             } else {
            //                 openMenu();
            //             }
            //         });
            //     }
            //
            //     // Functions to open/close menu (only for fallback)
            //     function openMenu() {
            //         navbarCollapse.classList.add('show');
            //         mobileMenuBtn.classList.add('active');
            //         mobileMenuBtn.setAttribute('aria-expanded', 'true');
            //         document.body.style.overflow = 'hidden';
            //     }
            //
            //     function closeMenu() {
            //         navbarCollapse.classList.remove('show');
            //         mobileMenuBtn.classList.remove('active');
            //         mobileMenuBtn.setAttribute('aria-expanded', 'false');
            //         document.body.style.overflow = '';
            //     }
            //
            //     // Enhanced close functionality
            //     function forceCloseMenu() {
            //         if (window.bootstrap && window.bootstrap.Collapse) {
            //             const collapse = window.bootstrap.Collapse.getInstance(navbarCollapse);
            //             if (collapse) {
            //                 collapse.hide();
            //             } else {
            //                 closeMenu();
            //                 syncButtonState();
            //             }
            //         } else {
            //             closeMenu();
            //             syncButtonState();
            //         }
            //     }
            //
            //     // Close menu when clicking outside
            //     document.addEventListener('click', function(event) {
            //         if (!mobileMenuBtn.contains(event.target) &&
            //             !navbarCollapse.contains(event.target) &&
            //             navbarCollapse.classList.contains('show')) {
            //             forceCloseMenu();
            //         }
            //     });
            //
            //     // Close menu when clicking on menu links
            //     const menuLinks = navbarCollapse.querySelectorAll('a');
            //     menuLinks.forEach(link => {
            //         link.addEventListener('click', function() {
            //             forceCloseMenu();
            //         });
            //     });
            //
            //     // Close menu on ESC key
            //     document.addEventListener('keydown', function(event) {
            //         if (event.key === 'Escape' && navbarCollapse.classList.contains('show')) {
            //             forceCloseMenu();
            //         }
            //     });
            //
            //     // Handle window resize
            //     window.addEventListener('resize', function() {
            //         if (window.innerWidth > 991 && navbarCollapse.classList.contains('show')) {
            //             forceCloseMenu();
            //         }
            //     });
            //
            //     // Observer for state changes (backup sync)
            //     const observer = new MutationObserver(function(mutations) {
            //         mutations.forEach(function(mutation) {
            //             if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
            //                 setTimeout(syncButtonState, 50);
            //             }
            //         });
            //     });
            //
            //     observer.observe(navbarCollapse, {
            //         attributes: true,
            //         attributeFilter: ['class']
            //     });
            // }
        });
    </script>

    <!-- Search Functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Desktop search elements
            const searchInput = document.getElementById('search-input');
            const searchResults = document.getElementById('search-results');
            const searchLoading = document.getElementById('search-loading');
            const searchItems = document.getElementById('search-items');
            const searchFooter = document.getElementById('search-footer');

            // Mobile search elements
            const mobileSearchInput = document.getElementById('mobile-search-input');
            const mobileSearchResults = document.getElementById('mobile-search-results');
            const mobileSearchLoading = document.getElementById('mobile-search-loading');
            const mobileSearchItems = document.getElementById('mobile-search-items');
            const mobileSearchFooter = document.getElementById('mobile-search-footer');

            // Initialize desktop search if exists
            if (searchInput) {
                initializeSearch(searchInput, searchResults, searchLoading, searchItems, searchFooter);
            }

            // Initialize mobile search if exists
            if (mobileSearchInput) {
                initializeSearch(mobileSearchInput, mobileSearchResults, mobileSearchLoading, mobileSearchItems, mobileSearchFooter);
            }

            function initializeSearch(input, results, loading, items, footer) {
                let searchTimeout;
                let isSearchFocused = false;

                // Show search results when focusing
                input.addEventListener('focus', function() {
                    isSearchFocused = true;
                    if (this.value.trim().length >= 2) {
                        results.style.display = 'block';
                    }
                });

                // Hide search results when clicking outside
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.main-menu-search') && !event.target.closest('.mobile-search')) {
                        results.style.display = 'none';
                        isSearchFocused = false;
                    }
                });

                // Handle search input
                input.addEventListener('input', function() {
                    const query = this.value.trim();

                    // Clear previous timeout
                    clearTimeout(searchTimeout);

                    if (query.length < 2) {
                        results.style.display = 'none';
                        return;
                    }

                    // Show loading state
                    loading.style.display = 'block';
                    items.innerHTML = '';
                    footer.style.display = 'none';
                    results.style.display = 'block';

                    // Delay search to avoid too many requests
                    searchTimeout = setTimeout(function() {
                        performSearch(query, loading, items, footer);
                    }, 300);
                });

                // Handle Enter key
                input.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        const query = this.value.trim();
                        if (query.length >= 2) {
                            window.location.href = `<?php echo e(route('shop.products')); ?>?search=${encodeURIComponent(query)}`;
                        }
                    }
                });
            }

            // Perform search function
            function performSearch(query, loading, items, footer) {
                fetch(`<?php echo e(route('shop.search-suggestions')); ?>?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        loading.style.display = 'none';

                        if (data.products && data.products.length > 0) {
                            renderSearchResults(data.products, items);
                            // Update footer link
                            const footerLink = footer.querySelector('.view-all-results');
                            if (footerLink) {
                                footerLink.href = data.search_url;
                                footerLink.textContent = `Показать все результаты (${data.products.length > 7 ? 'более ' + data.products.length : data.products.length})`;
                            }
                            footer.style.display = 'block';
                        } else {
                            items.innerHTML = '<div class="search-no-results">Товары не найдены</div>';
                            footer.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        loading.style.display = 'none';
                        items.innerHTML = '<div class="search-no-results">Ошибка поиска</div>';
                        footer.style.display = 'none';
                    });
            }

            // Render search results function
            function renderSearchResults(products, items) {
                items.innerHTML = '';

                products.forEach(product => {
                    const item = document.createElement('a');
                    item.href = product.url;
                    item.className = 'search-item';

                    item.innerHTML = `
                        <img src="${product.image}" alt="${product.name}" class="search-item-image">
                        <div class="search-item-info">
                            <div class="search-item-name">${product.name}</div>
                            <div class="search-item-category">${product.category}${product.brand ? ' • ' + product.brand : ''}</div>
                            <div class="search-item-price">${product.formatted_price}</div>
                        </div>
                    `;

                    items.appendChild(item);
                });
            }
        });
    </script>

    <!-- Sticky Header Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stickyHeader = document.querySelector('.header-bottom-sticky');

            if (!stickyHeader) return;

            let ticking = false;
            let isStuck = false;

            // Get the sticky header position
            const stickyOffset = stickyHeader.offsetTop;

            function checkSticky() {
                const currentScrollY = window.scrollY;

                // Check if header is stuck (sticky position activated)
                // We check if the element has moved from its original position
                const headerRect = stickyHeader.getBoundingClientRect();
                const shouldBeStuck = headerRect.top <= 0 && currentScrollY > 50;

                if (shouldBeStuck && !isStuck) {
                    stickyHeader.classList.add('is-stuck');
                    isStuck = true;
                } else if (!shouldBeStuck && isStuck) {
                    stickyHeader.classList.remove('is-stuck');
                    isStuck = false;
                }

                ticking = false;
            }

            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(checkSticky);
                    ticking = true;
                }
            }

            // Listen to scroll events
            window.addEventListener('scroll', requestTick, { passive: true });

            // Check initial state
            checkSticky();
        });
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html>
<?php /**PATH D:\projects\shop-laravel\resources\views/clients/layouts/app.blade.php ENDPATH**/ ?>