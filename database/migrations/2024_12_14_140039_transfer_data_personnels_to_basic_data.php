<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       // Parcours de chaque enregistrement de personnel pour le transférer vers basicData
       DB::table('personnels')->get()->each(function ($personnel) {
        DB::table('basic_data')->insert([
            'nom' => $personnel->nom,
            'prenom' => $personnel->prenom,
            'email' => $personnel->mail,
        ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('basic_data', function (Blueprint $table) {
                // Si besoin de rollback, on supprime les données ajoutées dans basicData
                DB::table('basicData')->truncate();
        });
    }
};
