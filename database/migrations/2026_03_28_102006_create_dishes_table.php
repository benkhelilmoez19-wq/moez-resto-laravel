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
        Schema::create('dishes', function (Blueprint $table) {
          $table->id();
        // Relation : Un plat appartient à une catégorie
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 8, 2); // ex: 15.50
        $table->string('image')->nullable();
        $table->boolean('is_available')->default(true); // Pour gérer les ruptures de stock
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};
