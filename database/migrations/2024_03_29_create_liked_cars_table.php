<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('liked_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Membuat unique constraint agar user tidak bisa like mobil yang sama lebih dari sekali
            $table->unique(['user_id', 'car_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('liked_cars');
    }
}; 