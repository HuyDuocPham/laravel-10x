<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $datas = Product::take(5)->get();
        return ProductResource::collection($datas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $errors = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
        ]);

        if ($errors->fails()) {
            return response()->json(['message' => 'NOT OK', 'errors' => $errors->errors()], 400);
        }
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return ProductResource::make($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
