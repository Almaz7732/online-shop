<?php $__env->startSection('title', 'Избранные - Ident'); ?>

<?php $__env->startSection('content'); ?>
<?php
    use Illuminate\Support\Facades\Storage;
?>

<!-- Start Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 col-12">
                <ul class="breadcrumb-nav">
                    <li><a href="<?php echo e(route('shop.index')); ?>"><i class="lni lni-home"></i> Главная</a></li>
                    <li>Избранные</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->

<!-- Start Wishlist Products Area -->
<section class="trending-product section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>Избранные товары</h2>
                </div>
            </div>
        </div>

        <div class="row" id="wishlist-products">
            <?php if($wishlistProducts->count() > 0): ?>
                <?php $__currentLoopData = $wishlistProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-3 col-md-6 col-12">
                        <!-- Start Single Product -->
                        <div class="single-product">
                            <div class="product-image">
                                <?php if($product->primaryImage && $product->primaryImage->image_path): ?>
                                    <img src="<?php echo e(Storage::url($product->primaryImage->image_path)); ?>" alt="<?php echo e($product->name); ?>">
                                <?php else: ?>
                                    <img src="<?php echo e(URL::asset('build/images/default-image.png')); ?>" alt="<?php echo e($product->name); ?>">
                                <?php endif; ?>








                                <div class="button">
                                    <a href="javascript:void(0)" class="btn" data-product-id="<?php echo e($product->id); ?>" title="Добавить в корзину"><i class="lni lni-cart"></i> В корзину</a>
                                    <a href="<?php echo e(route('shop.product-details', $product->slug)); ?>" class="btn mt-2" title="Показать">Показать</a>
                                </div>
                                <div class="wishlist-btn">
                                    <a href="javascript:void(0)" class="wishlist-toggle active" data-wishlist-id="<?php echo e($product->id); ?>" title="Удалить из избранного">
                                        <i class="lni lni-heart-filled"></i>
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
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <!-- Empty Wishlist State -->
                <div class="col-12 text-center" id="empty-wishlist">
                    <div class="empty-wishlist-content" style="padding: 80px 20px;">
                        <div class="empty-icon" style="font-size: 64px; color: #ddd; margin-bottom: 20px;">
                            <i class="lni lni-heart"></i>
                        </div>
                        <h3 style="color: #666; margin-bottom: 15px;">Ваш список избранного пуст</h3>
                        <p style="color: #999; margin-bottom: 30px;">
                            Добавьте товары в избранное, нажав на значок сердечка на товарах
                        </p>
                        <div class="button">
                            <a href="<?php echo e(route('shop.index')); ?>" class="btn">
                                Продолжить покупки
                                <i class="lni lni-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- End Wishlist Products Area -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load wishlist products on page load
    if (window.wishlistManager) {
        loadWishlistProducts();
    }

    // Function to load wishlist products via AJAX
    function loadWishlistProducts() {
        const wishlist = window.wishlistManager.getWishlist();

        if (wishlist.length === 0) {
            showEmptyWishlistState();
            return;
        }

        // If we have wishlist items but page shows empty, reload with IDs
        if (<?php echo e($wishlistProducts->count()); ?> === 0 && wishlist.length > 0) {
            const url = new URL(window.location.href);
            url.searchParams.set('ids', wishlist.join(','));
            window.location.href = url.toString();
        }
    }

    // Show empty wishlist state
    function showEmptyWishlistState() {
        const productsContainer = document.getElementById('wishlist-products');
        const emptyState = `
            <div class="col-12 text-center" id="empty-wishlist">
                <div class="empty-wishlist-content" style="padding: 80px 20px;">
                    <div class="empty-icon" style="font-size: 64px; color: #ddd; margin-bottom: 20px;">
                        <i class="lni lni-heart"></i>
                    </div>
                    <h3 style="color: #666; margin-bottom: 15px;">Ваш список избранного пуст</h3>
                    <p style="color: #999; margin-bottom: 30px;">
                        Добавьте товары в избранное, нажав на значок сердечка на товарах
                    </p>
                    <div class="button">
                        <a href="<?php echo e(route('shop.index')); ?>" class="btn">
                            Продолжить покупки
                            <i class="lni lni-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        `;
        productsContainer.innerHTML = emptyState;
    }

    // Listen for wishlist changes and update page accordingly
    document.addEventListener('click', function(e) {
        const wishlistButton = e.target.closest('[data-wishlist-id]');
        if (wishlistButton) {
            // Small delay to allow wishlist to update
            setTimeout(function() {
                const currentWishlist = window.wishlistManager.getWishlist();
                const currentProductCards = document.querySelectorAll('.single-product');

                // Remove products that are no longer in wishlist
                currentProductCards.forEach(function(card) {
                    const wishlistBtn = card.querySelector('[data-wishlist-id]');
                    if (wishlistBtn) {
                        const productId = parseInt(wishlistBtn.getAttribute('data-wishlist-id'));
                        if (!currentWishlist.includes(productId)) {
                            card.closest('.col-lg-3').remove();
                        }
                    }
                });

                // Show empty state if no products left
                if (currentWishlist.length === 0) {
                    showEmptyWishlistState();
                }
            }, 100);
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clients.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\ident-admin\resources\views/clients/shop/wishlist.blade.php ENDPATH**/ ?>