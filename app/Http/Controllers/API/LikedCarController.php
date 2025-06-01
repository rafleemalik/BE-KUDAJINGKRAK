<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LikedCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LikedCarController extends Controller
{
    /**
     * Toggle like/unlike untuk mobil
     * 
     * Jika mobil sudah di-like, akan di-unlike
     * Jika mobil belum di-like, akan di-like
     * 
     * Validasi input:
     * - car_id: harus ada dan harus ada di tabel cars
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleLike(Request $request)
    {
        try {
            // Validasi input car_id
            $request->validate([
                'car_id' => 'required|exists:cars,id'
            ]);

            // Cek apakah user sudah login
            $userId = Auth::id();
            if (!$userId) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            $carId = $request->car_id;

            // Cek apakah mobil sudah di-like
            $likedCar = LikedCar::where('user_id', $userId)
                               ->where('car_id', $carId)
                               ->first();

            if ($likedCar) {
                // Jika sudah di-like, hapus dari liked cars
                $likedCar->delete();
                return response()->json(['message' => 'Car unliked successfully', 'liked' => false]);
            }

            // Jika belum di-like, tambahkan ke liked cars
            LikedCar::create([
                'user_id' => $userId,
                'car_id' => $carId
            ]);

            return response()->json(['message' => 'Car liked successfully', 'liked' => true]);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Error in toggleLike: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while processing your request'], 500);
        }
    }

    /**
     * Mengambil semua mobil yang di-like oleh user yang sedang login
     * 
     * Mengambil relasi car untuk mendapatkan detail mobil
     * Hanya mengambil data mobil (pluck('car'))
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLikedCars()
    {
        try {
            // Cek apakah user sudah login
            $userId = Auth::id();
            if (!$userId) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            // Ambil semua mobil yang di-like
            $likedCars = LikedCar::with('car')
                                ->where('user_id', $userId)
                                ->get()
                                ->pluck('car');

            return response()->json($likedCars);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Error in getLikedCars: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while fetching liked cars'], 500);
        }
    }
} 