<?php

use App\Http\Controllers\Frontend\Auth\LoginController;


Route::group([ 'middleware' => 'guest'], function () {
    

Route::group([ 'middleware' => 'offline.guest'], function () {
    
    Route::get('login', [LoginController::class, 'showLoginPage'])->name('login');
    Route::post('login', [LoginController::class, 'offline_login'])->name('login.post');



});
});