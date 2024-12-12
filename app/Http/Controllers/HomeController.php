<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\Fokontany;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Personnel;
use App\Models\Diplome;
use App\Models\PoleRecherche;
use App\Models\ActiviteIndividual;
use App\Models\Commune;
use App\Models\District;
use App\Models\TypeMembre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function update(Request $request, $personId)
        {
            \Log::info('ID reçu pour la mise à jour :', ['id' => $personId]);

            // Trouver l'enregistrement personnel
            $personnel = Personnel::findOrFail($personId);

            // Valider les données de personnelData
            if ($request->has('personnelData')) {
                $validatedPersonnelData = $request->validate([
                    'personnelData.nom' => 'sometimes|string|max:255',
                    'personnelData.prenom' => 'sometimes|string|max:255',
                    'personnelData.appelation' => 'sometimes|string|max:255',
                    'personnelData.phone' => 'sometimes|string|max:15',
                    'personnelData.mail' => 'sometimes|email|max:255',
                    'personnelData.date_naissance' => 'sometimes|date',
                    'personnelData.date_inscription' => 'sometimes|date',
                    'personnelData.genre' => 'sometimes|string|max:50',
                    'personnelData.adresse' => 'sometimes|string|max:255',
                    'personnelData.nationalite' => 'sometimes|string|max:100',
                    'personnelData.section_id' => 'sometimes|exists:sections,id',
                ]);

                // Mettre à jour l'enregistrement personnel
                $personnel->update($validatedPersonnelData['personnelData']);
            }

            // Traitement des diplômes
            if ($request->has('diplomes')) {
                // Récupérer les diplômes depuis la requête
                $diplomes = $request->input('diplomes');

                // Valider les diplômes
                $validatedDiplomes = $request->validate([
                    'diplomes.*.nom' => 'required|string|max:255',
                ]);

                // Insérer chaque diplôme dans la base de données et l'associer au personnel
                foreach ($diplomes as $diplome) {
                    // Créer un nouveau diplôme et associer à ce personnel
                    Diplome::create([
                        'nom' => $diplome['nom'],
                        'personnel_id' => $personnel->id, // Associer le diplôme au personnel
                    ]);
                }
            }

            // Répondre avec une réponse appropriée// Retourner les données mises à jour
                return response()->json([
                    'message' => 'Données mises à jour avec succès.',
                    'personnel' => $personnel->fresh()->load('section', 'diplomes'), // Utilise `fresh` pour récupérer les données actualisées
                ]);
        }

    public function sondage(Request $request){
        // Validation des données d'entrée
        $request->validate([
            'district' => 'required|string',
            'commune' => 'required|string',
            'fokontany' => 'required|string',
            // Ajoutez d'autres règles de validation pour l'inscription
        ]);

        // Vérifier si le district existe
        $district = District::where('name', $request->district)->first();
        if (!$district) {
            return redirect()->back()->withErrors(['district' => 'Le district spécifié n\'existe pas.']);
        }

        // Vérifier si la commune existe dans ce district
        $commune = Commune::where('name', $request->commune)
                         ->where('district_id', $district->id)
                         ->first();
        if (!$commune) {
            return redirect()->back()->withErrors(['commune' => 'La commune spécifiée n\'existe pas dans ce district.']);
        }

        // Vérifier si le fokontany existe dans cette commune
        $fokontany = Fokontany::where('name', $request->fokontany)
                             ->where('commune_id', $commune->id)
                             ->first();
        if (!$fokontany) {
            return redirect()->back()->withErrors(['fokontany' => 'Le fokontany spécifié n\'existe pas dans cette commune.']);
        }


        return redirect()->route('home')->with('success', 'Inscription réussie !');

    }
    public function allData() {
        $sections = Section::all();
        $diplomes = Diplome::all();
        $activity = ActiviteIndividual::all();
        $poles    = PoleRecherche::all();

        return [
            'sections' => $sections,
            'diplomes' => $diplomes,
            'activity' => $activity,
            'poles'    => $poles
        ]; 
    }
    
    public function store(Request $request)
    {
        Log::info('Donnees recu :', ['data' => $request]);
        try {
            // Validation des données
            $request->validate([
                'appelation' => 'required|string|max:255',
                'Nom' => 'required|string|max:255',
                'Prenom' => 'required|string|max:255',
                'date_naissance' => 'required|date',
                'genre' => 'required|string|max:50',
                'adress' => 'required|string|max:255',
                'nationalite' => 'required|string|max:100',
                'telephone' => 'required|string|max:15',
                'email' => 'required|email|max:255',
                'diplomes' => 'required|array',
                'autreDiplomes' => 'nullable|string|max:255',
                'poles' => 'nullable|array',
                'activity' => 'required|string|max:100',
                'domain' => 'required|string|max:100',
                'date_inscription' => 'required|date',
                'membre_Actif' => 'required|boolean',
                'membre_sympathisant' => 'required|boolean',
                'section' => "required|exists:sections,id",
                'profile_picture' => 'sometimes|image|mimes:jpg,jpeg,png,gif|max:2048', // Validation de l'image

            ]);
            $personnelId = null;
    
            DB::transaction(function () use ($request, &$personnelId) {
                // Étape 1 : Enregistrer la personne dans la table `personnels`
                $personne = Personnel::create([
                    'appelation' => $request->input('appelation'),
                    'nom' => $request->input('Nom'),
                    'prenom' => $request->input('Prenom'),
                    'date_naissance' => $request->input('date_naissance'),
                    'genre' => $request->input('genre'),
                    'adresse' => $request->input('adress'),
                    'nationalite' => $request->input('nationalite'),
                    'date_inscription' => $request->input('date_inscription'),
                    'phone' => $request->input('telephone'),
                    'mail' => $request->input('email'),
                    'section_id' => $request->input('section'),
                ]);
                // Si une photo est envoyée, on la stocke
                if ($request->hasFile('profile_picture')) {
                    $path = $request->file('profile_picture')->store('public/profile_pictures');
                    $personne->profile_picture = $path;
                    $personne->save();
                }
                

                $personnelId = $personne->id;
    
                // Étape 2 : Déterminer les types de membres à associer
                $typeIds = [];
                if ($request->input('membre_Actif')) {
                    $typeActif = TypeMembre::firstOrCreate(['type' => 'actif']);
                    $typeIds[] = $typeActif->id;
                }
                if ($request->input('membre_sympathisant')) {
                    $typeSympathisant = TypeMembre::firstOrCreate(['type' => 'sympathisant']);
                    $typeIds[] = $typeSympathisant->id;
                }
    
                // Associer les types de membres dans la table pivot
                $personne->typesMembres()->sync($typeIds);
    
                // Associer les diplômes dans la table pivot `personnel_diplome`
                if ($request->input('diplomes')) {
                    $personne->diplomes()->sync($request->input('diplomes'));
                }
                
                // Associer les diplômes dans la table pivot `personnel_diplome`
                if ($request->input('poles')) {
                    // Les IDs des pôles
                    $poles = $request->input('poles');
                    
                    // Synchronisation sans données supplémentaires
                    $personne->polesRecherche()->sync($poles);
                }
                

                //ajouter une domaine et sont activite
                if ($request->input('activity')) {
                   // Assurez-vous que `activity` est un tableau
                    $activities = is_array($request->input('activity')) ? $request->input('activity') : [$request->input('activity')];
                    $domain = $request->input('domain');
                    // Synchronisation avec les activités
                    $syncData = [];
                    foreach ($activities as $activityId) {
                        $syncData[$activityId] = ['domain' => $domain]; // Appliquer la même valeur de `domain`
                    }
                    $personne->activiteIndividual()->sync($syncData);

                }

            });
    
            return response()->json([
                'success' => 'success',
                'message' => 'Données enregistrées avec succès',
                'id'      => $personnelId
                
            ], 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Retourner les erreurs de validation en JSON
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
    
        } catch (\Exception $e) {
            // Retourner une réponse en cas d'erreur
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'enregistrement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
