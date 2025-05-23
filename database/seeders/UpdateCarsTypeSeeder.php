<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Car;

class UpdateCarsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update semua mobil yang belum memiliki tipe
        Car::whereNull('type')->orWhere('type', '')->update(['type' => 'sport']);
    }
}
