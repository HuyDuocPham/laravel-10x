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
        $cart = session()->get('cart') ?? [];
        return view('client.pages.cart', compact('cart'));
    }
    public function addProductToCart($productId, $qty = 1)
    {
        $product = Product::find($productId);
        if ($product) {
            $cart = session()->get('cart') ?? [];
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => number_format($product->price, 2),
                'image_url' => $product->image_url,
                'qty' => ($cart[$productId['qty']] ?? 0) + $qty

            ];
            //add cart into session
            session()->put('cart', $cart);
            $totalProduct = count($cart);
            $totalPrice = $this->calculateTotalPrice($cart);
            return response()->json([
                'message' => 'Add product success!',
                'total_product' => $totalProduct,
                'total_price' => $totalPrice

            ]);
        } else {
            return response()->json(['message' => 'Add product failed!'], Response::HTTP_NOT_FOUND);
        }
    }

    public function calculateTotalPrice(array $cart)
    {
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['qty'] * $item['price'];
        }
        return number_format($totalPrice, 2);
    }

    public function deleteProductInCart($productId)
    {
        $cart = session()->get('cart') ?? [];
        if (array_key_exists($productId, $cart)) {
            unset($cart[$productId]); // Xóa
            session()->put('cart', $cart); // Cập nhật lại
            $totalProduct = count($cart);
            $totalPrice = $this->calculateTotalPrice($cart);
        } else {
            return response()->json(['message' => 'Delete product failed!'], Response::HTTP_NOT_FOUND);
        }
        
        $totalProduct = count($cart);
        $totalPrice = $this->calculateTotalPrice($cart);
        return response()->json([
            'message' => 'Add product success!',
            'total_product' => $totalProduct,
            'total_price' => $totalPrice

        ]);
    }
}
