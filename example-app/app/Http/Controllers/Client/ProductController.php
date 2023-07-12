<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Models\Product;


class ProductController extends Controller
{
    public function getProductBySlug(string $slug)
    {
        $products = Product::where('slug', $slug)->first();


        $productCategories = ProductCategory::latest()->get()->filter(function ($productCategory) {
            return $productCategory->products->count() > 0;
        })->take(10);
        return view('client.pages.product_detail', compact('products', 'productCategories'));
    }
}