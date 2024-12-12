<?php

namespace Database\Seeders;

use App\Models\ActiviteIndividual;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activity = [
            'Étudiant',
            "Retraité",
            "Recherche emploi",
            "Salarié",
            "Autres"
        ];

        foreach($activity as $active){
            ActiviteIndividual::create(['nom' => $active]);            
        }
    }
}
