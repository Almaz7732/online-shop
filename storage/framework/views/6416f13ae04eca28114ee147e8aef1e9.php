<?php $__env->startSection('title'); ?>
    Edit About Us Content
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <!-- Summer Note -->
    <link href="<?php echo e(URL::asset('build/libs/summernote/summernote-bs5.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            About Us
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Edit About Us Content
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Edit About Us Content</h4>
                            <p class="card-title-desc">Update the content information below</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?php echo e(route('admin.about-us.index')); ?>" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Back to About Us
                            </a>
                        </div>
                    </div>

                    <form action="<?php echo e(route('admin.about-us.update', $aboutUs->id)); ?>" method="POST" id="about-us-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Content Information</h5>

                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   id="title" name="title" value="<?php echo e(old('title', $aboutUs->title)); ?>"
                                                   placeholder="Enter about us title">
                                            <?php $__errorArgs = ['title'];
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
                                            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                            <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                      id="content" name="content" rows="15"><?php echo e(old('content', $aboutUs->content)); ?></textarea>
                                            <?php $__errorArgs = ['content'];
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
                            </div>

                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Settings</h5>

                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       id="is_active" name="is_active" <?php echo e(old('is_active', $aboutUs->is_active) ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="is_active">
                                                    Active Status
                                                </label>
                                            </div>
                                            <small class="text-muted">Enable this content to be displayed on the website</small>
                                        </div>

                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bx bx-check me-1"></i> Update Content
                                            </button>
                                        </div>

                                        <div class="mb-3">
                                            <button type="button" class="btn btn-secondary w-100" onclick="window.history.back()">
                                                <i class="bx bx-x me-1"></i> Cancel
                                            </button>
                                        </div>

                                        <div class="border-top pt-3">
                                            <h6 class="text-muted">Content Information</h6>
                                            <small class="text-muted d-block">Created: <?php echo e($aboutUs->created_at->format('M d, Y H:i')); ?></small>
                                            <small class="text-muted d-block">Updated: <?php echo e($aboutUs->updated_at->format('M d, Y H:i')); ?></small>
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
    <!-- Summer Note -->
    <script src="<?php echo e(URL::asset('build/libs/summernote/summernote-bs5.min.js')); ?>"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('#content').summernote({
                height: 300,
                placeholder: 'Write your about us content here...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });

            // Form validation
            $('#about-us-form').submit(function(e) {
                var title = $('#title').val().trim();
                var content = $('#content').summernote('code');

                if (title === '') {
                    e.preventDefault();
                    alert('Please enter a title');
                    $('#title').focus();
                    return false;
                }

                if (content === '' || content === '<p><br></p>') {
                    e.preventDefault();
                    alert('Please enter content');
                    $('#content').summernote('focus');
                    return false;
                }
            });

            // Show success message if exists
            <?php if(session('success')): ?>
                Swal.fire({
                    title: 'Success!',
                    text: '<?php echo e(session('success')); ?>',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\ident-admin\resources\views/admin/about-us/edit.blade.php ENDPATH**/ ?>