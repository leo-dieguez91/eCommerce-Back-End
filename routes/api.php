<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\GetUserController;
use App\Http\Controllers\User\PostUserController;
use App\Http\Controllers\User\PutUserController;
use App\Http\Controllers\User\DeleteUserController;
use App\Http\Controllers\Product\GetProductController;
use App\Http\Controllers\Product\PostProductController;
use App\Http\Controllers\Product\PutProductController;
use App\Http\Controllers\Product\DeleteProductController;
use App\Http\Controllers\Order\GetOrderController;
use App\Http\Controllers\Order\PostOrderController;
use App\Http\Controllers\Order\PutOrderController;
use App\Http\Controllers\Order\DeleteOrderController;
use App\Http\Controllers\UserAddress\GetUserAddressController;
use App\Http\Controllers\UserAddress\PostUserAddressController;
use App\Http\Controllers\UserAddress\PutUserAddressController;
use App\Http\Controllers\UserAddress\DeleteUserAddressController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth Routes
Route::prefix('/auth')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
    Route::post('/logout', LogoutController::class)->middleware('auth:api');
});

// Routes for Users
Route::get('/users/{id?}', GetUserController::class);

// Routes for Products
Route::get('/products/{id?}', GetProductController::class);
Route::post('/products', PostProductController::class)->middleware(['auth:api', 'role:admin']);
Route::put('/products/{id}', PutProductController::class)->middleware(['auth:api', 'role:admin']);
Route::delete('/products/{id}', DeleteProductController::class)->middleware(['auth:api', 'role:admin']);

// Routes for Orders
Route::middleware('auth:api')->group(function () {
    Route::get('/orders', GetOrderController::class);
    Route::post('/orders', PostOrderController::class);
    Route::put('/orders/{id}', PutOrderController::class);
    Route::delete('/orders/{id}', DeleteOrderController::class);
});
Route::get('/orders/tracking/{tracking_number}', [GetOrderController::class, 'getByTracking']);


// Routes for User Addresses
Route::prefix('/user/addresses')->middleware('auth:api')->group(function () {
    Route::get('/{id?}', GetUserAddressController::class);
    Route::post('/', PostUserAddressController::class);
    Route::delete('/{id}', DeleteUserAddressController::class);
});
