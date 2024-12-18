<?php

namespace App\Http\Controllers;

use App\Models\Demandes;
use Illuminate\Http\Request;

class DemandeController extends Controller
{

    public function getDemandesByUser($userId)
    {
        // Récupérer toutes les demandes associées à l'utilisateur par son ID
        $demandes = Demandes::where('personnel_id', $userId)->get();

        // Vérifier si des demandes existent
        if ($demandes->isEmpty()) {
            return response()->json([
                'message' => 'Aucune demande trouvée pour cet utilisateur.'
            ], 201);
        }

        // Retourner la liste des demandes
        return response()->json($demandes);
    }

    public function getAllDemandes() {
        $demandes = Demandes::with('personnel')->get();
        return response()->json(
            ['demandes' => $demandes,]
        );
    }

    public function soumettreDemande(Request $request)
    {
        // Validation de la requête
        $request->validate([
            'type_demande' => 'required|in:adhesion,cotisation',
            'message' => 'required|string',
            'id' => "required|exists:personnels,id"
        ]);

        // Créer la demande
        $demande = Demandes::create([
            'personnel_id' => $request->id,
            'type_demande' => $request->type_demande,
            'message' => $request->message,
            'status' => 'en_attente',
        ]);

        // Envoyer un message de confirmation ou autre
        return response()->json(['message' => 'Demande envoyée avec succès!'], 200);
    }

    // Méthode pour marquer une demande comme "traitee"
    public function traiter($id)
    {
        $demande = Demandes::findOrFail($id); // Récupère la demande par ID ou lance une erreur 404 si elle n'existe pas
        $demande->status = 'traitee'; // Modifie le statut de la demande
        $demande->save(); // Sauvegarde les modifications dans la base de données

        return response()->json([
            'message' => 'Demande marquée comme traitée.',
            'demande' => $demande
        ]);
    }

    // Méthode pour marquer une demande comme "rejete"
    public function rejeter($id)
    {
        $demande = Demandes::findOrFail($id); // Récupère la demande par ID ou lance une erreur 404 si elle n'existe pas
        $demande->status = 'rejete'; // Modifie le statut de la demande
        $demande->save(); // Sauvegarde les modifications dans la base de données

        return response()->json([
            'message' => 'Demande marquée comme rejetée.',
            'demande' => $demande
        ]);
    }


}
