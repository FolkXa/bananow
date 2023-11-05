<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function() {
    Route::post('/login','login');
    Route::post('/register','register');
});
Route::controller(AuthController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout','logout');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'category'
], function () {
    Route::get('', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
    Route::post('/store', [\App\Http\Controllers\Api\CategoryController::class, 'store']);
    Route::put('/update/{name}', [\App\Http\Controllers\Api\CategoryController::class, 'update']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'menu'
], function () {
    Route::get('', [\App\Http\Controllers\Api\MenuController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\Api\MenuController::class, 'showMenuById']);
    Route::post('/store', [\App\Http\Controllers\Api\MenuController::class, 'store']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'order'
], function () {
    Route::get('/{user_id}/{status}', [\App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::get('/getQueue', [\App\Http\Controllers\Api\OrderController::class, 'getQueue']);
    Route::post('/{user_id}/addMenu', [\App\Http\Controllers\Api\OrderController::class, 'addMenu']);
    Route::post('/{user_id}/allStore', [\App\Http\Controllers\Api\OrderController::class, 'allStore']);
});
