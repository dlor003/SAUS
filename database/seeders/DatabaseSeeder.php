<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Commune;
use App\Models\District;
use App\Models\Fokontany;
use App\Models\Section;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Génère 5 districts, chacun avec 3 communes, et chaque commune avec 4 fokontanies
        // District::factory(5)
        //     ->has(
        //         Commune::factory(3)
        //             ->has(Fokontany::factory(4))
        //     )
        //     ->create();
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // Insérer 8 provinces
        // $this->call([
        //     SectionSeeder::class,
        // ]);
        // $this->call(PolesSeeders::class);
        $this->call(ActivitySeeder::class);

    }
}
