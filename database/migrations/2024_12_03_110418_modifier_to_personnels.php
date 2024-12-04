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
       // Assurez-vous qu'il n'y a pas de données invalides avant d'ajouter la clé étrangère
       Schema::table('personnels', function (Blueprint $table) {
            // Si la contrainte existe déjà, supprimez-la d'abord
            $table->dropForeign(['section_id']);
            
            // Ajout de la nouvelle contrainte de clé étrangère
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Si vous souhaitez pouvoir annuler la migration
        Schema::table('personnels', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
        });
    }
};
