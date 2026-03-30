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
        Schema::create('orders', function (Blueprint $table) {
           $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->decimal('total_price', 10, 2); // Somme totale
        $table->enum('status', ['pending', 'preparing', 'shipped', 'delivered', 'cancelled'])->default('pending');
        $table->string('delivery_address')->nullable(); // Si différent de l'adresse profil
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
