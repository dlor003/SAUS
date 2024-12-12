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
        Schema::create('autres_diplomes', function (Blueprint $table) {
            $table->id(); // Colonne 'id' auto-incrémentée, clé primaire.
            $table->string("name"); // Colonne 'name' pour stocker le nom du diplôme.
            $table->foreignId('personnel_id') // Colonne pour la clé étrangère.
                  ->constrained('personnels') // Liaison avec la table 'personnels'.
                  ->onDelete('cascade'); // Suppression en cascade si un personnel est supprimé.
            $table->timestamps(); // Colonnes 'created_at' et 'updated_at'.
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autres_diplomes');
    }
};
