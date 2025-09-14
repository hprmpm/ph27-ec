<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;

class OrderController extends Controller
{
    public function order(Request $request)
    {
        // 1. 配送先情報のバリデーション
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'street_address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'カートが空です。');
        }

        DB::beginTransaction();
        try {
            // 2. 注文 (orders) テーブルに保存
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']),
                'shipping_name' => $validated['name'],
                'shipping_postal_code' => $validated['postal_code'],
                'shipping_city' => $validated['city'],
                'shipping_street_address' => $validated['street_address'],
                'shipping_building' => $validated['building'],
                'shipping_phone_number' => $validated['phone_number'],
            ]);

            // 3. 注文詳細 (order_details) の保存 と 在庫更新
            foreach ($cart as $productId => $item) {
                // 注文詳細を保存
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                // 在庫を更新
                $product = Product::find($productId);
                $stock = $product->stock - $item['quantity'];
                if ($stock < 0) {
                    // 在庫が足りない場合は例外を投げてロールバック
                    throw new Exception($product->name . 'の在庫が足りません。');
                }
                $product->stock = $stock;
                $product->save();
            }
            
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            // カートページに戻ってエラーメッセージを表示
            return redirect()->route('cart.index')->with('error', $e->getMessage() ?: '注文処理中にエラーが発生しました。');
        }
        
        // 4. カートを空にする
        session()->forget('cart');

        // 5. 完了ページへリダイレクト
        return redirect()->route('products.index')->with('success', 'ご注文ありがとうございます！');
    }
}