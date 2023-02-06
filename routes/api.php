<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\CartController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [UserController::class, 'auth']);
Route::post('/register', [UserController::class, 'register']);

Route::middleware('jwt.verify')->group(function () {

    Route::post('/logout', [UserController::class, 'logout']);

    Route::prefix('profile')->group(function() {
        Route::get('/', [ProfileController::class, 'index']);
        Route::put('/update', [ProfileController::class, 'update']);
    });

    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'delete']);
    });

    Route::prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'delete']);

        Route::get('/all', [ProductController::class, 'groupByCategory']);
    
        Route::put('/{id}/discount', [ProductController::class, 'setDiscount']);
        Route::post('/{id}/status', [ProductController::class, 'setStatus']);
    });

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::delete('/{id}', [CartController::class, 'delete']);
        Route::get('/qty', [CartController::class, 'getQty']);
    });
});

