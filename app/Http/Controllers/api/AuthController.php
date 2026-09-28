<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
public function register(RegisterRequest $request): JsonResponse
{
    $validatedData = $request->validated();

    $user = User::create([
        'name'     => $validatedData['name'],
        'email'    => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
        'role'     => $validatedData['role'] ?? "user",
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        "success" => true,
        'message' => 'compte creer avec succes', 
        "access_token" => $token,
        "token_type" => 'Bearer',
        'user' => $user, 
    ], 201);
}
#

    public function login(LoginRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json([
                "success" => false,
                'message' => 'Identifiants invalides',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "success" => true,
            'message' => 'connexion reussie',
            "access_token" => $token,
            "token_type" => 'Bearer',
            'user' => $user, 
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "success" => true,
            'message' => 'deconnexion reussie. et jeton révoqué',
        ], 200);
    }
}
