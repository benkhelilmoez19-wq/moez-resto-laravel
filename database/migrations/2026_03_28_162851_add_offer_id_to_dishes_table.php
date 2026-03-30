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
        Schema::table('dishes', function (Blueprint $table) {
            // 1. Ajout de la colonne offer_id (nullable car un plat n'a pas forcément de promo)
            // 2. constrained('offers') indique que la clé vient de la table 'offers'
            // 3. onDelete('set null') évite de supprimer le plat si l'offre est supprimée
            $table->foreignId('offer_id')
                  ->nullable()
                  ->after('category_id') 
                  ->constrained('offers')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dishes', function (Blueprint $table) {
            // On supprime d'abord la contrainte de clé étrangère
            $table->dropForeign(['offer_id']);
            // Ensuite on supprime la colonne
            $table->dropColumn('offer_id');
        });
    }
};