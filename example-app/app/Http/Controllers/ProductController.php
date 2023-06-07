<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {

        $arrayProduct = [
        ['id' => 2, 'name' => 'Product A', 'price' => 32000], 
        ['id' => 3, 'name' => 'Product B', 'price' => 31000], 
        ['id' => 4, 'name' => 'Product C', 'price' => 37000], 
        ['id' => 5, 'name' => 'Product D', 'price' => 33000], 
        ['id' => 6, 'name' => 'Product F', 'price' => 9999999999]];

        //cach1: 
        return view('user.list_user_blade', ['arrayProduct' => $arrayProduct]);
        //cach2:
        //return view('user.list_user_blade') ->with('arrayProduct', $arrayProduct) ;
        //cach3
        // $test = 'test';
        // return view('user.list_user_blade', compact('arrayProduct', 'test'));
    }
}
