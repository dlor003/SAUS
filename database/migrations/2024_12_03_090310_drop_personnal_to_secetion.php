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
        Schema::table('sections', function (Blueprint $table) {
            // Vérifiez si la clé étrangère existe et supprimez-la
            if (Schema::hasColumn('sections', 'personnal_id')) {
                $table->dropForeign(['personnal_id']);
            }

            // Vérifiez si la colonne existe déjà, puis ajoutez-la si ce n'est pas le cas
            if (!Schema::hasColumn('sections', 'personnal_id')) {
                $table->foreignId('personnal_id')->constrained();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            // Supprimez la clé étrangère et la colonne dans la méthode down
            $table->dropForeign(['personnal_id']);
            $table->dropColumn('personnal_id');
        });
    }
};
