<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // $cart = session()->get('cart') ?? [];
        $cart = session()->get('cart', []);
        return view('client.pages.checkout', compact('cart'));
    }
}
