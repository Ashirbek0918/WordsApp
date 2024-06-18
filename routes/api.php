<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WordsController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\WordsCheckController;

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

Route::post('admin/login',[AuthController::class,'login']);
Route::post('user/register',[UserAuthController::class,'register']);
Route::post('user/login',[UserAuthController::class,'login']);
Route::middleware('auth:sanctum')->group(function(){
    //admin routes
    Route::put('admin/update/{user}',[AuthController::class,'update']);
    Route::post('logout',[AuthController::class,'logout']);
    Route::get('getme',[AuthController::class,'getme']);

    //user routes
    Route::put('user/update/{user}',[UserAuthController::class,'update']);

    //words routes
    Route::get('words/unchecked',[WordsController::class,'uncheckedWords']);
    Route::get('words/mywords',[WordsController::class,'mywords']);
    Route::post('words/create',[WordsController::class,'create']);
    Route::put('words/update/{word}',[WordsController::class,'update'])->middleware('can:update-unverified-word,unverified');
    Route::delete('words/delete/{id}',[WordsController::class,'delete'])->middleware('can:update-unverified-word,unverified');
    Route::get('users',[WordsController::class,'usersWithWords']);

    //words check routes
    Route::post('words/check',[WordsCheckController::class,'checkWords']);
});
