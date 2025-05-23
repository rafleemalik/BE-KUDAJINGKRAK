<?php

namespace App\Http\Controllers\Admin;

use App\Models\Car;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Storage;


class CarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $cars = Car::query()
            ->when($search, function ($query, $search) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
            })
            ->get();
    
        return Inertia::render('Admin/AdminDashboard', [
            'cars' => $cars,
            'filters' => [
                'search' => $search
            ]
        ]);
    }

    // Ambil data mobil untuk API
    public function getCars()
    {
        return response()->json(Car::all());
    }

    // Menyimpan mobil baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5000',
            'specifications' => 'required',
            'type' => 'required|in:sport,hybrid,gt,suv',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('car_images', 'public');
        }

        $car = Car::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath,
            'specifications' => $request->specifications,
            'type' => $request->type,
        ]);

        return response()->json([
            'message' => 'Car created successfully!',
            'car' => $car
        ], 201);

        dd($request->all(), $request->file('image'));
    }

    // Menghapus mobil
    public function destroy(Car $car)
    {
        $car->delete();
        return response()->json(['message' => 'Car deleted successfully']);
    }

    // Update mobil
    public function update(Request $request, Car $car)
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5000',
            'specifications' => 'required',
            'type' => 'required|in:sport,hybrid,gt,suv',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($car->image) {
                Storage::delete('public/' . $car->image);
            }
            
            // Store new image
            $imagePath = $request->file('image')->store('car_images', 'public');
            $car->image = $imagePath;
        }

        // Update other fields
        $car->name = $request->name;
        $car->price = $request->price;
        $car->specifications = $request->specifications;
        $car->type = $request->type;
        $car->save();

        return response()->json([
            'message' => 'Car updated successfully!',
            'car' => $car
        ]);
    }

    
}
