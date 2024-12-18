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
        Schema::create('demandes', function (Blueprint $table) {
            $table->id(); // Crée une colonne 'id' de type BIGINT(20) UNSIGNED avec AUTO_INCREMENT
            $table->foreignId('personnel_id')->constrained('personnels')->onDelete('cascade'); // L'ID du personnel
            $table->enum('type_demande', ['adhesion', 'cotisation']); // Le type de demande
            $table->text('message'); // Le message de la demande
            $table->enum('status', ['en_attente', 'traitee', 'rejete'])->default('en_attente'); // Le statut de la demande
            $table->text('admin_message')->nullable(); // Message de l'administrateur (nullable)
            $table->timestamps(); // Crée les colonnes 'created_at' et 'updated_at' automatiquement
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
