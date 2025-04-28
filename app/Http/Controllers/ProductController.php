<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        // Fetch all products from the database with any related data needed for discounts
        $products = Product::all()->map(function ($product) {
            $product->discountType = ['auth', 'price', 'quantity'][rand(0, 2)];
            return $product;
        });
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }
}
