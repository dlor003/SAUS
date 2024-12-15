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
        Schema::table('districts', function (Blueprint $table) {
            $table->string('code')->unique(); // Code du district
        });
        Schema::table('communes', function (Blueprint $table) {
            $table->string('code')->unique(); // Code du district
        });
        Schema::table('fokotanys', function (Blueprint $table) {
            $table->string('code')->unique(); // Code du district
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
