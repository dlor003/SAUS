<?php

namespace App\Http\Controllers;

use App\Models\BasicData;
use App\Models\Diplome;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Login function
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        // Charger le personnel avec ses relations
        $personnelData = $user->personnel()->with([
            'diplomes',
            'section',
            'activiteIndividual',
            'polesRecherche',
            'typesMembres',
            'autresDiplomes'
        ])->first();

        // Vérifiez si le personnel est trouvé
        if (!$personnelData) {
            return response()->json([
                'message' => 'No personnel data found for this user.',
                'user' => $user,
                'token' => $token,
            ], 404);
        }

        // Inclure l'URL complète de l'image de profil
        $profilePictureUrl = $personnelData->profile_picture 
        ? asset('storage/' . $personnelData->profile_picture) 
        : null;

        // Structurer la réponse JSON
        return response()->json([
            'user' => $user,
            'token' => $token,
            'personnelData' => [
                'section' => $personnelData->section,
                'diplomes' => $personnelData->diplomes,
                'activity' => $personnelData->activiteIndividual,
                'polesSearch' => $personnelData->polesRecherche,
                'typesMembers' => $personnelData->typesMembres,
                'bodyData' => $personnelData,
                'basicData' => $personnelData->basicData,
                'profile_picture' => $profilePictureUrl // Inclure ici
            ],
        ]);
 
    }
    
    public function getUserProfile($id)
    {
        // Récupère un seul enregistrement correspondant à l'ID
        $personnelData = Personnel::where('id', $id)->first();
    
        if (!$personnelData) {
            // Gérer le cas où aucun enregistrement n'est trouvé
            return response()->json([
                'error' => 'Utilisateur non trouvé',
            ], 404);
        }
    
        // Génère l'URL de l'image de profil
        $profilePictureUrl = $personnelData->profile_picture 
            ? asset('storage/' . $personnelData->profile_picture) 
            : null;
    
        return response()->json([
            'personnelData' => [
                'section' => $personnelData->section,
                'diplomes' => $personnelData->diplomes,
                'activity' => $personnelData->activiteIndividual,
                'polesSearch' => $personnelData->polesRecherche,
                'typesMembers' => $personnelData->typesMembres,
                'bodyData' => $personnelData,
                'basicData' => $personnelData->basicData,
                'autrsDiplomes' => $personnelData->autresDiplomes,
                'profile_picture' => $profilePictureUrl,
            ],
        ]);
    }

     // Méthode pour débloquer un utilisateur
     public function unblockUser($userId)
     {
         // Vérifier si l'utilisateur existe dans la table basic_data
         $user = BasicData::where('id', $userId)->first();
 
         // Si l'utilisateur existe, mettre à jour le champ is_blocked à false
         if ($user) {
                $user->is_blocked = false;
                $user->district_attempts  = 0;
                $user->commune_attempts = 0;
                $user->fokontany_attempts = 0;
                $user->save();
                return response()->json(['message' => 'Utilisateur débloqué avec succès.'], 200);
         }
 
         // Si l'utilisateur n'existe pas, retourner un message d'erreur
         return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
     }
    


    public function registerBasicData (Request $request)
    {
    
        // Validation des données envoyées par React
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:basic_data,email',
        ]);

        // Enregistrement des données dans la table 'basic_data'
        $basicData = BasicData::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
        ]);

        // Réponse de succès
        return response()->json(['message' => 'Inscription réussie', 'data' => $basicData], 201);
    }


    // Logout function
    public function logout(Request $request, $tokenId)
    {
        Auth::logout();
        $request->tokens()->where('id', $tokenId)->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // Get authenticated user
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function AllUser()
    {
        $AllUser = BasicData::with('personnel')->get();

        return response()->json([
            'AllUsers' => $AllUser,
        ]);
    }

    public function AllAdmin()
    {
        $AllAdmin = User::with([
            'personnel:id,appelation', // Charger les colonnes nécessaires de Personnel
            'personnel.basicData'      // Charger les relations imbriquées de BasicData
        ])->where('roles', 'admin')->get();
    
        return response()->json([
            'AllAdmin' => $AllAdmin,
        ]);
    }
    



    // Registration function
    public function register(Request $request)
    {   
        try {
            // Validation des données
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'personnel_id' => 'required|exists:personnels,id',
            ]);
    
            // Création de l'utilisateur
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'personnel_id' => $validated['personnel_id'],
                'roles' => 'user',
            ]);
    
            return response()->json([
                'message' => 'Registration successful',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            // Log l'erreur pour plus de détails
            \Log::error('Erreur lors de l\'enregistrement : ' . $e->getMessage());
    
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
