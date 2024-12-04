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
        Schema::create('personne_type_membre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnal_id')->constrained('personnels')->onDelete('cascade'); // Relation avec la table personne
            $table->foreignId('type_membre_id')->constrained('type_membres'); // Relation avec la table type_membres
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personne_type_membre');
    }
};
