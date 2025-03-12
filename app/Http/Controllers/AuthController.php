<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilistaur; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilistaurs', // Ensure email is unique
            'password' => 'required|string|min:8', // Password must be at least 8 characters
        ]);

        
        $user = Utilistaur::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), 
            'cartdata' => '{}', 
        ]);

        
        $token = $user->createToken('auth_token')->plainTextToken;

        
        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201); 
    }

    /**
     * Login a user and generate a token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

       
        $credentials['password'] = trim($credentials['password']);


        $user = Utilistaur::where('email', $credentials['email'])->first();

        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 401); 
        }

        
        error_log('Provided Password: ' . $credentials['password']);
        error_log('Stored Password: ' . $user->password);

        
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Incorrect password'], 401); 
        }

        
        $token = $user->createToken('auth_token')->plainTextToken;

        
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
}