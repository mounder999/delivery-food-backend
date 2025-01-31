<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilistaur; // Replace with your User model if different
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
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilistaurs', // Ensure email is unique
            'password' => 'required|string|min:8', // Password must be at least 8 characters
        ]);

        // Create the user
        $user = Utilistaur::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Hash the password
            'cartdata' => '{}', // Default empty JSON object
        ]);

        // Generate a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the token and user details
        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201); // HTTP 201: Created
    }

    /**
     * Login a user and generate a token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Validate the request
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Trim the password input (to avoid issues with leading/trailing spaces)
        $credentials['password'] = trim($credentials['password']);

        // Attempt to find the user by email
        $user = Utilistaur::where('email', $credentials['email'])->first();

        // If the user does not exist, return an error
        if (!$user) {
            return response()->json(['message' => 'User not found'], 401); // HTTP 401: Unauthorized
        }

        // Debug: Print the provided password and the stored hash (for debugging purposes)
        error_log('Provided Password: ' . $credentials['password']);
        error_log('Stored Password: ' . $user->password);

        // Verify the password
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Incorrect password'], 401); // HTTP 401: Unauthorized
        }

        // Generate a token for the authenticated user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the token and user details
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
}