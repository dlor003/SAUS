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
        Schema::table('diplomes', function (Blueprint $table) {
            $table->dropForeign(['personnel_id']); // Supprime la clé étrangère
            $table->dropColumn('personnel_id'); // Supprime la colonne
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diplomes', function (Blueprint $table) {
            //
        });
    }
};
