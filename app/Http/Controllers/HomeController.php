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
use App\Models\User;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

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
                'poles.*.value' => 'required|string|max:255',
                'profession' => 'required|string|max:100',
                'domain' => 'required|string|max:100',
                'date_inscription' => 'required|date',
                'membre_Actif' => 'required|boolean',
                'membre_sympathisant' => 'required|boolean',
                'section' => "required|exists:sections,id"
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
    
                // Étape 3 : Ajouter les diplômes dans la table `diplomes`
                foreach ($request->input('diplome') as $diplome) {
                    Diplome::create([
                        'personnel_id' => $personne->id,
                        'nom' => $diplome,
                    ]);
                }
    
                if ($request->input('poles')) {
                    Log::info('Nombre de pôles reçus : ' . count($request->input('poles')));
                
                    foreach ($request->input('poles') as $pole) {
                        Log::info('Traitement du pôle : ', $pole);
                
                        if (!empty($pole['value'])) {
                            PoleRecherche::create([
                                'personnel_id' => $personne->id,
                                'nom' => $pole['value'],
                            ]);
                        } else {
                            Log::warning('Pôle invalide : ', $pole);
                        }
                    }
                } else {
                    Log::info('Aucun pôle reçu.');
                }

                // Étape 5 : Ajouter l'activité individuelle
                ActiviteIndividual::create([
                    'personnel_id' => $personne->id,
                    'nom' => $request->input('profession'), // Le nom de l'activité est la profession
                    'domain' => $request->input('domain'), // Domaine de l'activité
                ]);


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
