<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', 1)->paginate(12);
        $categories = Category::all();

        return view('shop.index', compact('products', 'categories'));
    }

    public function category($slug)
    {
        $categories = Category::all();
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = $category->products()
            ->where('is_active', 1)
            ->paginate(12);

        return view('shop.category', compact('products', 'categories', 'category'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        return view('shop.product', compact('product'));
    }
}
