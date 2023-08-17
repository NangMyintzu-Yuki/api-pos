<?php

use App\Http\Controllers\AuthOperatorController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('operator/register', [AuthOperatorController::class, 'register']);
Route::post('operator/login', [AuthOperatorController::class, 'login']);
Route::post('operator/logout', [AuthOperatorController::class, 'logout']);
Route::post('user/register', [AuthUserController::class, 'register']);
Route::post('user/login', [AuthUserController::class, 'login']);


///////////////////////////////////////// Operator

// Route::group(['middleware' => ['operator']],
//     function(){
        Route::prefix('operator')->group(function(){
            Route::post('change_password', [OperatorController::class, 'changePassword']);

            Route::post('/', [OperatorController::class, 'index']);
            Route::post('/store', [OperatorController::class, 'store']);
            Route::post('/edit', [OperatorController::class, 'edit']);
            Route::post('/update', [OperatorController::class, 'update']);
            Route::post('/delete', [OperatorController::class, 'delete']);
            Route::post('/filter', [OperatorController::class, 'filter']);
        });
// });




///////////////////////////////////////// User

Route::prefix('user')->group(function(){
    Route::post('change_password',[UserController::class,'changePassword']);
    Route::post('reset_password',[UserController::class,'resetPassword']);

    Route::post('/', [UserController::class, 'index']);
    Route::post('/store', [UserController::class, 'store']);
    Route::post('/edit', [UserController::class, 'edit']);
    Route::post('/update', [UserController::class, 'update']);
    Route::post('/delete', [UserController::class, 'delete']);
    Route::post('/filter', [UserController::class, 'filter']);
    Route::post('/change_status',[UserController::class,'changeStatus']);
});


///////////////////////////////////////// Branch

Route::prefix('branch')->group(function(){
    Route::post('/', [BranchController::class, 'index']);
    Route::post('/store', [BranchController::class, 'store']);
    Route::post('/edit', [BranchController::class, 'edit']);
    Route::post('/update', [BranchController::class, 'update']);
    Route::post('/delete', [BranchController::class, 'delete']);
    Route::post('/filter', [BranchController::class, 'filter']);
});



///////////////////////////////////////// Category

Route::prefix('category')->group(function () {
    Route::post('/', [CategoryController::class, 'index']);
    Route::post('/store', [CategoryController::class, 'store']);
    Route::post('/edit', [CategoryController::class, 'edit']);
    Route::post('/update', [CategoryController::class, 'update']);
    Route::post('/delete', [CategoryController::class, 'delete']);
    Route::post('/filter', [CategoryController::class, 'filter']);
});


///////////////////////////////////////// Product

Route::prefix('product')->group(function () {
    Route::post('/', [ProductController::class, 'index']);
    Route::post('/store', [ProductController::class, 'store']);
    Route::post('/edit', [ProductController::class, 'edit']);
    Route::post('/update', [ProductController::class, 'update']);
    Route::post('/delete', [ProductController::class, 'delete']);
    Route::post('/filter', [ProductController::class, 'filter']);
});


///////////////////////////////////////// Product Image

Route::prefix('product_image')->group(function () {
    Route::post('/', [ProductImageController::class, 'index']);
    Route::post('/store', [ProductImageController::class, 'store']);
    Route::post('/edit', [ProductImageController::class, 'edit']);
    Route::post('/update', [ProductImageController::class, 'update']);
    Route::post('/delete', [ProductImageController::class, 'delete']);
    Route::post('/filter', [ProductImageController::class, 'filter']);
});



///////////////////////////////////////// Kitchen

Route::prefix('kitchen')->group(function () {
    Route::post('/', [KitchenController::class, 'index']);
    Route::post('/store', [KitchenController::class, 'store']);
    Route::post('/edit', [KitchenController::class, 'edit']);
    Route::post('/update', [KitchenController::class, 'update']);
    Route::post('/delete', [KitchenController::class, 'delete']);
    Route::post('/filter', [KitchenController::class, 'filter']);
});


///////////////////////////////////////// Table

Route::prefix('table')->group(function () {
    Route::post('/', [TableController::class, 'index']);
    Route::post('/store', [TableController::class, 'store']);
    Route::post('/edit', [TableController::class, 'edit']);
    Route::post('/update', [TableController::class, 'update']);
    Route::post('/delete', [TableController::class, 'delete']);
    Route::post('/filter', [TableController::class, 'filter']);
});


///////////////////////////////////////// Payment Type

Route::prefix('payment_type')->group(function () {
    Route::post('/', [PaymentTypeController::class, 'index']);
    Route::post('/store', [PaymentTypeController::class, 'store']);
    Route::post('/edit', [PaymentTypeController::class, 'edit']);
    Route::post('/update', [PaymentTypeController::class, 'update']);
    Route::post('/delete', [PaymentTypeController::class, 'delete']);
    Route::post('/filter', [PaymentTypeController::class, 'filter']);
});




///////////////////////////////////////// Sale

Route::prefix('sale')->group(function () {
    Route::post('/', [SaleController::class, 'index']);
    Route::post('/store', [SaleController::class, 'store']);
    Route::post('/edit', [SaleController::class, 'edit']);
    Route::post('/update', [SaleController::class, 'update']);
    Route::post('/delete', [SaleController::class, 'delete']);
    Route::post('/filter', [SaleController::class, 'filter']);
});


///////////////////////////////////////// Sale

Route::prefix('sale_detail')->group(function () {
    Route::post('/', [SaleDetailController::class, 'index']);
    Route::post('/store', [SaleDetailController::class, 'store']);
    Route::post('/edit', [SaleDetailController::class, 'edit']);
    Route::post('/update', [SaleDetailController::class, 'update']);
    Route::post('/delete', [SaleDetailController::class, 'delete']);
    Route::post('/filter', [SaleDetailController::class, 'filter']);
});



