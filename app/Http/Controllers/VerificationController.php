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

    public function verifyFokontany(Request $request)
    {
        $request->validate([
            'fokontany' => 'required|string',
            'commune' => 'required|string'
        ]);

        $commune = Commune::where('name', $request->commune)->first();
        if (!$commune) {
            return response()->json(['exists' => false], 404);
        }

        $exists = Fokontany::where('name', $request->fokontany)
                           ->where('commune_id', $commune->id)
                           ->exists();

        return response()->json(['exists' => $exists]);
    }
}
