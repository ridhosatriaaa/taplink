<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qr_tags', function (Blueprint $table) {
            $table->string('id')->primary(); // ID Papan, misal: 001
            $table->string('activation_pin')->nullable(); // PIN Rahasia (bisa di-hash, bukan 4 digit raw)
            $table->string('place_id')->nullable(); // Google Place ID toko
            $table->string('whatsapp_number')->nullable(); // No WA toko
            $table->boolean('is_active')->default(false); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_tags');
    }
};
