<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('car')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($purchases);
    }

    public function store(Request $request)
    {
        try {
            Log::info('Purchase request received:', $request->all());
            
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

            Log::info('Purchase created successfully:', ['purchase' => $purchase]);

            return response()->json([
                'message' => 'Purchase successful',
                'purchase' => $purchase
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating purchase:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to create purchase',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, Purchase $purchase)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $purchase->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Status updated successfully',
            'purchase' => $purchase
        ]);
    }
} 