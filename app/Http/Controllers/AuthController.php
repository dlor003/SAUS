<?php

namespace App\Http\Controllers;

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
                'bodyData' => $personnelData
            ],
        ]);
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
