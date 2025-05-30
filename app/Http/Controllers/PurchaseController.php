<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'price' => 'required|numeric'
        ]);

        $purchase = Purchase::create([
            'user_id' => Auth::id(),
            'car_id' => $request->car_id,
            'price' => $request->price,
            'status' => 'pending',
            'purchase_date' => now()
        ]);

        return response()->json([
            'message' => 'Purchase successful',
            'purchase' => $purchase
        ], 201);
    }
} 