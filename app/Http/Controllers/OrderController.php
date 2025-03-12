<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Utilistaur; 
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Place a new order.
     */
   

public function placeOrder(Request $request)
{
    $request->validate([
        'items' => 'required|array',
        'amount' => 'required|numeric|min:0',
        'address' => 'required|string|max:255',
    ]);

    $user = Auth::user();

    // Create the order
    $order = Order::create([
        'user_idd' => $user->id,  
        'items' => $request->items, 
        'amount' => $request->amount,
        'address' => $request->address,
        'status' => 'Food Processing',
        'date' => now(), 
        'payment' => false,
    ]);

    return response()->json([
        'message' => 'Order placed successfully',
        'order' => $order
    ], 201);
}

}
