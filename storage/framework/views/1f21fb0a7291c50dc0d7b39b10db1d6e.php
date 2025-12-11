<?php $__env->startSection('title'); ?> Product Details <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> Products <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <a href="<?php echo e(route('products.index')); ?>">Products</a> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php echo e($product->name); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Product Details - <?php echo e($product->name); ?></h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <h5 class="font-size-14 text-muted">Basic Information:</h5>
                            <p class="mb-1"><strong>Name:</strong> <?php echo e($product->name); ?></p>
                            <p class="mb-1"><strong>Slug:</strong> <code><?php echo e($product->slug); ?></code></p>
                            <p class="mb-0"><strong>Price:</strong> <span class="text-success font-weight-bold"><?php echo e(number_format($product->price, 2)); ?> СОМ</span></p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <h5 class="font-size-14 text-muted">Category & Brand:</h5>
                            <p class="mb-1"><strong>Category:</strong> <?php echo e($product->category ? $product->category->name : 'N/A'); ?></p>
                            <p class="mb-1"><strong>Brand:</strong> <?php echo e($product->brand ? $product->brand->name : 'No Brand'); ?></p>
                            <p class="mb-0"><strong>Created:</strong> <?php echo e($product->created_at->format('d.m.Y H:i')); ?></p>
                        </div>
                    </div>
                </div>

                <?php if($product->description): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="font-size-14 text-muted">Description:</h5>
                            <div class="alert alert-info">
                                <?php echo nl2br(e($product->description)); ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <h5 class="font-size-14 text-muted">Product Images:</h5>
                        <?php if($product->images->count() > 0): ?>
                            <div class="row">
                                <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="card">
                                            <div class="position-relative">
                                                <img src="<?php echo e(Storage::url($image->image_path)); ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Product Image">
                                                <?php if($image->is_primary): ?>
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">Primary</span>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <button type="button" class="btn btn-sm btn-light" onclick="viewImage('<?php echo e(Storage::url($image->image_path)); ?>')">
                                                        <i class="bx bx-expand"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="bx bx-image text-muted" style="font-size: 48px;"></i>
                                <p class="text-muted mt-2">No images available for this product</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Actions</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit Product
                    </a>

                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Products
                    </a>

                    <button type="button" class="btn btn-danger" onclick="deleteProduct(<?php echo e($product->id); ?>)">
                        <i class="bx bx-trash"></i> Delete Product
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Statistics</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <h5 class="font-size-18 mb-1"><?php echo e($product->images->count()); ?></h5>
                            <p class="text-muted mb-0">Images</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h5 class="font-size-18 mb-1"><?php echo e(number_format($product->price, 2)); ?> СОМ</h5>
                            <p class="text-muted mb-0">Price</p>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="text-center">
                            <h6 class="font-size-14 mb-1">Last Updated</h6>
                            <p class="text-muted mb-0"><?php echo e($product->updated_at->format('d.m.Y H:i')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>

<script>
function viewImage(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}

function deleteProduct(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete the product and all its images!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo e(url('admin/products')); ?>/" + id,
                type: 'DELETE',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.success,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '<?php echo e(route("products.index")); ?>';
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong while deleting the product.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}

<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
<?php endif; ?>

<?php if(session('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?php echo e(session('error')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\ident-admin\resources\views/admin/products/show.blade.php ENDPATH**/ ?>