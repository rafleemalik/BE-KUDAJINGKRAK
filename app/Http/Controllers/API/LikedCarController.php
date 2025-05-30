<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LikedCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LikedCarController extends Controller
{
    public function toggleLike(Request $request)
    {
        try {
            $request->validate([
                'car_id' => 'required|exists:cars,id'
            ]);

            $userId = Auth::id();
            if (!$userId) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            $carId = $request->car_id;

            $likedCar = LikedCar::where('user_id', $userId)
                               ->where('car_id', $carId)
                               ->first();

            if ($likedCar) {
                $likedCar->delete();
                return response()->json(['message' => 'Car unliked successfully', 'liked' => false]);
            }

            LikedCar::create([
                'user_id' => $userId,
                'car_id' => $carId
            ]);

            return response()->json(['message' => 'Car liked successfully', 'liked' => true]);
        } catch (\Exception $e) {
            Log::error('Error in toggleLike: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while processing your request'], 500);
        }
    }

    public function getLikedCars()
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            $likedCars = LikedCar::with('car')
                                ->where('user_id', $userId)
                                ->get()
                                ->pluck('car');

            return response()->json($likedCars);
        } catch (\Exception $e) {
            Log::error('Error in getLikedCars: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while fetching liked cars'], 500);
        }
    }
} 