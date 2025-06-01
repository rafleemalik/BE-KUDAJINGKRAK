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
    /**
     * Mengambil semua data pembelian user yang sedang login
     * Data diurutkan dari yang terbaru (descending)
     * Mengambil relasi car untuk mendapatkan detail mobil yang dibeli
     */
    public function index()
    {
        $purchases = Purchase::with('car')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($purchases);
    }

    /**
     * Membuat data pembelian baru
     * 
     * Validasi input:
     * - car_id: harus ada dan harus ada di tabel cars
     * - price: harus ada dan harus berupa angka
     * 
     * Data yang disimpan:
     * - user_id: ID user yang sedang login
     * - car_id: ID mobil yang dibeli
     * - price: harga mobil
     * - status: default 'pending'
     * - purchase_date: waktu pembelian
     */
    public function store(Request $request)
    {
        try {
            // Log data request yang diterima
            Log::info('Purchase request received:', $request->all());
            
            // Validasi input
            $request->validate([
                'car_id' => 'required|exists:cars,id',
                'price' => 'required|numeric'
            ]);

            // Buat data pembelian baru
            $purchase = Purchase::create([
                'user_id' => Auth::id(),
                'car_id' => $request->car_id,
                'price' => $request->price,
                'status' => 'pending',
                'purchase_date' => now()
            ]);

            // Log jika pembelian berhasil
            Log::info('Purchase created successfully:', ['purchase' => $purchase]);

            // Return response sukses
            return response()->json([
                'message' => 'Purchase successful',
                'purchase' => $purchase
            ], 201);

        } catch (\Exception $e) {
            // Log jika terjadi error
            Log::error('Error creating purchase:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return response error
            return response()->json([
                'message' => 'Failed to create purchase',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengupdate status pembelian
     * 
     * Validasi input:
     * - status: harus ada dan harus salah satu dari: pending, completed, cancelled
     * 
     * @param Request $request
     * @param Purchase $purchase Model binding untuk mencari data pembelian
     */
    public function updateStatus(Request $request, Purchase $purchase)
    {
        // Validasi status
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        // Update status pembelian
        $purchase->update([
            'status' => $request->status
        ]);

        // Return response sukses
        return response()->json([
            'message' => 'Status updated successfully',
            'purchase' => $purchase
        ]);
    }
} 