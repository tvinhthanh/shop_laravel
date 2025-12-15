<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $cart = session('cart', []);

        if (!isset($cart[$id])) {
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'thumbnail' => $product->thumbnail,
                'quantity' => 0
            ];
        }

        $cart[$id]['quantity'] += $request->quantity ?? 1;

        session(['cart' => $cart]);
        return back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    public function remove($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);

        session(['cart' => $cart]);
        return back()->with('success', 'Đã xoá sản phẩm');
    }
}
