<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UsersControllerAPI;
use App\Http\Controllers\API\OrdersControllerAPI;
use App\Http\Controllers\API\ProductsControllerAPI;
use App\Http\Controllers\API\Sales_DetailsControllerAPI;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [UsersControllerAPI::class, 'login']);
Route::post('register', [UsersControllerAPI::class, 'register']);

Route::post('order-add', [UsersControllerAPI::class, 'orderAdd']);
Route::post('product-add', [UsersControllerAPI::class, 'productAdd']);


Route::post('reset-password', [UsersControllerAPI::class, 'resetPassword']);


Route::get('get-all-orders', [OrdersControllerAPI::class, 'getAllOrders']);
Route::get('get-orders', [OrdersControllerAPI::class, 'getOrders']);
Route::get('search-orders', [OrdersControllerAPI::class, 'searchOrders']);


Route::get('products-api', [ProductsControllerAPI::class, 'productsAPI']);


Route::get('sales-api', [Sales_DetailsControllerAPI::class, 'salesAPI']);
