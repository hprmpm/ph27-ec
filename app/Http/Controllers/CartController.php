<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ], [
            'quantity' => '正しい個数を入力してください。',
        ]);

        $productId = $request->input('productId');
        $quantity = $request->input('quantity');

        $cart = session()->get('cart', []);
        $cart[$productId] = $quantity;
        session(['cart' => $cart]);

        return redirect()->route('cart.index');
    }

    public function index()
    {
        $cart = session()->get('cart', []);

        $items = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            $items[] = ['quantity' => $quantity, 'product' => $product];
            $totalPrice += $product->price * $quantity;
        }

        return view('cart', [
            'items' => $items,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function destroy()
    {
        session()->forget('cart');

        return redirect()->route('cart.index');
    }
}
