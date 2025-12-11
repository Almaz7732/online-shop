<?php $__env->startSection('title', $selectedCategory ? $selectedCategory->name . ' - Ident' : 'Продукты - Ident'); ?>

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
                    <li><a href="<?php echo e(route('shop.products')); ?>">Товары</a></li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCategory): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCategory->parent): ?>
                            <li><a href="<?php echo e(route('shop.products.category', $selectedCategory->parent->slug)); ?>"><?php echo e($selectedCategory->parent->name); ?></a></li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <li><?php echo e($selectedCategory->name); ?></li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->

<!-- Start Products Area -->
<section class="product-grids section">
    <div class="container">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search')): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="search-info alert alert-light">
                        <h5>Результаты поиска для: "<?php echo e(request('search')); ?>"</h5>
                        <p class="mb-0">Найдено товаров: <?php echo e($products->total()); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($products->total() == 0): ?>
                            <a href="<?php echo e(route('shop.products')); ?>" class="btn btn-primary btn-sm mt-2">Показать все товары</a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="row">
            <!-- Start Sidebar -->
            <div class="col-lg-3 col-12">
                <div class="product-sidebar">
                    <!-- Start Categories -->
                    <div class="single-widget">
                        <h3>Категории</h3>
                        <div class="categories-widget">
                            <ul class="category-list">
                                <li class="<?php echo e(!$selectedCategory ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('shop.products')); ?>">
                                        Все товары
                                        <span class="count">(<?php echo e($allProductsCount); ?>)</span>
                                    </a>
                                </li>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $isCategoryActive = $selectedCategory && (
                                            $selectedCategory->id == $category->id ||
                                            ($selectedCategory->parent && $selectedCategory->parent->id == $category->id) ||
                                            ($selectedCategory->parent && $selectedCategory->parent->parent && $selectedCategory->parent->parent->id == $category->id)
                                        );
                                    ?>
                                    <li class="has-children <?php echo e($isCategoryActive ? 'active' : ''); ?>" data-category-id="<?php echo e($category->id); ?>">
                                        <div class="category-item">
                                            <a href="<?php echo e(route('shop.products.category', $category->slug)); ?>" class="category-link">
                                                <?php echo e($category->name); ?>

                                                <span class="count">(<?php echo e($category->total_products_count); ?>)</span>
                                            </a>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->children->count() > 0): ?>
                                                <span class="category-toggle" data-category="<?php echo e($category->id); ?>">
                                                    <i class="lni lni-plus"></i>
                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->children->count() > 0): ?>
                                            <ul class="sub-categories">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $isChildActive = $selectedCategory && (
                                                            $selectedCategory->id == $child->id ||
                                                            ($selectedCategory->parent && $selectedCategory->parent->id == $child->id)
                                                        );
                                                    ?>
                                                    <li class="has-children <?php echo e($isChildActive ? 'active' : ''); ?>">
                                                        <div class="category-item">
                                                            <a class="category-link" href="<?php echo e(route('shop.products.category', $child->slug)); ?>">
                                                                <?php echo e($child->name); ?>

                                                                <span class="count">(<?php echo e($child->total_products_count); ?>)</span>
                                                            </a>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($child->children->count() > 0): ?>
                                                                <span class="category-toggle" data-category="<?php echo e($child->id); ?>">
                                                                    <i class="lni lni-plus"></i>
                                                                </span>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($child->children->count() > 0): ?>
                                                            <ul class="sub-categories level-3">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $child->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grandchild): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <li class="<?php echo e(($selectedCategory && $selectedCategory->id == $grandchild->id) ? 'active' : ''); ?>">
                                                                        <a class="category-link" href="<?php echo e(route('shop.products.category', $grandchild->slug)); ?>">
                                                                            <?php echo e($grandchild->name); ?>

                                                                            <span class="count">(<?php echo e($grandchild->total_products_count); ?>)</span>
                                                                        </a>
                                                                    </li>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </ul>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </ul>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <!-- End Categories -->
                </div>
            </div>
            <!-- End Sidebar -->

            <!-- Start Main Content -->
            <div class="col-lg-9 col-12">
                <div class="product-grids-head">
                    <div class="product-grid-topbar">
                        <div class="row align-items-center">
                            <div class="col-lg-7 col-md-8 col-12">
                                <div class="product-sorting">
                                    <h3 class="tab-title">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCategory): ?>
                                            <?php echo e($selectedCategory->name); ?>

                                        <?php else: ?>
                                            Все товары
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </h3>
                                    <p class="product-show">Показано <?php echo e($products->firstItem() ?? 0); ?>–<?php echo e($products->lastItem() ?? 0); ?> из <?php echo e($products->total()); ?> результатов</p>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-4 col-12">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link <?php echo e($view == 'grid' ? 'active' : ''); ?>" id="nav-grid-tab" data-bs-toggle="tab" data-bs-target="#nav-grid" type="button" role="tab" aria-controls="nav-grid" aria-selected="<?php echo e($view == 'grid' ? 'true' : 'false'); ?>" data-view="grid">
                                            <i class="lni lni-grid-alt"></i>
                                        </button>
                                        <button class="nav-link <?php echo e($view == 'list' ? 'active' : ''); ?>" id="nav-list-tab" data-bs-toggle="tab" data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-list" aria-selected="<?php echo e($view == 'list' ? 'true' : 'false'); ?>" data-view="list">
                                            <i class="lni lni-list"></i>
                                        </button>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="product-sorting-menu">
                                <div class="sorting-list">
                                    <select class="form-select" id="sort-select">
                                        <option value="latest" <?php echo e($sort == 'latest' ? 'selected' : ''); ?>>Сортировка по новизне</option>
                                        <option value="price_low" <?php echo e($sort == 'price_low' ? 'selected' : ''); ?>>Цена: по возрастанию</option>
                                        <option value="price_high" <?php echo e($sort == 'price_high' ? 'selected' : ''); ?>>Цена: по убыванию</option>
                                        <option value="name_az" <?php echo e($sort == 'name_az' ? 'selected' : ''); ?>>Название: А-Я</option>
                                        <option value="name_za" <?php echo e($sort == 'name_za' ? 'selected' : ''); ?>>Название: Я-А</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="nav-tabContent">
                        <!-- Grid View -->
                        <div class="tab-pane fade <?php echo e($view == 'grid' ? 'show active' : ''); ?>" id="nav-grid" role="tabpanel" aria-labelledby="nav-grid-tab">
                            <div class="row">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="col-lg-4 col-md-6 col-6">
                                        <!-- Start Single Product -->
                                        <div class="single-product">
                                            <div class="product-image">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->primaryImage && $product->primaryImage->image_path): ?>
                                                    <img src="<?php echo e(Storage::url($product->primaryImage->image_path)); ?>" alt="<?php echo e($product->name); ?>">
                                                <?php else: ?>
                                                    <img src="<?php echo e(URL::asset('build/images/default-image.png')); ?>" alt="<?php echo e($product->name); ?>">
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>








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
                                    <?php echo $__env->make('clients.shop.partials.empty-products', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <!-- List View -->
                        <div class="tab-pane fade <?php echo e($view == 'list' ? 'show active' : ''); ?>" id="nav-list" role="tabpanel" aria-labelledby="nav-list-tab">
                            <div class="row">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="col-12">
                                        <!-- Start Single Product List -->
                                        <div class="single-product-list">
                                            <div class="row align-items-center">
                                                <div class="col-lg-3 col-md-4 col-12">
                                                    <div class="product-image">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->primaryImage && $product->primaryImage->image_path): ?>
                                                            <img src="<?php echo e(Storage::url($product->primaryImage->image_path)); ?>" alt="<?php echo e($product->name); ?>">
                                                        <?php else: ?>
                                                            <img src="<?php echo e(URL::asset('build/images/default-image.png')); ?>" alt="<?php echo e($product->name); ?>">
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>







                                                            <div class="wishlist-btn">
                                                                <a href="javascript:void(0)" class="wishlist-toggle" data-wishlist-id="<?php echo e($product->id); ?>" title="Добавить в избранное">
                                                                    <i class="lni lni-heart"></i>
                                                                </a>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-5 col-12">
                                                    <div class="product-info">
                                                        <span class="category">
                                                            <?php echo e($product->category ? $product->category->name : 'Без категории'); ?>

                                                        </span>
                                                        <h4 class="title">
                                                            <a href="<?php echo e(route('shop.product-details', $product->slug)); ?>">
                                                                <?php echo e($product->name); ?>

                                                            </a>
                                                        </h4>











                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->description): ?>
                                                            <p class="description"><?php echo e(Str::limit(strip_tags($product->description), 100)); ?></p>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-12">
                                                    <div class="product-price-actions">
                                                        <div class="price">
                                                            <span>$<?php echo e(number_format($product->price, 2)); ?></span>
                                                        </div>
                                                        <div class="action-buttons">
                                                            <a href="javascript:void(0)" class="btn btn-primary add-to-cart" data-product-id="<?php echo e($product->id); ?>" title="Добавить в корзину">
                                                                <i class="lni lni-cart"></i> В корзину
                                                            </a>
                                                            <a href="<?php echo e(route('shop.product-details', $product->slug)); ?>" class="btn btn-secondary btn-sm" title="Подробности">
                                                                Подробнее
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Single Product List -->
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php echo $__env->make('clients.shop.partials.empty-products', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                            <!-- Pagination -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($products->hasPages()): ?>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="pagination left">
                                            <?php echo e($products->links('pagination::bootstrap-4')); ?>

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Main Content -->
        </div>
    </div>
</section>
<!-- End Products Area -->


<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .product-sidebar .single-widget {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .product-sidebar .single-widget h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #0167f3;
            padding-bottom: 10px;
        }

        .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .category-list li {
            position: relative;
            border-bottom: 1px solid #f1f1f1;
        }

        .category-list li:last-child {
            border-bottom: none;
        }

        /* Category Item Structure */
        .category-list .category-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
        }

        .category-list .category-link {
            flex: 1;
            color: #666;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .category-list .category-link:hover,
        .category-list li.active .category-link {
            color: #0167f3;
            padding-left: 10px;
        }

        .category-list .count {
            font-size: 12px;
            color: #999;
            background: #f8f9fa;
            padding: 2px 8px;
            border-radius: 10px;
        }

        /* Category Toggle Button - always visible */
        .category-list .category-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            margin-left: 8px;
            cursor: pointer;
            color: #666;
            transition: all 0.2s ease;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .category-list .category-toggle:hover {
            background: #e8f1ff;
            color: #0167f3;
        }

        .category-list .category-toggle i {
            transition: all 0.2s ease;
        }

        /* Sub Categories - always positioned under parent */
        .category-list .has-children .sub-categories {
            display: none;
            list-style: none;
            padding-left: 20px;
            margin: 5px 0 0 0;
            background: #f8f9fa;
            border-left: 2px solid #e1e8f0;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease-out;
        }

        /* Show subcategories when parent is expanded */
        .category-list .has-children.expanded > .sub-categories {
            display: block;
            max-height: 2000px;
            transition: max-height 0.4s ease-in;
        }

        .category-list .sub-categories li {
            border-bottom: none;
        }

        .category-list .sub-categories li a {
            padding: 8px 12px;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            line-height: 1.4;
        }

        .category-list .sub-categories li a:hover,
        .category-list .sub-categories li.active a {
            background: #e8f1ff;
            color: #0167f3;
        }

        /* Smaller count for subcategories */
        .category-list .sub-categories .count {
            font-size: 11px;
            padding: 1px 6px;
        }

        /* Level 3 specific styles */
        .category-list .sub-categories.level-3 {
            background: #ffffff;
            border-left: 2px solid #c8d8e8;
            padding-left: 15px;
        }

        .product-sorting-menu {
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            padding: 20px 0;
            margin-bottom: 30px;
        }

        /* List View Styles */
        .single-product-list {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .single-product-list:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .single-product-list .product-image {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
        }

        .single-product-list .product-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .single-product-list .product-info {
            padding-left: 0;
        }

        .single-product-list .product-info .category {
            color: #0167f3;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .single-product-list .product-info .title {
            margin: 10px 0;
            font-size: 18px;
            font-weight: 600;
        }

        .single-product-list .product-info .title a {
            color: #333;
            text-decoration: none;
        }

        .single-product-list .product-info .title a:hover {
            color: #0167f3;
        }

        .single-product-list .product-info .description {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-top: 10px;
        }

        .single-product-list .product-price-actions {
            text-align: center;
        }

        .single-product-list .price {
            font-size: 24px;
            font-weight: 700;
            color: #0167f3;
            margin-bottom: 15px;
        }

        .single-product-list .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .single-product-list .action-buttons .btn {
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .single-product-list .action-buttons .wishlist-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #f8f9fa;
            color: #666;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        /* List view star ratings */
        .single-product-list .review {
            list-style: none;
            padding: 0;
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .single-product-list .review li {
            display: inline-block;
        }

        .single-product-list .review li i {
            color: #ffc107;
            font-size: 14px;
        }

        .single-product-list .review li span {
            margin-left: 8px;
            font-size: 12px;
            color: #666;
        }

        /* List view sale/new tags positioning */
        .single-product-list .product-image .new-tag,
        .single-product-list .product-image .sale-tag {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #0167f3;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 2;
        }

        .single-product-list .product-image .sale-tag {
            background: #ff4757;
        }

        .single-product-list .product-image .new-tag {
            background: #28a745;
        }

        /* List view wishlist button positioning */
        .single-product-list .product-image .wishlist-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 3;
        }

        .single-product-list .product-image .wishlist-btn .wishlist-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            background: rgba(255, 255, 255, 0.9);
            color: #666;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .single-product-list .product-image .wishlist-btn .wishlist-toggle:hover,
        .single-product-list .product-image .wishlist-btn .wishlist-toggle.active {
            background: #ff4757;
            color: #fff;
        }

        .single-product-list .product-image .wishlist-btn .wishlist-toggle.active i {
            color: #fff !important;
        }

        /* List view responsive adjustments */
        @media (max-width: 991px) {
            .single-product-list .product-info {
                padding-left: 0;
                margin-top: 20px;
            }

            .single-product-list .product-price-actions {
                margin-top: 20px;
                text-align: left;
            }
        }

        @media (max-width: 767px) {
            .single-product-list {
                padding: 15px;
            }

            .single-product-list .product-image img {
                height: 150px;
            }

            .single-product-list .action-buttons {
                flex-direction: row;
                justify-content: space-between;
            }

            .single-product-list .action-buttons .btn {
                flex: 1;
                margin: 0 5px;
            }
        }

        /* Grid view star colors (ensure consistency) */
        .single-product .review li i.lni-star-filled {
            color: #ffc107;
        }

        .single-product .review li i.lni-star {
            color: #ddd;
        }

        .single-product-list .action-buttons .wishlist-toggle:hover,
        .single-product-list .action-buttons .wishlist-toggle.active {
            background: #ff6b6b;
            color: #fff;
        }

        .single-product-list .review {
            margin: 10px 0;
        }

        @media (max-width: 991px) {
            /* List View Mobile Styles */
            .single-product-list .product-price-actions {
                text-align: left;
                margin-top: 20px;
            }

            .single-product-list .action-buttons {
                align-items: center;
                justify-content: space-between;
            }
        }

        @media (max-width: 767px) {
            .single-product-list .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Global functions for sorting and view switching
        function updateSort() {
            const sortValue = document.getElementById('sort-select').value;
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortValue);
            // Preserve current view parameter
            const currentView = new URLSearchParams(window.location.search).get('view') || 'grid';
            url.searchParams.set('view', currentView);
            window.location.href = url.toString();
        }

        function switchView(viewType) {
            const url = new URL(window.location.href);
            url.searchParams.set('view', viewType);
            window.location.href = url.toString();
        }

        // Bind sorting functionality
        const sortSelect = document.getElementById('sort-select');
        if (sortSelect) {
            sortSelect.addEventListener('change', updateSort);
        }

        // Bind view switching functionality
        const viewButtons = document.querySelectorAll('[data-view]');
        viewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const viewType = this.getAttribute('data-view');
                switchView(viewType);
            });
        });

        // Category toggle functionality - works on all devices
        const categoryToggles = document.querySelectorAll('.category-toggle');
        categoryToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const parentLi = this.closest('.has-children');
                const icon = this.querySelector('i');

                if (parentLi) {
                    parentLi.classList.toggle('expanded');

                    // Change icon between plus and minus
                    if (parentLi.classList.contains('expanded')) {
                        icon.classList.remove('lni-plus');
                        icon.classList.add('lni-minus');
                    } else {
                        icon.classList.remove('lni-minus');
                        icon.classList.add('lni-plus');
                    }
                }
            });
        });

    });
</script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('clients.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/clients/shop/products.blade.php ENDPATH**/ ?>