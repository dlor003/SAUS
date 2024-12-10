<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VerificationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/personnel/{personId}', [HomeController::class, 'update']);
});

Route::post('/check-email', function (Request $request) {
    $emailExists = User::where('email', $request->email)->exists();
    return response()->json(['exists' => $emailExists]);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user']);

Route::post('/verify-district', [VerificationController::class, 'verifyDistrict']);
Route::post('/verify-commune', [VerificationController::class, 'verifyCommune']);
Route::post('/verify-fokontany', [VerificationController::class, 'verifyFokontany']);

Route::post('/INSCRIPTION-SAUS', [HomeController::class, 'store']);
Route::get('/sections', [HomeController::class, 'sections']);
