<!-- Page Type and Identifier -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="page_type" class="form-label">Тип страницы <span class="text-danger">*</span></label>
            <select class="form-select" id="page_type" name="page_type" required>
                <option value="">Выберите тип страницы</option>
                <?php $__currentLoopData = $pageTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" <?php echo e(old('page_type', isset($seoSetting) ? $seoSetting->page_type : '') == $key ? 'selected' : ''); ?>>
                        <?php echo e($value); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="page_identifier" class="form-label">Идентификатор страницы</label>
            <input type="text" class="form-control" id="page_identifier" name="page_identifier"
                   value="<?php echo e(old('page_identifier', isset($seoSetting) ? $seoSetting->page_identifier : '')); ?>"
                   placeholder="Например: slug категории или товара">
            <div class="form-text">Оставьте пустым для общих настроек типа страницы</div>
        </div>
    </div>
</div>

<!-- Basic Meta Tags -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3"><i class="bx bx-search me-2"></i>Основные meta теги</h5>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="meta_title" class="form-label">Meta Title</label>
            <input type="text" class="form-control" id="meta_title" name="meta_title" maxlength="70"
                   value="<?php echo e(old('meta_title', isset($seoSetting) ? $seoSetting->meta_title : '')); ?>"
                   placeholder="SEO заголовок (до 70 символов)">
            <div class="form-text">
                <span id="title-counter">0</span>/70 символов
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="meta_keywords" class="form-label">Meta Keywords</label>
            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                   value="<?php echo e(old('meta_keywords', isset($seoSetting) ? $seoSetting->meta_keywords : '')); ?>"
                   placeholder="ключевые, слова, через, запятую">
        </div>
    </div>
    <div class="col-12">
        <div class="mb-3">
            <label for="meta_description" class="form-label">Meta Description</label>
            <textarea class="form-control" id="meta_description" name="meta_description" rows="3" maxlength="160"
                      placeholder="SEO описание (до 160 символов)"><?php echo e(old('meta_description', isset($seoSetting) ? $seoSetting->meta_description : '')); ?></textarea>
            <div class="form-text">
                <span id="description-counter">0</span>/160 символов
            </div>
        </div>
    </div>
</div>

<!-- Open Graph -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3"><i class="bx bx-share-alt me-2"></i>Open Graph (социальные сети)</h5>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="og_title" class="form-label">OG Title</label>
            <input type="text" class="form-control" id="og_title" name="og_title" maxlength="70"
                   value="<?php echo e(old('og_title', isset($seoSetting) ? $seoSetting->og_title : '')); ?>"
                   placeholder="Заголовок для соцсетей">
            <div class="form-text">
                <span id="og-title-counter">0</span>/70 символов
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="og_image" class="form-label">OG Image</label>
            <input type="text" class="form-control" id="og_image" name="og_image"
                   value="<?php echo e(old('og_image', isset($seoSetting) ? $seoSetting->og_image : '')); ?>"
                   placeholder="URL изображения для соцсетей">
        </div>
    </div>
    <div class="col-12">
        <div class="mb-3">
            <label for="og_description" class="form-label">OG Description</label>
            <textarea class="form-control" id="og_description" name="og_description" rows="3" maxlength="160"
                      placeholder="Описание для соцсетей"><?php echo e(old('og_description', isset($seoSetting) ? $seoSetting->og_description : '')); ?></textarea>
            <div class="form-text">
                <span id="og-description-counter">0</span>/160 символов
            </div>
        </div>
    </div>
</div>

<!-- Technical SEO -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3"><i class="bx bx-cog me-2"></i>Технические настройки</h5>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="canonical_url" class="form-label">Canonical URL</label>
            <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                   value="<?php echo e(old('canonical_url', isset($seoSetting) ? $seoSetting->canonical_url : '')); ?>"
                   placeholder="https://example.com/canonical-url">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="robots_meta" class="form-label">Robots Meta</label>
            <select class="form-select" id="robots_meta" name="robots_meta">
                <option value="index,follow" <?php echo e(old('robots_meta', isset($seoSetting) ? $seoSetting->robots_meta : 'index,follow') == 'index,follow' ? 'selected' : ''); ?>>
                    index,follow (по умолчанию)
                </option>
                <option value="noindex,follow" <?php echo e(old('robots_meta', isset($seoSetting) ? $seoSetting->robots_meta : '') == 'noindex,follow' ? 'selected' : ''); ?>>
                    noindex,follow
                </option>
                <option value="index,nofollow" <?php echo e(old('robots_meta', isset($seoSetting) ? $seoSetting->robots_meta : '') == 'index,nofollow' ? 'selected' : ''); ?>>
                    index,nofollow
                </option>
                <option value="noindex,nofollow" <?php echo e(old('robots_meta', isset($seoSetting) ? $seoSetting->robots_meta : '') == 'noindex,nofollow' ? 'selected' : ''); ?>>
                    noindex,nofollow
                </option>
            </select>
        </div>
    </div>
</div>

<!-- Status -->
<div class="row mb-4">
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                   <?php echo e(old('is_active', isset($seoSetting) ? $seoSetting->is_active : true) ? 'checked' : ''); ?>>
            <label class="form-check-label" for="is_active">
                Активировать эту SEO настройку
            </label>
        </div>
    </div>
</div>

<?php $__env->startSection('script'); ?>
<script>
$(document).ready(function() {
    // Character counters
    function updateCounter(inputId, counterId, maxLength) {
        const input = $('#' + inputId);
        const counter = $('#' + counterId);

        function updateCount() {
            const length = input.val().length;
            counter.text(length);

            if (length > maxLength * 0.8) {
                counter.parent().removeClass('text-muted').addClass('text-warning');
            }
            if (length > maxLength * 0.95) {
                counter.parent().removeClass('text-warning').addClass('text-danger');
            }
            if (length <= maxLength * 0.8) {
                counter.parent().removeClass('text-warning text-danger').addClass('text-muted');
            }
        }

        input.on('input', updateCount);
        updateCount(); // Initial count
    }

    // Initialize counters
    updateCounter('meta_title', 'title-counter', 70);
    updateCounter('meta_description', 'description-counter', 160);
    updateCounter('og_title', 'og-title-counter', 70);
    updateCounter('og_description', 'og-description-counter', 160);

    // Auto-fill OG fields from meta fields
    $('#meta_title').on('input', function() {
        if ($('#og_title').val() === '') {
            $('#og_title').val($(this).val());
            updateCounter('og_title', 'og-title-counter', 70);
        }
    });

    $('#meta_description').on('input', function() {
        if ($('#og_description').val() === '') {
            $('#og_description').val($(this).val());
            updateCounter('og_description', 'og-description-counter', 160);
        }
    });
});
</script>
<?php $__env->stopSection(); ?><?php /**PATH D:\projects\ident-admin\resources\views/admin/seo/_form.blade.php ENDPATH**/ ?>