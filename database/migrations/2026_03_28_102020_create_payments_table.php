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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Le client
        $table->integer('guest_count'); // Nombre de personnes
        $table->dateTime('reservation_date'); // Date et Heure
        $table->string('table_number')->nullable(); // Optionnel : assigner une table
        $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
        $table->text('special_requests')->nullable(); // Notes (ex: allergie, anniversaire)
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
