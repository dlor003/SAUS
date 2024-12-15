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
        Schema::table('personnels', function (Blueprint $table) {
            $table->dropColumn(['nom', 'prenom', 'mail']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Si tu veux pouvoir annuler cette migration, il suffit de rajouter les colonnes
        Schema::table('personnels', function (Blueprint $table) {
            $table->string('nom');
            $table->string('prenom');
            $table->string('mail');
        });
    }
};
