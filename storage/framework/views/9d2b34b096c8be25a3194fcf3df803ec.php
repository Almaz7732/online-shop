<?php $__env->startPush('styles'); ?>
    <link href="<?php echo e(URL::asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopPush(); ?>

<?php $__env->startSection('title', "{$product->name} - Ident."); ?>

<?php $__env->startSection('content'); ?>
<?php
    use Illuminate\Support\Facades\Storage;
?>
<!-- Start Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="breadcrumbs-content">
                    <h1 class="page-title"><?php echo e($product->name); ?></h1>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12">
                <ul class="breadcrumb-nav">
                    <li><a href="<?php echo e(route('shop.index')); ?>"><i class="lni lni-home"></i> Главная</a></li>
                    <li><a href="<?php echo e(route('shop.products')); ?>">Магазин</a></li>
                    <?php if($product->category): ?>
                        <li><a href="<?php echo e(route('shop.products.category', $product->category->slug)); ?>"><?php echo e($product->category->name); ?></a></li>
                    <?php endif; ?>
                    <li><?php echo e($product->name); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->

<!-- Start Item Details -->
<section class="item-details section">
    <div class="container">
        <div class="top-area">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="product-images">
                        <main id="gallery">
                            <div class="main-img position-relative">
                                <?php if($product->images->count() > 0): ?>
                                    <img src="<?php echo e(Storage::url($product->images->first()->image_path)); ?>" id="current" alt="<?php echo e($product->name); ?>">
                                <?php else: ?>
                                    <img src="<?php echo e(URL::asset('build/images/default-image.png')); ?>" id="current" alt="<?php echo e($product->name); ?>">
                                <?php endif; ?>
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <button type="button" class="btn btn-sm btn-light" onclick="viewImage()">
                                            <i class="bx bx-expand"></i>
                                        </button>
                                    </div>
                            </div>
                            <div class="images">
                                <?php if($product->images->count() > 0): ?>
                                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img src="<?php echo e(Storage::url($image->image_path)); ?>" class="img" alt="<?php echo e($product->name); ?> - Image <?php echo e($loop->iteration); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                        </main>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="product-info">
                        <h2 class="title"><?php echo e($product->name); ?></h2>
                        <p class="category"><i class="lni lni-tag"></i>
                            <?php if($product->category): ?>
                                <?php echo e($product->category->name); ?>

                                <?php if($product->category->parent): ?>
                                    <a href="javascript:void(0)"><?php echo e($product->category->parent->name); ?></a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if($product->brand): ?>
                                <span class="ms-3"><i class="lni lni-bookmark"></i> <?php echo e($product->brand->name); ?></span>
                            <?php endif; ?>
                        </p>
                        <h3 class="price"><?php echo e(number_format($product->price, 2)); ?> СОМ</h3>
                        <p class="info-text"><?php echo e($product->description ?: 'Описание товара скоро появится.'); ?></p>
                        <div class="bottom-content">
                            <div class="row align-items-end">
                                <div class="col-lg-4 col-md-4 col-12">
                                    <div class="button cart-button">
                                        <button class="btn add-to-cart" data-product-id="<?php echo e($product->id); ?>" style="width: 100%;">Добавить в корзину</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-12">
                                    <div class="wishlist-btn" style="position: static">
                                        <a href="javascript:void(0)" class="wishlist-toggle" data-wishlist-id="<?php echo e($product->id); ?>" title="Добавить в избранное">
                                            <i class="lni lni-heart"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-details-info">
            <div class="single-block">
                <div class="row">
                    <?php echo $product->description; ?>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Item Details -->

<!-- Review Modal -->
<div class="modal fade review-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Оставить отзыв</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="review-name">Ваше имя</label>
                            <input class="form-control" type="text" id="review-name" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="review-email">Ваш Email</label>
                            <input class="form-control" type="email" id="review-email" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="review-subject">Тема</label>
                            <input class="form-control" type="text" id="review-subject" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="review-rating">Рейтинг</label>
                            <select class="form-control" id="review-rating">
                                <option>5 звезд</option>
                                <option>4 звезды</option>
                                <option>3 звезды</option>
                                <option>2 звезды</option>
                                <option>1 звезда</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="review-message">Отзыв</label>
                    <textarea class="form-control" id="review-message" rows="8" required></textarea>
                </div>
            </div>
            <div class="modal-footer button">
                <button type="button" class="btn" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn">Отправить отзыв</button>
            </div>
        </div>
    </div>
</div>
<!-- End Review Modal -->

<!-- Image View Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Product Image">
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function viewImage() {
        document.getElementById('modalImage').src = fullImg.src;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }

    // Product Image Gallery
    const imgs = document.querySelectorAll('.img');
    const fullImg = document.querySelector('#current');

    imgs.forEach((img) => {
        img.addEventListener('click', (e) => {
            fullImg.src = e.target.src;
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clients.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\ident-admin\resources\views/clients/shop/product-details.blade.php ENDPATH**/ ?>