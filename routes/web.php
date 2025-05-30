<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Lat1Controller;
use App\Http\Controllers\API\PurchaseController;

Route::inertia('/', 'Home')->name('home');

Route::get('/login', function () {
  return inertia('Auth/Login'); 
})->name('login');  

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/register', function () {
  return inertia('Auth/Register'); 
});
Route::post('/register', [AuthController::class, 'register']);
Route::inertia('/admin', 'Admin/AdminDashboard')->name('admin.dashboard');

Route::get('/api/cars', [CarController::class, 'getCars']);
Route::post('/api/cars', [CarController::class, 'store']);
Route::delete('/api/cars/{car}', [CarController::class, 'destroy']);
Route::put('/api/cars/{car}', [CarController::class, 'update']);

Route::inertia('/ferrari', 'User/Ferrari')->name('ferrari');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
});

Route::get('/admin/cars', [CarController::class, 'index'])->name('admin.cars.index');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/purchases', [PurchaseController::class, 'index']);
    Route::post('/purchases', [PurchaseController::class, 'store']);
});



