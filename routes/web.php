<?php

use Illuminate\Support\Facades\Route; 

// Customer Controller
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

// Admin Controller
use App\Http\Controllers\admin\LoginController as AdminLoginController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'account'], function(){

    // Guest Middleware
    Route::group(['middleware' => 'guest'], function(){
        Route::get('register',[LoginController::class, 'register'])->name('account.register');
        Route::post('process-register',[LoginController::class, 'processRegister'])->name('account.processRegister');
        Route::get('login',[LoginController::class, 'index'])->name('account.login');
        Route::post('authenticate',[LoginController::class, 'authenticate'])->name('account.authenticate');
    });

    // Authenticate Middleware 
    Route::group(['middleware' => 'auth'], function(){
        Route::get('dashboard',[DashboardController::class, 'index'])->name('account.dashboard');
        Route::get('logout',[LoginController::class, 'logout'])->name('account.logout');        
    });
});


Route::group(['prefix' => 'admin'], function(){

    // Guest Middleware Admin
    Route::group(['middleware' => 'admin.guest'], function(){
        Route::get('register',[AdminLoginController::class, 'register'])->name('admin.register');
        Route::post('process-register',[AdminLoginController::class, 'processRegister'])->name('admin.processRegister');

        Route::get('login',[AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('authenticate',[AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    });

    // Authenticate Middleware Admin
    Route::group(['middleware' => 'admin.auth'], function(){
        Route::get('dashboard',[AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('logout',[AdminLoginController::class, 'logout'])->name('admin.logout');
    });
});





  

