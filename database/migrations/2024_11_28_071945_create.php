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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('communes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('fokotanys', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('commune_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id(); // ID unique pour chaque pole de recherche
            $table->string('nom'); // Nom du pole de recherche, nullable
        });

        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('appelation');
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->string('genre');
            $table->string('other');
            $table->string('adresse');
            $table->string('nationalite');
            $table->integer('phone');
            $table->string('mail');
            $table->foreignId('section_id')->constrained('personnels')->onDelete('cascade'); // Relation avec la table personnels
            $table->date('date_inscription');
            $table->timestamps();
        });

        Schema::create('diplomes', function (Blueprint $table) {
            $table->id(); // ID unique pour chaque diplôme
            $table->string('nom'); // Nom du diplôme (par exemple, "Baccalauréat", "Master")
            $table->foreignId('personnel_id')->constrained('personnels')->onDelete('cascade'); // Relation avec la table personnels
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });

        Schema::create('poles_recherches', function (Blueprint $table) {
            $table->id(); // ID unique pour chaque pole de recherche
            $table->string('nom'); // Nom du pole de recherche, nullable
            $table->foreignId('personnel_id')->constrained('personnels')->onDelete('cascade'); // Référence à personnel, nullable
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });

        Schema::create('activite_individuals', function (Blueprint $table) {
            $table->id(); // ID unique pour chaque pole de recherche
            $table->string('nom'); // Nom du pole de recherche, nullable
            $table->foreignId('personnel_id')->constrained('personnels')->onDelete('cascade'); // Référence à personnel, nullable
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::dropIfExists(table: 'personnels');
        Schema::dropIfExists('diplomes');
        Schema::dropIfExists('poles_recherche');
    }
};
