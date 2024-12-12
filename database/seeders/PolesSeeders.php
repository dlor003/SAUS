<?php

namespace Database\Seeders;

use App\Models\PoleRecherche;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolesSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poles = [
            'Sciences Humaines, Sociales et du Langage',
            'Sciences de la Société',
            'Sciences du Vivant',
            'Sciences Agronomiques et Alimentaires',
            'Sciences de la Santé',
            'Sciences de l\'ingénierie et technologie',
            'Sciences de l\'éducation',
        ]; 
        foreach($poles as $pole)
        {
            PoleRecherche::create(['nom' => $pole]);
        }
    }
}
