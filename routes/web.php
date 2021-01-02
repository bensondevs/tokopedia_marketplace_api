<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TokopediaApiController;

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

Route::redirect('/', 'login', 301);

Auth::routes();

Route::group([
    'middleware' => ['auth'],
    'as' => 'dashboard.',
    'prefix' => 'dashboard',
], function () {
	Route::get('/', [HomeController::class, 'index'])->name('index');

	Route::get('shops', [TokopediaApiController::class, 'getShops'])->name('shops');
	Route::get('add_shop', [ShopController::class, 'addShop'])->name('shops.add_shop');
	Route::post('store_shop', [ShopController::class, 'storeShop'])->name('shops.store_shop');
	Route::post('remove_shop', [ShopController::class, 'removeShop'])->name('shops.remove_shop');
	
	Route::get('orders', [TokopediaApiController::class, 'getOrders'])->name('orders');
	Route::post('accept_order', [TokopediaApiController::class, 'acceptOrder'])->name('orders.accept_order');
	Route::post('reject_order', [TokopediaApiController::class, 'rejectOrder'])->name('orders.reject_order');
	Route::get('print_shipping_label', [TokopediaApiController::class, 'printShippingLabel'])->name('orders.print_shipping_label');
	Route::get('load_shipping_label', [TokopediaApiController::class, 'loadShippingLabel'])->name('orders.load_shipping_label');
	Route::post('request_pickup', [TokopediaApiController::class, 'requestPickUp'])->name('orders.request_pickup');
});
