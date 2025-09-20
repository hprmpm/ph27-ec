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
        // 配送先情報のバリデーション
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

            // 注文詳細保存と更新
            foreach ($cart as $productId => $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                $product = Product::find($productId);
                $stock = $product->stock - $item['quantity'];
                if ($stock < 0) {
                    throw new Exception($product->name . 'の在庫が足りません。');
                }
                $product->stock = $stock;
                $product->save();
            }
            
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', $e->getMessage() ?: '注文処理中にエラーが発生しました。');
        }
        
        session()->forget('cart');

        return redirect()->route('products.index')->with('success', 'ご注文ありがとうございます！');
    }
}