<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TaxiOrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TariffController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CarTypeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('/customers')->group(function() {
    Route::get('/get-partners', [PartnerController::class, 'index']);
    Route::get('/{partner}/get-products', [ProductController::class, 'categoryProducts']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('/admin')->group(function() {
        Route::resource('users', UserController::class);
        Route::post('/drivers/{driver}/add-sum', [DriverController::class, 'addSum']);
        Route::resource('drivers', DriverController::class);
        Route::resource('car-types', CarTypeController::class);
        Route::resource('cars', CarController::class);
        Route::resource('tariffs', TariffController::class);
        Route::resource('partners', PartnerController::class);
        Route::resource('customers', CustomerController::class);
        Route::resource('clients', ClientController::class);
        Route::post('drivers/{driver}/activate', [DriverController::class, 'activate']);
    });

    Route::prefix('/drivers')->group(function() {
        Route::get('/get-new-orders', [TaxiOrderController::class, 'getNewOrders']);
        Route::post('/merge-order', [DriverController::class, 'mergeOrder']);
        Route::post('/add-sum', [DriverController::class, 'addSum']);
        Route::get('/{driver}/get-tariff', [DriverController::class, 'getTariff']);
        Route::get('/{driver}/daily-clients', [DriverController::class, 'daily']);
        Route::get('/{driver}/clients', [DriverController::class, 'clients']);
        Route::get('/{driver}/get-profits', [DriverController::class, 'profit']);
        Route::get('/{driver}/orders', [DriverController::class, 'orders']);
        Route::get('/{driver}', [DriverController::class, 'show']);
        Route::put('/{driver}', [DriverController::class, 'update']);
        Route::post('/{driver}', [DriverController::class, 'history']);
    });

    Route::prefix('/partners')->group(function() {
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::get('/{partner}/orders', [OrderController::class, 'index']);
        Route::get('/{order}/order-items', [OrderController::class, 'getOrderItems']);
        Route::post('/{order}/change-order-status', [OrderController::class, 'changeOrderStatus']);
        Route::post('/{item}/change-item-status', [OrderController::class, 'changeItemStatus']);
        Route::get('/{partner}', [PartnerController::class, 'show']);
        Route::put('/{partner}', [PartnerController::class, 'update']);
    });

    Route::prefix('/operators')->group(function() {
        Route::post('/driver-location', [DriverController::class, 'check']);
        Route::post('/create-order', [TaxiOrderController::class, 'store']);
        Route::get('/get-delivery-orders', [OrderController::class, 'getOrdersByStatus']);
        Route::get('/get-taxi-orders', [TaxiOrderController::class, 'getOrdersByStatus']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::put('/{user}', [UserController::class, 'update']);
    });

    Route::prefix('/customers')->group(function() {
//        Route::get('/get-partners', [PartnerController::class, 'index']);
//        Route::get('/{partner}/get-products', [ProductController::class, 'categoryProducts']);
        Route::post('/set-order', [OrderController::class, 'store']);
        Route::get('/{customer}/get-orders', [OrderController::class, 'getDeliveryOrders']);
        Route::get('/{customer}/get-finished-orders', [OrderController::class, 'getOrdersByStatus']);
        Route::get('{customer}', [CustomerController::class, 'show']);
        Route::put('{customer}', [CustomerController::class, 'update']);
        Route::post('{update}');
    });

    Route::prefix('/clients')->group(function() {
        Route::post('/driver-location', [DriverController::class, 'check']);
        Route::post('/create-order', [TaxiOrderController::class, 'store']);
        Route::get('/{client}', [UserController::class, 'show']);
        Route::put('/{client}', [UserController::class, 'update']);
    });
});

/* Authentication */
Route::get('/car-types', [CarTypeController::class, 'index']);
Route::get('/tariffs', [TariffController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/driver/login', [AuthController::class, 'driver']);
Route::post('/driver/verify', [AuthController::class, 'verify']);
Route::post('/driver/register', [DriverController::class, 'store']);
Route::post('/drivers/{driver}/car', [CarController::class, 'store']);
Route::post('/partner/login', [AuthController::class, 'partner']);
Route::post('/customer/register', [CustomerController::class, 'store']);
Route::post('/customer/login', [AuthController::class, 'customer']);
Route::post('/customer/verify', [AuthController::class, 'verifyCustomer']);
Route::post('/client/login', [AuthController::class, 'client']);
Route::post('/client/verify', [AuthController::class, 'verifyClient']);

Route::post('/receive-status', [AuthController::class, 'receive'])->name('receive_status');

/* Any route */
Route::any('/{all?}', function() {
    return response()->json([
        'msg' => "Ushbu sohaga kirishga ruxsat berilmagan"
    ], 401);
})->name('any');