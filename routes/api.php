<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserBookmarkController;
use App\Http\Controllers\CartController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/products', [ProductController::class, 'store'])->middleware('validate_product');
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'show']);

    Route::post('/product/{id}/bookmark', [UserBookmarkController::class, 'add']);
    Route::post('/product/{id}/unbookmark', [UserBookmarkController::class, 'remove']);
    Route::get('/bookmarks', [UserBookmarkController::class, 'index']);

    Route::post('/cart/add/order', [CartController::class, 'addToCart']);
    Route::post('/cart/delete/order', [CartController::class, 'deleteFromCart']); //delete a specific order from cart
});


Route::get('/testing', [\App\Http\Controllers\TestingController::class, 'index']);
