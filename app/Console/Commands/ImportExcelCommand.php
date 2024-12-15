<?php

namespace App\Console\Commands;

use App\Models\Commune;
use App\Models\District;
use App\Models\Fokontany;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory; // Ajout de l'import correct !
use Illuminate\Support\Facades\DB; // Import nécessaire

class ImportExcelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:excel {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from an Excel file into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $filePath = $this->argument('file'); // Chemin du fichier passé en argument

            // Charger le fichier Excel
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, true, true, true);
    
            // Démarrer une transaction
            DB::beginTransaction();
    
            foreach ($data as $index => $row) {
                if ($index === 1) continue; // Ignorer les en-têtes
    
                // Récupérer les colonnes
                $codeDist = $row['C'];
                $districtName = $row['E'];
                $codeCom = $row['F'];
                $communeName = $row['G'];
                $codeFkt = $row['H'];
                $fktName = $row['I'];
    
                // === Étape 1 : Insérer ou vérifier le district ===
                $district = District::firstOrCreate(
                    ['code' => $codeDist],
                    ['name' => $districtName]
                );
    
                // === Étape 2 : Insérer ou vérifier la commune ===
                $commune = Commune::firstOrNew(['code' => $codeCom]);
                $commune->name = $communeName;
                $commune->district_id = $district->id;
                $commune->save();
    
                // === Étape 3 : Insérer ou vérifier le fokontany ===
                Fokontany::firstOrCreate(
                    [
                        'name' => $fktName,
                        'commune_id' => $commune->id
                    ],
                    [
                        'code' => $codeFkt
                    ]
                );
            }
    
            // Si tout est OK, on valide la transaction
            DB::commit();
    
            $this->info('Importation terminée avec succès !');

        }  catch (\Exception $e) {
            $this->error('Erreur : ' . $e->getMessage());
        }
    }
}
