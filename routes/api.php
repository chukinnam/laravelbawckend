<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameinfoController;
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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/signup', [AuthController::class, 'signup']);
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() { 
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::put('/updateuserdetailinfo',[AuthController::class,"updateuserdetailinfo"]);
        Route::get('/getuserdetailinfo',[AuthController::class,'getuserdetailinfo']);
        Route::post('/insertuserdetailinfo',[AuthController::class,'insertuserdetailinfo']);
        Route::post('/usercheckout',[AuthController::class,'usercheckout']);
        Route::get('/getuserorder',[AuthController::class,'getuserorder']);
    });
});

Route::post('/gameinfo',[GameinfoController::class,"getinfo"]);
Route::get('/gameinfo/{id}',[GameinfoController::class,"getoneinfo"]);
Route::get('/image/{name}',[GameinfoController::class,"getimage"]);

