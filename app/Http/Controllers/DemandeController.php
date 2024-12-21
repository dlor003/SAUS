<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\Demandes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            'id' => "required|exists:personnels,id",
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validation du fichier
        ]);

        // Traitement de l'image (enregistrement de l'image dans le dossier 'public/preuve_paiement')
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('preuve_paiement', 'public'); // Stockage de l'image
        }

        // Génération de l'URL de l'image (même logique que pour le profil)
        $profilePictureUrl = $filePath ? asset('storage/' . $filePath) : null;


        // Créer la demande
        $demande = Demandes::create([
            'personnel_id' => $request->id,
            'type_demande' => $request->type_demande,
            'message' => $request->message,
            'status' => 'en_attente',
            'preuve_picture_demandes' => $profilePictureUrl,
        ]);

        // Envoyer un message de confirmation ou autre
        return response()->json(['message' => 'Demande envoyée avec succès!'], 200);
    }

    // Méthode pour marquer une demande comme "traitee"
    public function traiter($id)
    {
        \Log::info('Méthode traiter appelée', ['id' => $id]);
    
        // Récupération de la demande ou erreur 404
        $demande = Demandes::findOrFail($id);
    
        \Log::info('Demande récupérée', ['demande' => $demande]);
    
        // Mise à jour du statut de la demande
        $demande->status = 'traitee';
        $demande->save();
    
        \Log::info('Demande mise à jour', ['status' => $demande->status]);
    
        // Création automatique de la cotisation
        $cotisation = Cotisation::create([
            'personnel_id' => $demande->personnel_id,
            'name' => $demande->type_demande,
            'date_payment' => now(),
            'personnel_id' => $demande->personnel_id,
            'preuve_picture' => $demande->preuve_picture_demandes,
        ]);
    
        \Log::info('Cotisation créée', ['cotisation' => $cotisation]);
    
        return response()->json([
            'message' => 'Demande marquée comme traitée et cotisation créée.',
            'demande' => $demande,
            'cotisation' => $cotisation,
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
