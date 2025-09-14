<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        $saleProducts = Product::where('price', '<', 100)->get();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'saleProducts' => $saleProducts,
        ]);
    }

    public function show(int $id)
    {

        $product = Product::findOrFail($id);

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }
}
