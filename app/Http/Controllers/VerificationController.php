<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\District;
use App\Models\Fokontany;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verifyDistrict(Request $request)
    {
        $request->validate(['district' => 'required|string']);
        $exists = District::where('name', $request->district)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function verifyCommune(Request $request)
    {
        $request->validate([
            'commune' => 'required|string',
            'district' => 'required|string'
        ]);

        $district = District::where('name', $request->district)->first();
        if (!$district) {
            return response()->json(['exists' => false], 404);
        }

        $exists = Commune::where('name', $request->commune)
                         ->where('district_id', $district->id)
                         ->exists();

        return response()->json(['exists' => $exists]);
    }

        public function dataToVerified() {
            $district = District::all();
            $commune = Commune::all();
            $fokontany = Fokontany::all();

            return  [
                'districts' => $district,
                'communes' => $commune,
                'fokontanys' => $fokontany
            ];
        }

        public function verifyFokontany(Request $request)
        {
            // Validation des entrées
            $request->validate([
                'fokontany' => 'required|string',
                'commune' => 'required|string',
                'fokontany_commune_id' => 'required|integer', // Validation de l'ID de la commune
            ]);

            // Recherche de la commune par le nom
            $commune = Commune::where('name', $request->commune)->first();

            // Vérification si la commune envoyée existe dans la base de données
            if ($commune) {
                // Si l'ID de la commune envoyée correspond à l'ID du fokontany_commune_id
                if ($commune->id == $request->fokontany_commune_id) {
                   // Si l'ID correspond, le fokontany existe dans cette commune, on retourne true directement
                    return response()->json(['exists' => true]);
                }else{
                    // Vérification si le fokontany existe dans la commune "ANTSOHIHY"
                    $commune = Fokontany::where('name', $request->fokontany)
                                                ->where('commune_id', $commune->id)
                                                ->exists();
                    // Si aucune des deux communes n'est trouvée, on renvoie false
                    return response()->json(['exists' => false]);
                }
            }

        }

}



