<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
{
    // Menambahkan admin secara manual
    User::create([
        'username' => 'admin',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
    ]);
}
}
