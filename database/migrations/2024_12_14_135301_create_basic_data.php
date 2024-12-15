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
        // Créer la table basic_data
        Schema::create('basic_data', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email');
            $table->integer('district_attempts')->default(0);
            $table->integer('commune_attempts')->default(0);
            $table->integer('fokontany_attempts')->default(0);
            $table->boolean('is_blocked')->default(false);
            $table->timestamps();
        });

        // Ajouter la colonne basic_data_id dans la table personnels
        Schema::table('personnels', function (Blueprint $table) {
            $table->unsignedBigInteger('basic_data_id')->nullable();  // Référence à basic_data
            $table->foreign('basic_data_id')->references('id')->on('basic_data')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            $table->dropForeign(['basic_data_id']);
            $table->dropColumn('basic_data_id');
        });

        Schema::dropIfExists('basic_data');
    }
};

