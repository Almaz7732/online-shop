

<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Dashboards'); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s ease-in-out infinite;
        border-radius: 4px;
    }
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    .skeleton-text {
        height: 20px;
        margin-bottom: 10px;
    }
    .skeleton-title {
        height: 30px;
        width: 60%;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> Dashboards <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> Dashboard <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<!-- Date Range Filter -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="card-title mb-md-0"><i class="bx bx-calendar me-2"></i>Статистика за выбранный период</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <label class="form-label">Начало периода</label>
                                <input type="text" id="start_date" class="form-control" placeholder="Выберите дату">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Конец периода</label>
                                <input type="text" id="end_date" class="form-control" placeholder="Выберите дату">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button id="applyFilter" class="btn btn-primary w-100">
                                    <i class="bx bx-filter-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-primary-subtle">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-primary">Welcome Back !</h5>
                            <p>TechStore Dashboard</p>
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        <img src="<?php echo e(URL::asset('build/images/profile-img.png')); ?>" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="avatar-md profile-user-wid mb-4">
                            <img src="<?php echo e(isset(Auth::user()->avatar) ? asset(Auth::user()->avatar) : asset('build/images/users/user-dummy-img.jpg')); ?>" alt="" class="img-thumbnail rounded-circle">
                        </div>
                        <h5 class="font-size-15 text-truncate"><?php echo e(Str::ucfirst(Auth::user()->name)); ?></h5>
                    </div>

                    <div class="col-sm-8">
                        <div class="pt-4">
                            <div class="row">
                                <div class="col-6">
                                    <div id="profile-orders-loading" class="skeleton skeleton-text" style="width: 80px;"></div>
                                    <h5 class="font-size-15" id="profile-orders-count" style="display: none;">0</h5>
                                    <p class="text-muted mb-0">Заказы</p>
                                </div>
                                <div class="col-6">
                                    <div id="profile-revenue-loading" class="skeleton skeleton-text" style="width: 100px;"></div>
                                    <h5 class="font-size-15" id="profile-revenue" style="display: none;">0 СОМ</h5>
                                    <p class="text-muted mb-0">Доход</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="" class="btn btn-primary waves-effect waves-light btn-sm">View Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-xl-8">
        <div class="row">
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Заказы</p>
                                <div id="widget-orders-loading" class="skeleton skeleton-text" style="width: 80px;"></div>
                                <h4 class="mb-0" id="widget-orders-count" style="display: none;">0</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-copy-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Доход</p>
                                <div id="widget-revenue-loading" class="skeleton skeleton-text" style="width: 100px;"></div>
                                <h4 class="mb-0" id="widget-revenue" style="display: none;">0 СОМ</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="bx bx-archive-in font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Посетители</p>
                                <div id="widget-visitors-loading" class="skeleton skeleton-text" style="width: 80px;"></div>
                                <h4 class="mb-0" id="widget-visitors" style="display: none;">0</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-success">
                                        <i class="bx bx-user font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Просмотры товаров</p>
                                <div id="widget-product-views-loading" class="skeleton skeleton-text" style="width: 80px;"></div>
                                <h4 class="mb-0" id="widget-product-views" style="display: none;">0</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-info">
                                        <i class="bx bx-show font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Доход за период</h4>
                        <div class="row">
                            <div class="col">
                                <p class="text-muted">За выбранный период</p>
                                <div id="period-revenue-loading" class="skeleton skeleton-text" style="width: 150px;"></div>
                                <h3 id="period-revenue" style="display: none;">0 СОМ</h3>
                                <p class="text-muted">
                                    <span id="growth-percentage" style="display: none;">
                                        <span id="growth-value" class="me-2">0%</span>
                                    </span>
                                    <span id="growth-loading" class="skeleton skeleton-text" style="width: 100px;"></span>
                                    <span id="growth-label">Из предыдущего периода</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->


    </div>
</div>
<!-- end row -->


<!-- Analytics Charts -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Посещения сайта</h4>
                <div id="siteVisitsChart-loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                </div>
                <div id="siteVisitsChart" class="apex-charts" dir="ltr" style="display: none;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Топ-10 просматриваемых товаров</h4>
                <div id="productViewsChart-loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                </div>
                <div id="productViewsChart" class="apex-charts" dir="ltr" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Top Products Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Популярные товары</h4>
                <div class="table-responsive">
                    <div id="top-products-loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Загрузка...</span>
                        </div>
                    </div>
                    <table class="table table-nowrap align-middle" id="top-products-table" style="display: none;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Товар</th>
                            <th>Изображение</th>
                            <th>Категория</th>
                            <th>Цена</th>
                            <th>Просмотры</th>
                        </tr>
                        </thead>
                        <tbody id="top-products-tbody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Transaction Modal -->
<div class="modal fade transaction-detailModal" tabindex="-1" role="dialog" aria-labelledby="transaction-detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transaction-detailModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Product id: <span class="text-primary">#SK2540</span></p>
                <p class="mb-4">Billing Name: <span class="text-primary">Neal Matthews</span></p>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <div>
                                        <img src="<?php echo e(URL::asset('build/images/product/img-7.png')); ?>" alt="" class="avatar-sm">
                                    </div>
                                </th>
                                <td>
                                    <div>
                                        <h5 class="text-truncate font-size-14">Wireless Headphone (Black)</h5>
                                        <p class="text-muted mb-0">$ 225 x 1</p>
                                    </div>
                                </td>
                                <td>$ 255</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <div>
                                        <img src="<?php echo e(URL::asset('build/images/product/img-4.png')); ?>" alt="" class="avatar-sm">
                                    </div>
                                </th>
                                <td>
                                    <div>
                                        <h5 class="text-truncate font-size-14">Phone patterned cases</h5>
                                        <p class="text-muted mb-0">$ 145 x 1</p>
                                    </div>
                                </td>
                                <td>$ 145</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h6 class="m-0 text-right">Sub Total:</h6>
                                </td>
                                <td>
                                    $ 400
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h6 class="m-0 text-right">Shipping:</h6>
                                </td>
                                <td>
                                    Free
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h6 class="m-0 text-right">Total:</h6>
                                </td>
                                <td>
                                    $ 400
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal -->

<!-- subscribeModal -->
<div class="modal fade" id="subscribeModal" tabindex="-1" aria-labelledby="subscribeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar-md mx-auto mb-4">
                        <div class="avatar-title bg-light rounded-circle text-primary h1">
                            <i class="mdi mdi-email-open"></i>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-10">
                            <h4 class="text-primary">Subscribe !</h4>
                            <p class="text-muted font-size-14 mb-4">Subscribe our newletter and get notification to stay
                                update.</p>

                            <div class="input-group bg-light rounded">
                                <input type="email" class="form-control bg-transparent border-0" placeholder="Enter Email address" aria-label="Recipient's username" aria-describedby="button-addon2">

                                <button class="btn btn-primary" type="button" id="button-addon2">
                                    <i class="bx bxs-paper-plane"></i>
                                </button>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end modal -->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ru.js"></script>

<!-- apexcharts -->
<script src="<?php echo e(URL::asset('build/libs/apexcharts/apexcharts.min.js')); ?>"></script>

<!-- dashboard init -->
<script src="<?php echo e(URL::asset('build/js/pages/dashboard.init.js')); ?>"></script>

<!-- Statistics AJAX -->
<script>
let siteVisitsChartInstance = null;
let productViewsChartInstance = null;

// Initialize Flatpickr
const startDatePicker = flatpickr("#start_date", {
    dateFormat: "Y-m-d",
    locale: "ru",
    defaultDate: new Date(new Date().getFullYear(), new Date().getMonth(), 1), // First day of current month
    onChange: function(selectedDates, dateStr, instance) {
        // Update end date min date
        endDatePicker.set('minDate', dateStr);
    }
});

const endDatePicker = flatpickr("#end_date", {
    dateFormat: "Y-m-d",
    locale: "ru",
    defaultDate: new Date(), // Today
    onChange: function(selectedDates, dateStr, instance) {
        // Update start date max date
        startDatePicker.set('maxDate', dateStr);
    }
});

// Load statistics
function loadStatistics() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;

    // Show loading indicators
    showLoading();

    // Make AJAX request
    fetch(`<?php echo e(route('admin.statistics')); ?>?start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            updateStatistics(data);
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
            alert('Ошибка загрузки статистики');
        });
}

function showLoading() {
    // Profile card
    document.getElementById('profile-orders-loading').style.display = 'block';
    document.getElementById('profile-orders-count').style.display = 'none';
    document.getElementById('profile-revenue-loading').style.display = 'block';
    document.getElementById('profile-revenue').style.display = 'none';

    // Widgets
    document.getElementById('widget-orders-loading').style.display = 'block';
    document.getElementById('widget-orders-count').style.display = 'none';
    document.getElementById('widget-revenue-loading').style.display = 'block';
    document.getElementById('widget-revenue').style.display = 'none';
    document.getElementById('widget-visitors-loading').style.display = 'block';
    document.getElementById('widget-visitors').style.display = 'none';
    document.getElementById('widget-product-views-loading').style.display = 'block';
    document.getElementById('widget-product-views').style.display = 'none';

    // Period revenue
    document.getElementById('period-revenue-loading').style.display = 'block';
    document.getElementById('period-revenue').style.display = 'none';
    document.getElementById('growth-loading').style.display = 'inline-block';
    document.getElementById('growth-percentage').style.display = 'none';

    // Charts
    document.getElementById('siteVisitsChart-loading').style.display = 'block';
    document.getElementById('siteVisitsChart').style.display = 'none';
    document.getElementById('productViewsChart-loading').style.display = 'block';
    document.getElementById('productViewsChart').style.display = 'none';

    // Table
    document.getElementById('top-products-loading').style.display = 'block';
    document.getElementById('top-products-table').style.display = 'none';
}

function updateStatistics(data) {
    // Profile card
    document.getElementById('profile-orders-loading').style.display = 'none';
    document.getElementById('profile-orders-count').textContent = numberFormat(data.ordersCount);
    document.getElementById('profile-orders-count').style.display = 'block';

    document.getElementById('profile-revenue-loading').style.display = 'none';
    document.getElementById('profile-revenue').textContent = numberFormat(data.totalRevenue, 0) + ' СОМ';
    document.getElementById('profile-revenue').style.display = 'block';

    // Widgets
    document.getElementById('widget-orders-loading').style.display = 'none';
    document.getElementById('widget-orders-count').textContent = numberFormat(data.ordersCount);
    document.getElementById('widget-orders-count').style.display = 'block';

    document.getElementById('widget-revenue-loading').style.display = 'none';
    document.getElementById('widget-revenue').textContent = numberFormat(data.totalRevenue, 0) + ' СОМ';
    document.getElementById('widget-revenue').style.display = 'block';

    document.getElementById('widget-visitors-loading').style.display = 'none';
    document.getElementById('widget-visitors').textContent = numberFormat(data.uniqueVisitors);
    document.getElementById('widget-visitors').style.display = 'block';

    document.getElementById('widget-product-views-loading').style.display = 'none';
    document.getElementById('widget-product-views').textContent = numberFormat(data.productViews);
    document.getElementById('widget-product-views').style.display = 'block';

    // Period revenue
    document.getElementById('period-revenue-loading').style.display = 'none';
    document.getElementById('period-revenue').textContent = numberFormat(data.periodRevenue, 0) + ' СОМ';
    document.getElementById('period-revenue').style.display = 'block';

    document.getElementById('growth-loading').style.display = 'none';
    const growthValue = data.growthPercentage;
    const growthClass = growthValue >= 0 ? 'text-success' : 'text-danger';
    const growthIcon = growthValue >= 0 ? 'up' : 'down';
    const growthSign = growthValue >= 0 ? '+' : '';

    document.getElementById('growth-value').className = growthClass + ' me-2';
    document.getElementById('growth-value').innerHTML = `${growthSign}${growthValue}% <i class="mdi mdi-arrow-${growthIcon}"></i>`;
    document.getElementById('growth-percentage').style.display = 'inline';

    // Update charts
    updateCharts(data);

    // Update table
    updateTopProductsTable(data.topProducts);
}

function updateCharts(data) {
    // Site Visits Chart
    const siteVisitsOptions = {
        series: [{
            name: 'Всего посещений',
            data: data.siteVisitsChart.total_visits
        }, {
            name: 'Уникальные посетители',
            data: data.siteVisitsChart.unique_visitors
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        colors: ['#556ee6', '#34c38f'],
        xaxis: {
            categories: data.siteVisitsChart.dates
        },
        yaxis: {
            title: {
                text: 'Количество'
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        }
    };

    if (siteVisitsChartInstance) {
        siteVisitsChartInstance.destroy();
    }
    document.getElementById('siteVisitsChart-loading').style.display = 'none';
    document.getElementById('siteVisitsChart').style.display = 'block';
    siteVisitsChartInstance = new ApexCharts(document.querySelector("#siteVisitsChart"), siteVisitsOptions);
    siteVisitsChartInstance.render();

    // Product Views Chart
    const productViewsOptions = {
        series: [{
            name: 'Просмотры',
            data: data.productViewsChart.view_counts
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            },
        },
        plotOptions: {
            bar: {
                horizontal: true,
                dataLabels: {
                    position: 'top'
                },
                borderRadius: 4,
                borderRadiusApplication: 'end',
            }
        },
        dataLabels: {
            enabled: true,
            offsetX: 30,
            style: {
                fontSize: '12px',
                colors: ['#304758'],
            }
        },
        xaxis: {
            categories: data.productViewsChart.product_names
        },
        yaxis: {
            title: {
                text: undefined
            }
        }
    };

    if (productViewsChartInstance) {
        productViewsChartInstance.destroy();
    }
    document.getElementById('productViewsChart-loading').style.display = 'none';
    document.getElementById('productViewsChart').style.display = 'block';
    productViewsChartInstance = new ApexCharts(document.querySelector("#productViewsChart"), productViewsOptions);
    productViewsChartInstance.render();
}

function updateTopProductsTable(topProducts) {
    const tbody = document.getElementById('top-products-tbody');
    tbody.innerHTML = '';

    if (topProducts.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">Нет данных</td></tr>';
    } else {
        topProducts.forEach((item, index) => {
            const row = document.createElement('tr');

            let imageHtml = '';
            if (item.product.primary_image && item.product.primary_image.image_path) {
                imageHtml = `<img src="/storage/${item.product.primary_image.image_path}" alt="${item.product.name}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">`;
            } else {
                imageHtml = `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="bx bx-image text-muted"></i></div>`;
            }

            row.innerHTML = `
                <td>${index + 1}</td>
                <td>
                    <a href="/admin/products/${item.product.id}" class="text-body fw-bold">
                        ${item.product.name}
                    </a>
                </td>
                <td>${imageHtml}</td>
                <td>${item.product.category ? item.product.category.name : 'N/A'}</td>
                <td>${numberFormat(item.product.price, 2)} СОМ</td>
                <td>
                    <span class="badge badge-soft-success font-size-12">
                        ${numberFormat(item.views_count)} просмотров
                    </span>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    document.getElementById('top-products-loading').style.display = 'none';
    document.getElementById('top-products-table').style.display = 'table';
}

function numberFormat(number, decimals = 0) {
    return new Intl.NumberFormat('ru-RU', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(number);
}

// Apply filter button
document.getElementById('applyFilter').addEventListener('click', function() {
    loadStatistics();
});

// Load statistics on page load
document.addEventListener('DOMContentLoaded', function() {
    loadStatistics();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/index.blade.php ENDPATH**/ ?>