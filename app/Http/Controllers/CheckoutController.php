<?php

namespace App\Http\Controllers;

use App\Models\Product; // Productモデルをインポート
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

        // フロントエンドで表示するための詳細なカート情報を格納する配列
        $cartItems = [];
        // 合計金額を計算するための変数
        $total = 0;

        // DBへの問い合わせを一度にまとめる (N+1問題対策)
        $productIds = array_keys($sessionCart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // セッションからIDと数量を取り出し、DBから取得した情報を組み合わせる
        foreach ($sessionCart as $id => $quantity) {
            if (isset($products[$id])) {
                $product = $products[$id];
                $subtotal = $product->price * $quantity;
                
                // フロントエンドが必要とする形式の配列を作成
                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
                // 合計金額を更新
                $total += $subtotal;
            }
        }

        // もしカートの中の商品がDBから削除されるなどしてカートが空になった場合の安全対策
        if (empty($cartItems)) {
            session()->forget('cart');
            return redirect()->route('products.index');
        }

        // 整形済みのカート情報と計算済みの合計金額をフロントエンドに渡す
        return Inertia::render('Checkout/Index', [
            'user' => $user,
            'cartItems' => $cartItems, // 'cart' から 'cartItems' に変更
            'total' => $total,       // 新しく 'total' を追加
        ]);
    }
}