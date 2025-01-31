<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;



use Illuminate\Http\Request;

use App\Models\Food;
use App\Models\Utilistaur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
class FoodController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'price' => 'required|numeric',
            'description' => 'required|string|max:500',
            'category' => 'required|string|max:255',
        ]);
    
        // Handle the image upload
        if ($request->hasFile('image')) {
            // Generate a unique filename using timestamp and original filename
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('images', $filename, 'public'); // Store in the 'public' disk
            $validatedData['image'] = Storage::url($imagePath); // Store the public URL
        }
    
        // Create the food item in the database
        $food = Food::create($validatedData);
    
        // Return a JSON response with success message and food data
        return response()->json(['message' => 'Food created successfully', 'food' => $food], 201);
    }
    
    public function show($id)
    {
        $food = Food::find($id);

        if ($food) {
            return response()->json(['food' => $food], 200);
        } else {
            return response()->json(['message' => 'Food not found'], 404);
        }
    }
    public function delete($id)
    {
        $food = Food::find($id);

        if ($food) {
            // Delete the image file from storage
            Storage::disk('public')->delete(str_replace('storage/', '', $food->image));
            
            // Delete the food item from the database
            $food->delete();

            return response()->json(['message' => 'Food deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Food not found'], 404);
        }
    }
    public function update(Request $request, $id)
{
    // Find the food item by ID
    $food = Food::find($id);

    if ($food) {
        // Validate the request without specifying which fields are required
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional, only if a new image is provided
            'price' => 'sometimes|numeric',
            'description' => 'sometimes|string|max:500',
            'category' => 'sometimes|string|max:255',
        ]);

        // Handle the image upload if a new one is provided
        if ($request->hasFile('image')) {
            // Delete the old image from storage if it exists
            if ($food->image) {
                Storage::disk('public')->delete(str_replace('storage/', '', $food->image));
            }

            // Generate a unique filename using timestamp and original filename
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('images', $filename, 'public'); // Store in the 'public' disk
            $validatedData['image'] = Storage::url($imagePath); // Store the public URL
        }

        // Update the food item in the database
        $food->update(array_filter($validatedData)); // Update only provided fields

        // Return a JSON response with success message and updated food data
        return response()->json(['message' => 'Food updated successfully', 'food' => $food], 200);
    } else {
        return response()->json(['message' => 'Food not found'], 404);
    }
}
public function index()
{
    // Retrieve all food items from the database
    $foods = Food::all();

    // Return a JSON response with the food items
    return response()->json(['foods' => $foods], 200);
}
public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string',
       
        'email' => 'required|email|unique:utilisateurs',
        'password' => 'required|min:8',
        
    ]);

    // Create a user
    $user = Utilistaur::create([
        'name' => $request->input('name'),
       
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
        
    ]);

    // Create a client associated with the user
    
    return response()->json(['user' => $user, 'message' => 'User registered successfully'], 201);
}
}
