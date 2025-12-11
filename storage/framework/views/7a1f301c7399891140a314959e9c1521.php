<?php $__env->startSection('title'); ?>
    SEO настройки
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <!-- DataTables -->
    <link href="<?php echo e(URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert -->
    <link href="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            SEO настройки
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            SEO настройки
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Управление SEO настройками</h4>
                            <p class="card-title-desc">Настройте SEO для каждой страницы сайта для лучшей индексации в поисковиках</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?php echo e(route('admin.seo.create')); ?>" class="btn btn-primary">
                                <i class="bx bx-plus me-1"></i> Добавить SEO настройку
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="seo-table" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Страница</th>
                                    <th>Meta информация</th>
                                    <th>Статус</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Preview Modal -->
    <div class="modal fade" id="seoPreviewModal" tabindex="-1" aria-labelledby="seoPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="seoPreviewModalLabel">Предпросмотр в поисковике</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="preview-container">
                        <div class="search-result-preview">
                            <div class="result-url text-success" id="preview-url"></div>
                            <h3 class="result-title text-primary" id="preview-title"></h3>
                            <div class="result-description text-muted" id="preview-description"></div>
                        </div>

                        <hr>

                        <h6>Schema.org разметка:</h6>
                        <pre id="preview-schema" class="bg-light p-3 rounded"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <!-- DataTables -->
    <script src="<?php echo e(URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')); ?>"></script>
    <!-- Sweet Alert -->
    <script src="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize DataTable
            $('#seo-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "<?php echo e(route('admin.seo.data')); ?>",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%'},
                    {data: 'page_info', name: 'page_info', width: '20%'},
                    {data: 'meta_info', name: 'meta_info', width: '35%'},
                    {data: 'status_badge', name: 'status_badge', width: '10%'},
                    {data: 'created_at', name: 'created_at', width: '15%'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false, width: '15%'},
                ],
                order: [[4, 'desc']],
                pageLength: 25,
                responsive: true,
                language: {
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                    emptyTable: 'SEO настройки не найдены'
                }
            });

            // Delete SEO setting
            $(document).on('click', '.delete-seo', function() {
                const seoId = $(this).data('id');

                Swal.fire({
                    title: 'Вы уверены?',
                    text: "Это действие удалит SEO настройки!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Да, удалить!',
                    cancelButtonText: 'Отмена'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "<?php echo e(url('admin/seo')); ?>/" + seoId,
                            type: 'DELETE',
                            data: {
                                _token: '<?php echo e(csrf_token()); ?>'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Удалено!',
                                    text: response.message,
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                                $('#seo-table').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Ошибка!',
                                    text: 'Что-то пошло не так при удалении.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });
            });

            // Preview SEO
            $(document).on('click', '.preview-seo', function() {
                const seoId = $(this).data('id');

                $.ajax({
                    url: "<?php echo e(url('admin/seo/preview')); ?>/" + seoId,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#preview-url').text(response.preview.url);
                            $('#preview-title').text(response.preview.title);
                            $('#preview-description').text(response.preview.description);
                            $('#preview-schema').text(JSON.stringify(response.preview.schema, null, 2));
                            $('#seoPreviewModal').modal('show');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ошибка!',
                            text: 'Не удалось загрузить предпросмотр.',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                });
            });
        });

        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Успех',
                text: '<?php echo e(session('success')); ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Ошибка',
                text: '<?php echo e(session('error')); ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>

<style>
.search-result-preview {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    background: #f9f9f9;
    margin-bottom: 20px;
}

.result-url {
    font-size: 14px;
    margin-bottom: 5px;
}

.result-title {
    font-size: 18px;
    margin-bottom: 8px;
    line-height: 1.2;
}

.result-description {
    font-size: 14px;
    line-height: 1.4;
}

#preview-schema {
    font-size: 12px;
    max-height: 300px;
    overflow-y: auto;
}
</style>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\ident-admin\resources\views/admin/seo/index.blade.php ENDPATH**/ ?>