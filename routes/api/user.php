<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\User\AuthController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RoleController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('admin/register',[AuthController::class, 'register'])->name('register');
Route::post('admin/login',[AuthController::class, 'login'])->name('login');
Route::group( ['prefix' => 'admin','middleware' => ['auth:admin-api','scopes:admin'] ],function(){
   // authenticated staff routes here 
    Route::get('user', [UserController::class, 'index']);
    Route::get('users',[AuthController::class, 'index']);

    // Route::get('get-permissions', [PermissionController::class, 'index']);
    // Route::post('create-permission', [PermissionController::class, 'store']);
    
    // Route::resource('roles', RoleController::class);
});

Route::post('admin/create-doctor',[UserController::class, 'createDoctor']);
Route::post('admin/update-doctor/{id}',[UserController::class, 'updateDoctor']);
Route::get('admin/get-doctors',[UserController::class, 'getDoctors']);
Route::get('admin/get-doctor/{id}',[UserController::class, 'getSpecificDoctor']);

Route::get('admin/get-permissions', [PermissionController::class, 'index']);
Route::get('admin/get-permission/{id}', [PermissionController::class, 'show']);
Route::post('admin/create-permission', [PermissionController::class, 'store']);

Route::get('admin/get-roles', [RoleController::class, 'index']);
Route::get('admin/get-role/{id}', [RoleController::class, 'show']);
Route::post('admin/create-role', [RoleController::class, 'store']);