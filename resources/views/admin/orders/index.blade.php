@extends('layouts.master')

@section('title') Заказы @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Управление @endslot
@slot('title') Заказы @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Управление заказами</h4>
                <p class="card-title-desc">Просмотр и управление заказами пользователей</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="orders-table" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Клиент</th>
                                <th>Телефон</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                                <th>Дата создания</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                    </table>
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
                <h5 class="modal-title" id="statusModalLabel">Изменить статус заказа</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="status-form">
                    <div class="mb-3">
                        <label for="order-status" class="form-label">Выберите новый статус:</label>
                        <select class="form-select" id="order-status" name="status" required>
                            <option value="pending">В ожидании</option>
                            <option value="processing">В обработке</option>
                            <option value="completed">Завершен</option>
                            <option value="cancelled">Отменен</option>
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

@endsection

@section('script')
<script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

<script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {
    let currentOrderId = null;

    // Initialize DataTable
    const table = $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.orders.data") }}',
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'customer', name: 'customer' },
            { data: 'phone', name: 'phone' },
            { data: 'formatted_amount', name: 'formatted_amount', orderable: false },
            { data: 'status_badge', name: 'status_badge', orderable: false },
            { data: 'created_date', name: 'created_date' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[ 0, 'desc' ]],
        pageLength: 25,
        responsive: true,
        language: {
            processing: "Загрузка...",
            search: "Поиск:",
            lengthMenu: "Показать _MENU_ записей",
            info: "Показано от _START_ до _END_ из _TOTAL_ записей",
            infoEmpty: "Показано от 0 до 0 из 0 записей",
            infoFiltered: "(отфильтровано из _MAX_ записей)",
            loadingRecords: "Загрузка записей...",
            zeroRecords: "Записи отсутствуют.",
            emptyTable: "В таблице отсутствуют данные",
            paginate: {
                first: "Первая",
                previous: "Предыдущая",
                next: "Следующая",
                last: "Последняя"
            }
        }
    });

    // Status change functionality
    $(document).on('click', '.status-change', function(e) {
        e.preventDefault();
        currentOrderId = $(this).data('id');
        const currentStatus = $(this).data('status');

        $('#order-status').val(currentStatus);
        $('#statusModal').modal('show');
    });

    // Save status change
    $('#save-status').click(function() {
        if (!currentOrderId) return;

        const newStatus = $('#order-status').val();

        $.ajax({
            url: '{{ url("admin/orders") }}/' + currentOrderId,
            method: 'PUT',
            data: {
                status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#statusModal').modal('hide');
                    table.ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: 'Успешно!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
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
    $(document).on('click', '.delete-order', function(e) {
        e.preventDefault();
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
                    url: '{{ url("admin/orders") }}/' + orderId,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();

                            Swal.fire({
                                icon: 'success',
                                title: 'Удалено!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
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
@endsection

@section('css')
<link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
