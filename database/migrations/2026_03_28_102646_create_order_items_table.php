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
        Schema::create('order_items', function (Blueprint $table) {
           $table->id();
        // On lie l'item à la commande parente
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        
        // On lie l'item au plat spécifique
        $table->foreignId('dish_id')->constrained()->onDelete('cascade');
        
        // Informations au moment de la commande (importantes si le prix change plus tard)
        $table->integer('quantity')->default(1);
        $table->decimal('price', 8, 2); // Le prix unitaire au moment de l'achat
        
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
