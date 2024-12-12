<?php

namespace Database\Seeders;

use App\Models\Diplome;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiplomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $diplomes = ["Premier cycle - Licence", "Deuxième cycle - Master", "Troisième cycle - Doctorat ou plus"];

        foreach ($diplomes as $name) {
            Diplome::create(['nom' => $name]);
        }
    }
}
