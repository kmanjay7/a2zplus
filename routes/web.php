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
            Route::get('get-ben/{id}', 'MoneyTransferController@getBen')->name('getBen');
            Route::post('verify-otp', 'MoneyTransferController@verifyOtp')->name('verifyOtp');
            Route::post('resend-otp', 'MoneyTransferController@resendOtp')->name('resendOtp');
            Route::post('ben-delete', 'MoneyTransferController@benDelete')->name('benDelete');
            Route::get('ben-list/{sender_id}', 'MoneyTransferController@benList')->name('benList');
            Route::post('confirm-ben/{id}', 'MoneyTransferController@confirmBen')->name('confirmBen');
            Route::post('trans-init/{id}', 'MoneyTransferController@transactionInit')->name('transactionInit');
        });
    });
});           