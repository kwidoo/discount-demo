<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\DiscountService;
use App\Models\Product;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CartController extends Controller
{
    public function __construct(protected DiscountService $discountService)
    {
        // Middleware can be added here if needed
    }

    /**
     * Retrieve cart data by ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCart(Request $request, ?Cart $cart)
    {
        $user = $cart->user;
        if (!$cart->exists) {
            $user = User::find(1);
            $cart = Cart::create([
                'user_id' => $user->id,
            ]);
        }

        // Calculate cart totals
        $subtotal = $cart->total();
        $discount = $this->discountService->calculateDiscount($cart, $user);
        $total = $subtotal - $discount;

        return response()->json([
            'cart_id' => $cart->id,
            'items' => $cart->items(),
            'user_id' => $user?->id,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
        ]);
    }

    public function calculate(Request $request, Cart $cart)
    {
        $cartData = $request->input('cart', []);
        $user = $request->input('authenticated', false) ? User::first() : null;

        $products = Product::whereIn('id', array_keys($cartData))->get();

        // Create a collection to hold cart products with their quantities
        $cart->products()->detach();
        foreach ($products as $product) {
            $cart->products()->attach($product->id, [
                'quantity' => $cartData[$product->id],
            ]);
        }

        // Use DiscountService to calculate appropriate discounts
        $discountService = app(DiscountService::class);
        $subtotal = $cart->total(); // Now this will work properly
        $discount = $this->discountService->calculateDiscount($cart, $user);
        $total = $subtotal - $discount;

        return response()->json([
            'cart_id' => $cart->id,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
        ]);
    }
}
