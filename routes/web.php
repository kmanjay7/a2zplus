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

require __DIR__.'/auth.php';

Route::namespace('Admin')->middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', 'CommonController@index')->name('index');
        Route::resource('money-transfer', 'MoneyTransferController');
        Route::prefix('dmt')->name('dmt.')->group(function () {
            Route::get('logout', 'MoneyTransferController@logout')->name('logout');
            Route::post('verifyOtp', 'MoneyTransferController@verifyOtp')->name('verifyOtp');
            Route::post('resendOtp', 'MoneyTransferController@resendOtp')->name('resendOtp');
        });
    });
});           