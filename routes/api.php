<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\ApiTokenMiddleware;

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
date_default_timezone_set('Asia/Jakarta');

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::get('user/info/{api_token}/{user_id}', [UserController::class, 'info']);
Route::get('user/dashboard/{api_token}/{user_id}', [UserController::class, 'dashboard']);
Route::get('user/transaction/{api_token}/{user_id}', [UserController::class, 'transaction']);
Route::get('user/transaction/{api_token}/{user_id}/search/{keyword}', [UserController::class, 'search']);
Route::get('user/transaction/{api_token}/{user_id}/type/{keyword}', [UserController::class, 'type']);
Route::get('user/transaction/{api_token}/{user_id}/date', [UserController::class, 'date']);

Route::get('transaction/show/{api_token}/{id}', [TransactionController::class, 'show']);
Route::post('transaction/storeget', [TransactionController::class, 'storeget']);
Route::post('transaction/storeadd', [TransactionController::class, 'storeadd']);
Route::post('transaction/update', [TransactionController::class, 'update']);
Route::post('transaction/destroy', [TransactionController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware'=>['api_token']], function(){
    
    //Route::get('user/info/"{api_token}"/{user_id}', [UserController::class, 'info']);
    //Route::get('user/transaction/"{api_token}"/{user_id}', [UserController::class, 'transaction']);
    //Route::get('transaction/show/{api_token}/{id}', [TransactionController::class, 'show']);
    //Route::post('transaction/store', [TransactionController::class, 'store']);
    //Route::post('transaction/update', [TransactionController::class, 'update']);
    //Route::post('transaction/destroy', [TransactionController::class, 'destroy']);
        


});