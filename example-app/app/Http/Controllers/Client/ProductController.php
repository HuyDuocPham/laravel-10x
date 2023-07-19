<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProductBySlug(string $slug){
        $product = Product::where('slug', $slug)->first();

        // $productCategories = ProductCategory::latest()->get()->filter(function($productCategory){
        //     return $productCategory->products->count() > 0;
        // })->take(10);

        return view('client.pages.product_detail', compact('product'));
    }
}
