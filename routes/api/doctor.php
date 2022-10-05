<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Doctor\AuthController;

Route::post('doctor/register',[AuthController::class, 'register'])->name('register');
Route::post('doctor/login',[AuthController::class, 'login'])->name('login');
Route::group( ['prefix' => 'doctor','middleware' => ['auth:doctor-api','scopes:doctor'] ],function(){
    Route::get('user', [AuthController::class, 'index']);
});
