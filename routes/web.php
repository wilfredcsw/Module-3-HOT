<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('home');
});

Route::get('/analytics', function () {
    return view('analytics');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('route/temperatureroute', 'App\Http\Controllers\dbcontroller@getTemperature');
    Route::get('route/humidityroute', 'App\Http\Controllers\dbcontroller@getHumidity');
    Route::get('route/waterlevelroute', 'App\Http\Controllers\dbcontroller@getWaterLevel');
    Route::get('route/alertroute', 'App\Http\Controllers\dbcontroller@getAlert');


});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
