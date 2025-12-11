<?php $__env->startSection('title'); ?>
    Create Product
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <!-- Select2 -->
    <link href="<?php echo e(URL::asset('build/libs/select2/css/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- Dropzone -->
    <link href="<?php echo e(URL::asset('build/libs/dropzone/dropzone.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- Summer Note -->
    <link href="<?php echo e(URL::asset('build/libs/summernote/summernote-bs5.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Products
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Create Product
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Create New Product</h4>
                            <p class="card-title-desc">Fill in the product information below</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Back to Products
                            </a>
                        </div>
                    </div>

                    <form action="<?php echo e(route('products.store')); ?>" method="POST" enctype="multipart/form-data" id="product-form">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Basic Information</h5>

                                        <div class="mb-3">
                                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   id="name" name="name" value="<?php echo e(old('name')); ?>"
                                                   placeholder="Enter product name" required>
                                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">СОМ</span>
                                                <input type="number" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                       id="price" name="price" value="<?php echo e(old('price')); ?>"
                                                       step="0.01" min="0" placeholder="0.00" required>
                                                <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea id="description" name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description')); ?></textarea>
                                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Images -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Product Images</h5>
                                        <p class="card-title-desc">Upload multiple images for your product. The first uploaded image will be set as primary.</p>

                                        <div class="dropzone" id="product-images-dropzone">
                                            <div class="fallback">
                                                <input name="images[]" type="file" multiple="multiple" accept="image/*">
                                            </div>
                                            <div class="dz-message needsclick">
                                                <div class="mb-3">
                                                    <i class="display-4 text-muted bx bx-image-add"></i>
                                                </div>
                                                <h4>Drop product images here or click to upload.</h4>
                                                <p class="text-muted">Only image files are allowed (JPG, PNG, GIF)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Product Details</h5>

                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-select select2 <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    id="category_id" name="category_id" required>
                                                <option value="">Select a category</option>
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                                        <?php echo e($category->name); ?>

                                                    </option>
                                                    <?php if($category->children->count() > 0): ?>
                                                        <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($child->id); ?>" <?php echo e(old('category_id') == $child->id ? 'selected' : ''); ?>>
                                                                &nbsp;&nbsp;── <?php echo e($child->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="mb-3">
                                            <label for="brand_id" class="form-label">Brand</label>
                                            <select class="form-select select2 <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    id="brand_id" name="brand_id">
                                                <option value="">No Brand</option>
                                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($brand->id); ?>" <?php echo e(old('brand_id') == $brand->id ? 'selected' : ''); ?>>
                                                        <?php echo e($brand->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bx bx-save me-1"></i> Create Product
                                            </button>
                                            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary">
                                                <i class="bx bx-x me-1"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Preview -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Preview</h5>
                                        <div class="product-preview">
                                            <div class="product-img-preview mb-3">
                                                <img id="preview-image" src="https://via.placeholder.com/300x300?text=No+Image"
                                                     class="img-fluid rounded" alt="Product Preview"
                                                     style="width: 100%; height: 200px; object-fit: cover;">
                                            </div>
                                            <h6 id="preview-name" class="text-muted">Product Name</h6>
                                            <h5 id="preview-price" class="text-primary">$0.00</h5>
                                            <p id="preview-category" class="text-muted small">Category: Not selected</p>
                                            <p id="preview-brand" class="text-muted small">Brand: No Brand</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <!-- Select2 -->
    <script src="<?php echo e(URL::asset('build/libs/select2/js/select2.min.js')); ?>"></script>
    <!-- Dropzone -->
    <script src="<?php echo e(URL::asset('build/libs/dropzone/dropzone-min.js')); ?>"></script>
    <!-- Summer Note -->
    <script src="<?php echo e(URL::asset('build/libs/summernote/summernote-bs5.min.js')); ?>"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true,
                width: '100%'
            });

            // Initialize SummerNote
            $('#description').summernote({
                placeholder: 'Hello stand alone ui',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link',]],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Initialize Dropzone
            Dropzone.autoDiscover = false;
            const productDropzone = new Dropzone("#product-images-dropzone", {
                url: "#", // We'll handle upload on form submit
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFiles: 10,
                maxFilesize: 2, // MB
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                dictRemoveFile: "Remove",
                dictDefaultMessage: "Drop product images here or click to upload.",

                init: function() {
                    const dropzone = this;

                    // Handle form submission
                    $("#product-form").on("submit", function(e) {
                        if (dropzone.getQueuedFiles().length > 0) {
                            e.preventDefault();
                            e.stopPropagation();

                            // Create FormData with form fields
                            const formData = new FormData(this);

                            // Add dropzone files
                            dropzone.getQueuedFiles().forEach(function(file) {
                                formData.append('images[]', file);
                            });

                            // Submit via Ajax
                            $.ajax({
                                url: $(this).attr('action'),
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    window.location.href = "<?php echo e(route('products.index')); ?>";
                                },
                                error: function(xhr) {
                                    if (xhr.status === 422) {
                                        const errors = xhr.responseJSON.errors;
                                        // Handle validation errors
                                        Object.keys(errors).forEach(function(key) {
                                            const input = $('[name="' + key + '"]');
                                            input.addClass('is-invalid');
                                            input.closest('.mb-3').find('.invalid-feedback').remove();
                                            input.after('<div class="invalid-feedback">' + errors[key][0] + '</div>');
                                        });
                                    }
                                }
                            });
                        }
                    });

                    // Update preview when files are added
                    this.on("addedfile", function(file) {
                        if (this.files[0] === file) {
                            updateImagePreview(file);
                        }
                    });

                    this.on("removedfile", function(file) {
                        if (this.files.length > 0) {
                            updateImagePreview(this.files[0]);
                        } else {
                            resetImagePreview();
                        }
                    });
                }
            });

            // Update preview functions
            function updateImagePreview(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }

            function resetImagePreview() {
                $('#preview-image').attr('src', 'https://via.placeholder.com/300x300?text=No+Image');
            }

            // Live preview updates
            $('#name').on('input', function() {
                const value = $(this).val() || 'Product Name';
                $('#preview-name').text(value);
            });

            $('#price').on('input', function() {
                const value = parseFloat($(this).val()) || 0;
                $('#preview-price').text('$' + value.toFixed(2));
            });

            $('#category_id').on('change', function() {
                const text = $(this).find('option:selected').text() || 'Not selected';
                $('#preview-category').text('Category: ' + text);
            });

            $('#brand_id').on('change', function() {
                const text = $(this).find('option:selected').text() || 'No Brand';
                $('#preview-brand').text('Brand: ' + text);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\ident-admin\resources\views/admin/products/create.blade.php ENDPATH**/ ?>