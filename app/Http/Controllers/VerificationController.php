<?php

namespace App\Http\Controllers;

use App\Models\BasicData;
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
            'district' => 'required|string',
            'id' => 'required|exists:basic_data,id', // Vérifie que l'utilisateur existe
        ]);
    
        // Récupérer l'utilisateur
        $user = BasicData::where('id', $request->id)->first();
    
        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }
    
        // Vérifier si le district existe
        $district = District::where('name', $request->district)->first();
        if (!$district) {
            return response()->json(['exists' => false], 404);
        }
    
        // Vérifier si la commune existe dans le district
        $exists = Commune::where('name', $request->commune)
                        ->where('district_id', $district->id)
                        ->exists();
    
        if (!$exists) {
            // Incrémenter le compteur des tentatives pour la commune
            $user->increment('commune_attempts'); // Utilisation correcte du champ
    
            // Si le nombre de tentatives atteint 3, bloquer l'utilisateur
            if ($user->commune_attempts >= 3) {
                $user->update(['is_blocked' => true]);
                return response()->json(['exists' => false, 'blocked' => true]);
            }
    
            return response()->json(['exists' => false, 'attempts' => $user->commune_attempts]);
        }
    
        // Si la commune est valide, réinitialiser le compteur des tentatives
        $user->update(['commune_attempts' => 0]);
    
        return response()->json(['exists' => true]);
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
                    'fokontany_commune_id' => 'required|integer',
                    'id' => 'required|exists:basic_data,id'
                ]);

                // Récupérer l'utilisateur (on suppose que l'email est transmis dans la requête pour identifier l'utilisateur)
                $user = BasicData::where('id', $request->id)->first();

                if (!$user) {
                    return response()->json(['error' => 'Utilisateur non trouvé'], 404);
                }

                // Recherche de la commune par le nom
                $commune = Commune::where('name', $request->commune)->first();

                if ($commune) {
                    // Si l'ID correspond, le fokontany existe dans cette commune
                    if ($commune->id == $request->fokontany_commune_id) {
                        return response()->json(['exists' => true]);
                    } else {
                        // Vérification si le fokontany existe dans la commune
                        $fokontanyExists = Fokontany::where('name', $request->fokontany)
                                                    ->where('commune_id', $commune->id)
                                                    ->exists();

                        if (!$fokontanyExists) {
                            // Si le fokontany est incorrect, incrémenter les tentatives
                            $user->increment('fokontany_attempts');

                            // Si le nombre de tentatives atteint 3, bloquer l'utilisateur
                            if ($user->fokontany_attempts >= 3) {
                                $user->update(['is_blocked' => true]);
                                return response()->json(['exists' => false, 'blocked' => true]);
                            }

                            return response()->json(['exists' => false, 'attempts' => $user->fokontany_attempts]);
                        }

                        return response()->json(['exists' => false]);
                    }
                }

                return response()->json(['exists' => false]);
            }


}



