<?php

use App\Http\Controllers\AuthOperatorController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\OperatorController;
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
Route::post('user/register', [AuthUserController::class, 'register']);
Route::post('user/login', [AuthUserController::class, 'login']);


///////////////////////////////////////// Operator

Route::prefix('operator')->group(function(){
    Route::post('change_password', [OperatorController::class, 'changePassword']);
});


///////////////////////////////////////// User

Route::prefix('user')->group(function(){
    Route::post('change_password',[UserController::class,'changePassword']);
    Route::post('reset_password',[UserController::class,'resetPassword']);
});


