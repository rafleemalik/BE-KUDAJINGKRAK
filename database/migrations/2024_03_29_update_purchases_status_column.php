<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Drop kolom status jika sudah ada
            if (Schema::hasColumn('purchases', 'status')) {
                $table->dropColumn('status');
            }
            
            // Tambah kolom status baru
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}; 