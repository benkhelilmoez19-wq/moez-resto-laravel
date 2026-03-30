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
        Schema::create('offers', function (Blueprint $table) {
           $table->id();
        $table->string('name'); // Nom de l'offre (ex: "Promo Été")
        $table->string('code')->unique()->nullable(); // Code à saisir (ex: SUMMER24)
        
        // Type de remise : 'percentage' (%) ou 'fixed' (€)
        $table->enum('type', ['percentage', 'fixed'])->default('percentage');
        $table->decimal('value', 8, 2); // Le montant (ex: 10.00)
        
        // Conditions d'application
        $table->decimal('min_order_amount', 8, 2)->default(0); // Montant mini pour activer
        $table->dateTime('start_date'); // Début de l'offre
        $table->dateTime('end_date'); // Fin de l'offre
        
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
