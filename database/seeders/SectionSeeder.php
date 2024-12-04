<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Liste des provinces
         $provinces = [
            'Antananarivo', 'Fianarantsoa', 'Mahajanga',
            'Toamasina', 'Toliara', 'Antsiranana'
        ];

        // Boucle pour insérer chaque province dans la table
        foreach ($provinces as $province) {
            Section::create(['nom' => $province]);
        }
    }
}
