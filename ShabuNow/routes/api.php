<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\UserController;
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
    'middleware' => 'api'
], function () {
    Route::get('/getContacts/{user_id}', [AuthController::class, 'getContacts']);
    Route::post('/updateContacts/{user_id}', [AuthController::class, 'updateContacts']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'menu'
], function () {
    Route::get('', [\App\Http\Controllers\Api\MenuController::class, 'index']);
    Route::get('/available', [\App\Http\Controllers\Api\MenuController::class, 'availableMenu']);
    Route::get('/{id}', [\App\Http\Controllers\Api\MenuController::class, 'showMenuById']);
    Route::post('/store', [\App\Http\Controllers\Api\MenuController::class, 'store']);
    Route::post('/update/{id}', [\App\Http\Controllers\Api\MenuController::class, 'update']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'order'
], function () {
    Route::get('/status/{status}', [\App\Http\Controllers\Api\OrderController::class, 'ordersByStatus']);
    Route::get('/non_status/{status}', [\App\Http\Controllers\Api\OrderController::class, 'ordersByNonStatus']);
    Route::get('/{user_id}/status/{status}', [\App\Http\Controllers\Api\OrderController::class, 'orderByStatus']);
    Route::get('/{user_id}/non_status/{status}', [\App\Http\Controllers\Api\OrderController::class, 'orderByNonStatus']);
    Route::get('/getQueue', [\App\Http\Controllers\Api\OrderController::class, 'getQueue']);
    Route::post('/{user_id}/addMenu', [\App\Http\Controllers\Api\OrderController::class, 'addMenu']);
    Route::post('/{user_id}/allStore', [\App\Http\Controllers\Api\OrderController::class, 'allStore']);
    Route::post('/{order_id}/updateOrderStatus', [\App\Http\Controllers\Api\OrderController::class, 'updateOrderStatus']);
    Route::post('/{order_id}/{status}', [\App\Http\Controllers\Api\OrderController::class, 'updateStatus']);
});

Route::group([ //customer section --------------------------------------------------------------------------------------------
    'middleware' => 'api',
    'prefix' => 'customer'
], function () {
    Route::get('/', [UserController::class, 'listCustomer']);
    Route::get('{user}', [UserController::class, 'show']); //show 1 User (NOT ONLY 1 STAFF)
    Route::put('{user}/update', [UserController::class, 'update']); //show 1 User (NOT ONLY 1 STAFF)
    Route::post('{user}/updatePassword', [UserController::class, 'updatePassword']);
    Route::delete('{user}/delete', [UserController::class, 'destroy']);
    Route::post('create', [UserController::class, 'store']);
});

Route::group([ //staff section --------------------------------------------------------------------------------------------
    'middleware' => 'api',
    'prefix' => 'staff'
], function () {
    Route::get('/', [UserController::class, 'listStaff']);
    Route::get('{user}', [UserController::class, 'show']); //show 1 User (NOT ONLY 1 STAFF)
    Route::put('{user}/update', [UserController::class, 'update']); //show 1 User (NOT ONLY 1 STAFF)
    Route::delete('{user}/delete', [UserController::class, 'destroy']);
    Route::post('create', [UserController::class, 'store']);
});
