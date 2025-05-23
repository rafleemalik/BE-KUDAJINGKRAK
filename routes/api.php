<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CarController;

// Route untuk menambah mobil
Route::post('/cars', [CarController::class, 'store']);

// Route untuk mengambil semua mobil (opsional, jika ingin ada GET)
Route::get('/cars', [CarController::class, 'index']);
