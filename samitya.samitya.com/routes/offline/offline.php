<?php 

use App\Http\Controllers\Frontend\Auth\LoginController;



Route::group([ 'namespace' => 'Backend' , 'middleware' => 'offline'], function () {


    
    Route::get('logout', [LoginController::class, 'offlinelogout'])->name('logout');
    Route::get('dashboard', 'Admin\OfflineStudentController@show_dashboard')->name('dashboard');
    Route::post('payment', 'Admin\OfflineStudentController@payment')->name('payment');
    Route::get('show', 'Admin\OfflineStudentController@show_payment')->name('show');
    Route::put('pending_payment/{id}','Admin\OfflineStudentController@pending_payment')->name('pending_payment');

    Route::get('download-invoice', 'Admin\OfflineStudentController@getInvoice')->name('download_invoice');



});