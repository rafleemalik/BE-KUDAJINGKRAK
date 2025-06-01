<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    // Get all cars
    public function index()
    {
        $cars = Car::all();
        return response()->json($cars);
    }

    // Get car by id
    public function show($id)
    {
        $car = Car::find($id);
        if (!$car) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mobil tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $car
        ]);
    }

    // Create new car
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'specifications' => 'required|string',
            'price' => 'required|numeric',
            'type' => 'required|string|in:sport,hybrid,gt,suv',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $imagePath = $request->file('image')->store('cars', 'public');

        $car = Car::create([
            'name' => $request->name,
            'specifications' => $request->specifications,
            'price' => $request->price,
            'type' => $request->type,
            'image' => $imagePath
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Mobil berhasil ditambahkan',
            'data' => $car
        ], 201);
    }

    // Update car
    public function update(Request $request, $id)
    {
        $car = Car::find($id);
        if (!$car) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mobil tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'specifications' => 'required|string',
            'price' => 'required|numeric',
            'type' => 'required|string|in:sport,hybrid,gt,suv',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'specifications' => $request->specifications,
            'price' => $request->price,
            'type' => $request->type
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cars', 'public');
        }

        $car->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Mobil berhasil diperbarui',
            'data' => $car
        ]);
    }

    // Delete car
    public function destroy($id)
    {
        $car = Car::find($id);
        if (!$car) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mobil tidak ditemukan'
            ], 404);
        }

        $car->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Mobil berhasil dihapus'
        ]);
    }

    // Get cars by type
    public function getByType($type)
    {
        $cars = Car::where('type', $type)->get();
        return response()->json([
            'status' => 'success',
            'data' => $cars
        ]);
    }
}
