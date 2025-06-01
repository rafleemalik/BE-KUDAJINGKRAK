<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\PurchaseController;
use App\Http\Controllers\API\LikedCarController;

Route::inertia('/', 'Home')->name('home');

// Auth Routes
Route::get('/login', function () {
    return inertia('Auth/Login'); 
})->name('login');  

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/register', function () {
    return inertia('Auth/Register'); 
});
Route::post('/register', [AuthController::class, 'register']);

// Admin Routes
Route::inertia('/admin', 'Admin/AdminDashboard')->name('admin.dashboard');

// Car Admin Routes
Route::get('/admin/cars', [CarController::class, 'index']);
Route::post('/admin/cars', [CarController::class, 'store']);
Route::delete('/admin/cars/{car}', [CarController::class, 'destroy']);
Route::put('/admin/cars/{car}', [CarController::class, 'update']);

// User Routes
Route::inertia('/ferrari', 'User/Ferrari')->name('ferrari');
Route::get('/cars', [CarController::class, 'index']);

// Protected Routes
Route::middleware(['auth'])->group(function () {
    
    // Purchase Routes
    Route::get('/purchases', [PurchaseController::class, 'index']);
    Route::post('/purchases', [PurchaseController::class, 'store']);
    Route::put('/purchases/{purchase}/status', [PurchaseController::class, 'updateStatus']);
    
    // Liked Cars Routes
    Route::post('/liked-cars/toggle', [LikedCarController::class, 'toggleLike']);
    Route::get('/liked-cars', [LikedCarController::class, 'getLikedCars']);
});



