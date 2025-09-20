<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $sessionCart = session('cart', []);
        
        if (empty($sessionCart)) {
            return redirect()->route('products.index');
        }

        $cartItems = [];
        $total = 0;
        $productIds = array_keys($sessionCart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($sessionCart as $id => $quantity) {
            if (isset($products[$id])) {
                $product = $products[$id];
                $subtotal = $product->price * $quantity;
                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        if (empty($cartItems)) {
            session()->forget('cart');
            return redirect()->route('products.index');
        }

        return Inertia::render('Checkout/Index', [
            'user' => $user,
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }
}