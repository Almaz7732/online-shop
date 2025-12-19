<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiteSettingsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\MapSettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
    'confirm' => false,
]);

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/products', [ShopController::class, 'products'])->name('shop.products');
Route::get('/products/category/{slug}', [ShopController::class, 'products'])->name('shop.products.category');
Route::get('/product/{slug}', [ShopController::class, 'productDetails'])->name('shop.product-details');
Route::get('/wishlist', [ShopController::class, 'wishlist'])->name('shop.wishlist');
Route::get('/cart', [ShopController::class, 'cart'])->name('shop.cart');
Route::get('/cart-data', [ShopController::class, 'cartData'])->name('shop.cart-data');
Route::get('/about', [ShopController::class, 'about'])->name('shop.about');
Route::get('/search-suggestions', [ShopController::class, 'searchSuggestions'])->name('shop.search-suggestions');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

Route::group(['middleware' => ['auth', 'permission:admin-setting']], function () {
    Route::get('/admin', [App\Http\Controllers\HomeController::class, 'root'])->name('root');
    Route::get('/admin/statistics', [App\Http\Controllers\HomeController::class, 'getStatistics'])->name('admin.statistics');

    //Update User Details
    Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

    // Products Management Routes
    Route::prefix('admin')->group(function () {
        Route::resource('brands', BrandController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);

        // Product Images Management
        Route::post('products/{product}/upload-images', [ProductController::class, 'uploadImages'])->name('products.upload-images');
        Route::delete('product-images/{image}', [ProductController::class, 'deleteImage'])->name('product-images.delete');
        Route::patch('product-images/{image}/set-primary', [ProductController::class, 'setPrimaryImage'])->name('product-images.set-primary');

        // Site Settings Management Routes
        Route::get('site-settings', [SiteSettingsController::class, 'index'])->name('site-settings.index');
        Route::post('site-settings/update', [SiteSettingsController::class, 'updateSettings'])->name('site-settings.update');

        // Carousel Management Routes
        Route::get('site-settings/carousel-data', [SiteSettingsController::class, 'carouselData'])->name('site-settings.carousel.data');
        Route::get('site-settings/carousel/create', [SiteSettingsController::class, 'createCarousel'])->name('site-settings.carousel.create');
        Route::post('site-settings/carousel', [SiteSettingsController::class, 'storeCarousel'])->name('site-settings.carousel.store');
        Route::get('site-settings/carousel/{slide}/edit', [SiteSettingsController::class, 'editCarousel'])->name('site-settings.carousel.edit');
        Route::put('site-settings/carousel/{slide}', [SiteSettingsController::class, 'updateCarousel'])->name('site-settings.carousel.update');
        Route::delete('site-settings/carousel/{slide}', [SiteSettingsController::class, 'destroyCarousel'])->name('site-settings.carousel.destroy');

        // Orders Management Routes
        Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('orders/data', [OrderController::class, 'data'])->name('admin.orders.data');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::put('orders/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
        Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');

        // About Management Routes
        Route::resource('about', AboutController::class, ['as' => 'admin']);

        // Map Settings Routes
        Route::get('map-settings', [MapSettingController::class, 'index'])->name('admin.map-settings.index');
        Route::put('map-settings', [MapSettingController::class, 'update'])->name('admin.map-settings.update');

        // SEO Management Routes
        Route::get('seo/data', [\App\Http\Controllers\SeoController::class, 'data'])->name('admin.seo.data');
        Route::get('seo/preview/{id}', [\App\Http\Controllers\SeoController::class, 'preview'])->name('admin.seo.preview');
        Route::resource('seo', \App\Http\Controllers\SeoController::class, ['as' => 'admin']);
    });
});

//Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
