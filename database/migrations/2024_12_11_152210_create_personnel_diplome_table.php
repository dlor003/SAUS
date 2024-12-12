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
        Schema::create('personnel_diplome', function (Blueprint $table) {
            $table->foreignId('personnel_id')->constrained('personnels')->onDelete('cascade'); // Clé étrangère vers personnels
            $table->foreignId('diplome_id')->constrained('diplomes')->onDelete('cascade'); // Clé étrangère vers diplomes
            $table->timestamps(); // Colonnes pour suivre les dates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_diplome');
    }
};
