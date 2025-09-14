<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index()
    {
        $sessionCart = session('cart', []);
        $cartItems = [];
        $total = 0;

        // Eager load products to avoid N+1 query problem
        $products = Product::whereIn('id', array_keys($sessionCart))->get()->keyBy('id');

        foreach ($sessionCart as $productId => $quantity) {
            if (isset($products[$productId])) {
                $product = $products[$productId];
                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ];
                $total += $product->price * $quantity;
            }
        }

        return Inertia::render('Cart/Index', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $productId = $request->input('product_id');

        $cart = session('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + 1;
        session(['cart' => $cart]);

        return back();
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $productId = $request->input('product_id');

        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }
        session(['cart' => $cart]);

        return back()->with('success', '商品をカートから削除しました。');
    }

    /**
     * Update product quantity in the cart.
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId] = $quantity;
        }
        session(['cart' => $cart]);

        return back();
    }
}
