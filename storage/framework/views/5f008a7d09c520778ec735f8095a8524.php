<div class="col-12">
    <div class="text-center py-5">
        <i class="lni lni-package" style="font-size: 48px; color: #ddd;"></i>
        <h4 class="mt-3">Товары не найдены</h4>
        <p class="text-muted">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($selectedCategory) && $selectedCategory): ?>
                В категории "<?php echo e($selectedCategory->name); ?>" пока нет товаров.
            <?php else: ?>
                В каталоге пока нет товаров.
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </p>
        <a href="<?php echo e(route('shop.products')); ?>" class="btn btn-primary">Посмотреть все товары</a>
    </div>
</div><?php /**PATH /var/www/html/resources/views/clients/shop/partials/empty-products.blade.php ENDPATH**/ ?>