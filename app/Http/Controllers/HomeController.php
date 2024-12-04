<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Personnel;
use App\Models\Diplome;
use App\Models\PoleRecherche;
use App\Models\ActiviteIndividual;
use App\Models\TypeMembre;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{

    public function sections() {
        $sections = Section::all();

        return ['section' => $sections];
    }
    
    public function store(Request $request)
    {
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
                'diplome' => 'required|array',
                'autreDiplome' => 'nullable|string|max:255',
                'poles' => 'nullable|array',
                'profession' => 'required|string|max:100',
                'domain' => 'required|string|max:100',
                'date_inscription' => 'required|date',
                'membre_Actif' => 'required|boolean',
                'membre_sympathisant' => 'required|boolean',
                'section' => "required|exists:sections,id"
            ]);
    
            DB::transaction(function () use ($request) {
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
    
                // Étape 3 : Ajouter les diplômes dans la table `diplomes`
                foreach ($request->input('diplome') as $diplome) {
                    Diplome::create([
                        'personnel_id' => $personne->id,
                        'nom' => $diplome,
                    ]);
                }
    
                // Étape 4 : Ajouter les pôles dans `poles_recherche`
                if ($request->input('poles')) {
                    foreach ($request->input('poles') as $pole) {
                        // Vérifiez si 'name' est défini et non vide
                        if (!empty($pole['name'])) {
                            PoleRecherche::create([
                                'personnel_id' => $personne->id,
                                'nom' => $pole['name'], // 'nom' est obligatoire
                            ]);
                        }
                    }
                }

                // Étape 5 : Ajouter l'activité individuelle
                ActiviteIndividual::create([
                    'personnel_id' => $personne->id,
                    'nom' => $request->input('profession'), // Le nom de l'activité est la profession
                    'domain' => $request->input('domain'), // Domaine de l'activité
                ]);


            });
    
            return response()->json([
                
                'message' => 'Données enregistrées avec succès',
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
