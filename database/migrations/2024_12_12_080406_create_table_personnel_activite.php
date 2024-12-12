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
        Schema::create('table_personnel_activite', function (Blueprint $table) {
            $table->foreignId('personnel_id')->constrained('personnels')->onDelete('cascade'); // Clé étrangère vers personnels
            $table->foreignId('activity_id')->constrained('diplomes')->onDelete('cascade'); // Clé étrangère vers diplomes
        });

        Schema::table('personnel_poles', function (Blueprint $table) {
            $table->renameColumn('diplome_id', 'poles_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_personnel_activite');
    }
};
