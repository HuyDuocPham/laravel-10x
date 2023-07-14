<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart');
        return view('client.pages.cart', compact('cart'));
    }
    public function addProductToCart($productId)
    {
        $product = Product::find($productId);
        $cart = session()->get('cart') ?? [];
        if ($product) {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => number_format($product->price, 2),
                'image_url' => $product->image_url,
                'qty' => ($cart[$productId['qty']] ?? 0) + 1

            ];
            //add cart into session
            session()->put('cart', $cart);
            return response()->json(['message' => 'Add product success!']);
        } else {
            return response()->json(['message' => 'Add product failed!'], Response::HTTP_NOT_FOUND);
        }
    }
}