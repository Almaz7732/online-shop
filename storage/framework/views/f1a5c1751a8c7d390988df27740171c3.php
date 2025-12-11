<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Order_Details'); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> Management <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <a href="<?php echo e(route('admin.orders.index')); ?>">Orders</a> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> Order #<?php echo e($order->id); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Детали заказа #<?php echo e($order->id); ?></h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <h5 class="font-size-14 text-muted">Информация о клиенте:</h5>
                            <p class="mb-1"><strong>Имя:</strong> <?php echo e($order->name); ?> <?php echo e($order->surname); ?></p>
                            <p class="mb-1"><strong>Телефон:</strong> <?php echo e($order->phone); ?></p>
                            <p class="mb-0"><strong>Адрес:</strong> <?php echo e($order->address); ?></p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <h5 class="font-size-14 text-muted">Информация о заказе:</h5>
                            <p class="mb-1"><strong>Дата создания:</strong> <?php echo e($order->created_at->format('d.m.Y H:i')); ?></p>
                            <p class="mb-1"><strong>Статус:</strong>
                                <?php
                                    $badgeClass = match($order->status) {
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                    $statusText = match($order->status) {
                                        'pending' => 'В ожидании',
                                        'processing' => 'В обработке',
                                        'completed' => 'Завершен',
                                        'cancelled' => 'Отменен',
                                        default => $order->status
                                    };
                                ?>
                                <span class="badge bg-<?php echo e($badgeClass); ?>"><?php echo e($statusText); ?></span>
                            </p>
                            <p class="mb-0"><strong>Общая сумма:</strong> <span class="text-success font-weight-bold"><?php echo e(number_format($order->total_amount, 2)); ?> СОМ</span></p>
                        </div>
                    </div>
                </div>

                <?php if($order->comment): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="font-size-14 text-muted">Комментарий к заказу:</h5>
                            <div class="alert alert-info">
                                <?php echo e($order->comment); ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <h5 class="font-size-14 text-muted">Товары в заказе:</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="10%">Фото</th>
                                        <th width="40%">Товар</th>
                                        <th width="15%">Количество</th>
                                        <th width="17.5%">Цена за единицу</th>
                                        <th width="17.5%">Итого</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $subtotal = 0;
                                    ?>
                                    <?php $__currentLoopData = $order->cart_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            // Calculate price per item from total amount
                                            $totalItems = collect($order->cart_data)->sum('quantity');
                                            $pricePerItem = $order->total_amount / $totalItems;
                                            $itemTotal = $pricePerItem * $item['quantity'];
                                            $subtotal += $itemTotal;
                                            $product = $order->products[$item['id']] ?? null;
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php if($product && $product->primaryImage): ?>
                                                    <img src="<?php echo e(Storage::url($product->primaryImage->image_path)); ?>"
                                                         class="rounded"
                                                         style="width: 50px; height: 50px; object-fit: cover;"
                                                         alt="<?php echo e($product->name ?? 'Product'); ?>">
                                                <?php else: ?>
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="bx bx-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($product): ?>
                                                    <div>
                                                        <a href="<?php echo e(route('products.show', $item['id'])); ?>" class="fw-medium">
                                                            <?php echo e($product->name); ?>

                                                        </a>
                                                        <div class="text-muted small">ID: #<?php echo e($item['id']); ?></div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">Продукт не найден (ID: #<?php echo e($item['id']); ?>)</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center"><?php echo e($item['quantity']); ?></td>
                                            <td class="text-center"><?php echo e(number_format($pricePerItem, 2)); ?> СОМ</td>
                                            <td class="text-center"><?php echo e(number_format($itemTotal, 2)); ?> СОМ</td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-end">Итого:</th>
                                        <th class="text-center"><?php echo e(number_format($order->total_amount, 2)); ?> СОМ</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Действия</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="fas fa-edit"></i> Изменить статус
                    </button>

                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Вернуться к списку
                    </a>

                    <button type="button" class="btn btn-danger delete-order" data-id="<?php echo e($order->id); ?>">
                        <i class="fas fa-trash"></i> Удалить заказ
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Статистика</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <h5 class="font-size-18 mb-1"><?php echo e(collect($order->cart_data)->sum('quantity')); ?></h5>
                            <p class="text-muted mb-0">Товаров</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h5 class="font-size-18 mb-1"><?php echo e(number_format($order->total_amount, 2)); ?> СОМ</h5>
                            <p class="text-muted mb-0">Сумма</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Изменить статус заказа #<?php echo e($order->id); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="status-form">
                    <div class="mb-3">
                        <label for="order-status" class="form-label">Выберите новый статус:</label>
                        <select class="form-select" id="order-status" name="status" required>
                            <option value="pending" <?php echo e($order->status == 'pending' ? 'selected' : ''); ?>>В ожидании</option>
                            <option value="processing" <?php echo e($order->status == 'processing' ? 'selected' : ''); ?>>В обработке</option>
                            <option value="completed" <?php echo e($order->status == 'completed' ? 'selected' : ''); ?>>Завершен</option>
                            <option value="cancelled" <?php echo e($order->status == 'cancelled' ? 'selected' : ''); ?>>Отменен</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="save-status">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>

<script>
$(document).ready(function() {
    // Save status change
    $('#save-status').click(function() {
        const newStatus = $('#order-status').val();

        $.ajax({
            url: '<?php echo e(route("admin.orders.update", $order->id)); ?>',
            method: 'PUT',
            data: {
                status: newStatus,
                _token: '<?php echo e(csrf_token()); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $('#statusModal').modal('hide');

                    // Reload page to show updated status
                    location.reload();
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ошибка!',
                    text: 'Произошла ошибка при обновлении статуса.',
                    confirmButtonText: 'ОК'
                });
            }
        });
    });

    // Delete order functionality
    $('.delete-order').click(function() {
        const orderId = $(this).data('id');

        Swal.fire({
            title: 'Вы уверены?',
            text: "Это действие нельзя будет отменить!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Да, удалить!',
            cancelButtonText: 'Отмена'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo e(route("admin.orders.destroy", $order->id)); ?>',
                    method: 'DELETE',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Удалено!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '<?php echo e(route("admin.orders.index")); ?>';
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ошибка!',
                            text: 'Произошла ошибка при удалении заказа.',
                            confirmButtonText: 'ОК'
                        });
                    }
                });
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\ident-admin\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>