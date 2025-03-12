<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilistaur;
use App\Models\Food;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    
    public function __construct()
    {
        
    }

    
    public function addToCart(Request $request)
{
    try {
        
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        \Log::info('User authenticated.', ['user_id' => $user->id]);

        
        $validatedData = $request->validate([
            'food_id' => 'required|exists:foods,food_id',
            'quantity' => 'required|integer|min:1',
        ]);

        
        $food = Food::find($validatedData['food_id']);

        
        $cart = json_decode($user->cartdata, true) ?? [];

        
        $found = false;
        foreach ($cart as &$item) {
            if ($item['food_id'] == $food->food_id) {
                $item['quantity'] += $validatedData['quantity'];
                $found = true;
                break;
            }
        }

        
        if (!$found) {
            $cart[] = [
                'food_id' => $food->food_id,
                'name' => $food->name,
                'price' => $food->price,
                'quantity' => $validatedData['quantity'],
                'image' => $food->image,
            ];
        }

        
        $user->cartdata = json_encode($cart);
        $user->save();

        return response()->json([
            'message' => 'Item added to cart successfully',
            'cart' => $cart,
        ], 200);
    } catch (\Exception $e) {
        \Log::error('Error in addToCart', ['error' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

   public function removeFromCart(Request $request, $foodId)
{
    try {
        $user = $request->user(); 
        $cart = json_decode($user->cartdata, true) ?? []; 
        
        if (empty($cart)) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $updatedCart = [];
        $found = false;

        foreach ($cart as $item) {
            if ($item['food_id'] == $foodId) {
                $found = true;
                if ($item['quantity'] > 1) {
                    $item['quantity'] -= 1; 
                    $updatedCart[] = $item; 
                }
            } else {
                $updatedCart[] = $item; 
            }
        }

        if (!$found) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        $user->cartdata = json_encode($updatedCart);
        $user->save();

        return response()->json(['message' => 'Item quantity updated or removed', 'cart' => $updatedCart], 200);

    } catch (\Exception $e) {
        \Log::error('Error in removeFromCart', ['error' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    

    public function viewCart(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            $user = Auth::user();
            $cart = json_decode($user->cartdata, true);
    
            if (empty($cart)) {
                return response()->json(['message' => 'Your cart is empty', 'cart' => []], 200);
            }
    
            $cartItems = array_map(function ($item) {
                return [
                    'food_id' => $item['food_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['price'] * $item['quantity'],
                ];
            }, $cart);
    
            return response()->json([
                'message' => 'Cart retrieved successfully',
                'cart' => $cartItems,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error in viewCart', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    
}