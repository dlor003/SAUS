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
        Schema::create('personnel_activite', function (Blueprint $table) {
            $table->foreignId('personnel_id')->constrained('personnels')->onDelete('cascade'); // Clé étrangère vers personnels
            $table->foreignId('activity_id')->constrained('activite_individuals')->onDelete('cascade'); // Clé étrangère vers diplomes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
