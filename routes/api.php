<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HotelController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\HotelImageController;
use App\Http\Controllers\API\CategoryController;


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
Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

// Hotels API
Route::controller(HotelController::class)->group(function(){
    Route::get('hotels', 'index');
    Route::get('hotels/{id}', 'show');
    Route::post('hotels', 'store');
    Route::put('hotels/{id}', 'update');
    Route::delete('hotels/{id}', 'destroy');

    //restore hotel with id
    Route::put('hotels/restore/{id}', 'restore');
});

// hotel images API
Route::controller(HotelImageController::class)->group(function(){
    Route::get('hotel-images', 'index');
    Route::get('hotel-images/{id}', 'show');
    Route::post('hotel-images', 'store');
    Route::put('hotel-images/{id}', 'update');
    Route::delete('hotel-images/{id}', 'destroy');

    Route::get('hotel-images/hotel/{id}', 'getImageByHotelId');
    Route::delete('hotel-images/hotel/{id}', 'deleteImageByHotelId');
    Route::put('hotel-images/hotel/{id}', 'restoreWithHotelID');
});

// Categories API
Route::controller(CategoryController::class)->group(function(){
    Route::get('categories', 'index');
    Route::get('categories/{id}', 'show');
    Route::post('categories', 'store');
    Route::put('categories/{id}', 'update');
    Route::delete('categories/{id}', 'destroy');

    Route::delete('categories/hotel/{id}', 'deleteCategoryByHotelId');
    Route::put('categories/hotel/{id}', 'restoreByHotelId');
    Route::put('categories/restore/{id}', 'restore');
});

