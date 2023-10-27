<?php

use App\Http\Controllers\AddToCartController;
use App\Http\Controllers\AddToCartDetailController;
use App\Http\Controllers\AuthOperatorController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TownshipController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiteSettingController;
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
Route::post('user/register', [AuthUserController::class, 'register']);
Route::post('user/store', [UserController::class, 'store']);
Route::post('user/login', [AuthUserController::class, 'login']);
Route::post('dashboard',[DashboardController::class,'index']);

Route::post('web/product', [ProductController::class, 'index']);
Route::post('web/category', [CategoryController::class, 'index']);
Route::post('web/product/filter', [ProductController::class, 'filter']);
Route::post('web/product/detail', [ProductController::class, 'detail']);
Route::post('getUserLocation',[LocationController::class,'index']);
Route::post('getLocationWithUserId',[LocationController::class, 'getLocationWithUserId']);
Route::post('storeUserLocation',[LocationController::class,'store']);




///////////////////////////////////////// Auth

Route::group(['middleware' => ['auth:sanctum', 'user']], function () {
    Route::prefix('user')->group(function () {
        Route::post('logout', [AuthUserController::class, 'logout']);
        Route::post('change_password', [UserController::class, 'changePassword']);
        Route::post('reset_password', [UserController::class, 'resetPassword']);
    });
});



Route::group(['middleware' => ['auth:sanctum','operator']], function () {

    Route::prefix('role')->group(function () {
        Route::post('/', [RoleController::class, 'index']);
    });

    Route::post('operator/logout', [AuthOperatorController::class, 'logout']);


    ///////////////////////////////////////// Operator


    Route::prefix('operator')->group(function(){
        Route::post('change_password', [OperatorController::class, 'changePassword']);
        Route::post('/', [OperatorController::class, 'index']);
        Route::post('/store', [OperatorController::class, 'store']);
        Route::post('/edit', [OperatorController::class, 'edit']);
        Route::post('/update', [OperatorController::class, 'update']);
        Route::post('/delete', [OperatorController::class, 'delete']);
        Route::post('/filter', [OperatorController::class, 'filter']);
    });



    ///////////////////////////////////////// User

    Route::prefix('user')->group(function(){

        Route::post('/', [UserController::class, 'index']);
        // Route::post('/store', [UserController::class, 'store']);
        Route::post('/edit', [UserController::class, 'edit']);
        Route::post('/update', [UserController::class, 'update']);
        Route::post('/delete', [UserController::class, 'delete']);
        Route::post('/filter', [UserController::class, 'filter']);
        Route::post('/change_status',[UserController::class,'changeStatus']);
        Route::post('/getOrderedItems',[UserController::class, 'getOrderedItems']);
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
        Route::post('/change_status', [TableController::class, 'change_status']);
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
        Route::post('/change_status', [SaleController::class, 'change_status']);
        Route::post('/saleWithUserId', [SaleController::class, 'saleWithUserId']);
    });


    ///////////////////////////////////////// Sale Detail

    Route::prefix('sale_detail')->group(function () {
        Route::post('/', [SaleDetailController::class, 'index']);
        Route::post('/store', [SaleDetailController::class, 'store']);
        Route::post('/edit', [SaleDetailController::class, 'edit']);
        Route::post('/update', [SaleDetailController::class, 'update']);
        Route::post('/delete', [SaleDetailController::class, 'delete']);
        Route::post('/filter', [SaleDetailController::class, 'filter']);
    });


    ///////////////////////////////////////// Add To Cart

    Route::prefix('add_to_cart')->group(function () {
        Route::post('/', [AddToCartController::class, 'index']);
        Route::post('/store', [AddToCartController::class, 'store']);
        Route::post('/edit', [AddToCartController::class, 'edit']);
        Route::post('/update', [AddToCartController::class, 'update']);
        Route::post('/delete', [AddToCartController::class, 'delete']);
        Route::post('/filter', [AddToCartController::class, 'filter']);
    });


    ///////////////////////////////////////// Add To Cart Detail

    Route::prefix('add_to_cart_detail')->group(function () {
        Route::post('/', [AddToCartDetailController::class, 'index']);
        Route::post('/store', [AddToCartDetailController::class, 'store']);
        Route::post('/edit', [AddToCartDetailController::class, 'edit']);
        Route::post('/update', [AddToCartDetailController::class, 'update']);
        Route::post('/delete', [AddToCartDetailController::class, 'delete']);
        Route::post('/filter', [AddToCartDetailController::class, 'filter']);
    });


    ///////////////////////////////////////// Sale

    Route::prefix('payment')->group(function () {
        Route::post('/', [PaymentController::class, 'index']);
        Route::post('/store', [PaymentController::class, 'store']);
        Route::post('/edit', [PaymentController::class, 'edit']);
        Route::post('/update', [PaymentController::class, 'update']);
        Route::post('/delete', [PaymentController::class, 'delete']);
        Route::post('/filter', [PaymentController::class, 'filter']);
        Route::post('/change_status', [PaymentController::class, 'change_status']);
        Route::post('/make_invoice', [PaymentController::class, 'make_invoice']);
    });

    ///////////////////////////////////////// Sale

    Route::prefix('ingredient')->group(function () {
        Route::post('/', [IngredientController::class, 'index']);
        Route::post('/store', [IngredientController::class, 'store']);
        Route::post('/edit', [IngredientController::class, 'edit']);
        Route::post('/update', [IngredientController::class, 'update']);
        Route::post('/delete', [IngredientController::class, 'delete']);
        Route::post('/filter', [IngredientController::class, 'filter']);
    });

    ///////////////////////////////////////// Table

    Route::prefix('division')->group(function () {
        Route::post('/', [DivisionController::class, 'index']);
        Route::post('/store', [DivisionController::class, 'store']);
        Route::post('/edit', [DivisionController::class, 'edit']);
        Route::post('/update', [DivisionController::class, 'update']);
        Route::post('/delete', [DivisionController::class, 'delete']);
        Route::post('/filter', [DivisionController::class, 'filter']);
    });

    ///////////////////////////////////////// City

    Route::prefix('city')->group(function () {
        Route::post('/', [CityController::class, 'index']);
        Route::post('/store', [CityController::class, 'store']);
        Route::post('/edit', [CityController::class, 'edit']);
        Route::post('/update', [CityController::class, 'update']);
        Route::post('/delete', [CityController::class, 'delete']);
        Route::post('/filter', [CityController::class, 'filter']);
    });

    ///////////////////////////////////////// Township

    Route::prefix('township')->group(function () {
        Route::post('/', [TownshipController::class, 'index']);
        Route::post('/store', [TownshipController::class, 'store']);
        Route::post('/edit', [TownshipController::class, 'edit']);
        Route::post('/update', [TownshipController::class, 'update']);
        Route::post('/delete', [TownshipController::class, 'delete']);
        Route::post('/filter', [TownshipController::class, 'filter']);
    });
    ///////////////////////////////////////// Site Setting

    Route::prefix('siteSetting')->group(function () {
        Route::post('/', [SiteSettingController::class, 'index']);
        Route::post('/store', [SiteSettingController::class, 'store']);
        Route::post('/edit', [SiteSettingController::class, 'edit']);
        Route::post('/update', [SiteSettingController::class, 'update']);
        Route::post('/delete', [SiteSettingController::class, 'delete']);
        Route::post('/filter', [SiteSettingController::class, 'filter']);
    });

    ///////////////////////////////////////// Location

    Route::prefix('location')->group(function () {
        Route::post('/store', [LocationController::class, 'store']);
        Route::post('/edit', [LocationController::class, 'edit']);
        Route::post('/update', [LocationController::class, 'update']);
        Route::post('/changeStatus', [LocationController::class, 'changeStatus']);
        Route::post('/delete', [LocationController::class, 'destory']);
    });




});
