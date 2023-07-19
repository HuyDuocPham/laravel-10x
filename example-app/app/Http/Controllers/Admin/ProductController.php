<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline as PipelinePipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Pipeline;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $keyword = $request->keyword;
        // $status = $request->status;
        // $amountStart= $request->amount_start;
        // $amountEnd= $request->amount_end;
        // $sort = $request->sort;

        // $filter = [];
        // if(!is_null($keyword)){
        //     $filter[] = ['name', 'like', '%'.$keyword.'%'];
        // }
        // //200
        // if(!is_null($status)){
        //     $filter[] = ['status', $status];
        // }
        // //100
        // if(!is_null($amountStart) && !is_null($amountEnd))
        // {
        //     $filter[] = ['price', '>=', $amountStart];
        //     $filter[] = ['price', '<=', $amountEnd];
        // }
        // //50

        // //Sort
        // $sortBy = ['id', 'desc'];
        // switch($sort){
        //     case 1:
        //         $sortBy = ['price', 'asc'];
        //         break;
        //     case 2:
        //         $sortBy = ['price', 'desc'];
        //         break;
        // }

        // $products = Product::where($filter)->orderBy($sortBy[0], $sortBy[1])->paginate(config('myconfig.item_per_page'));

        // if(!is_null($amountStart) && !is_null($amountEnd))
        // {
        //     $products = Product::where($filter)
        //     ->whereBetween('price', [$amountStart, $amountEnd])
        //     ->paginate(config('myconfig.item_per_page'));
        // }else{
        //     $products = Product::where($filter)->paginate(config('myconfig.item_per_page'));
        // }


        //QueryBuilder
        //SELECT product.*, product_category.name
        // FROM product INNER JOIN product_category
        //  ON product_category.id = product.product_category_id;

        // $products = DB::table('product')
        // ->join('product_category', 'product_category.id', '=','product.product_category_id')
        // ->select('product.*', 'product_category.name as product_category_name')
        // ->paginate(config('myconfig.item_per_page'));


        $pipelines = [
            \App\Filters\ByKeyword::class,
            \App\Filters\ByStatus::class,
            \App\Filters\ByMinMax::class,
        ];

        // //use Illuminate\Support\Facades\Pipeline;

        $pipeline = Pipeline::send(Product::query()->withTrashed())
        ->through($pipelines)
        ->thenReturn();

        //use Illuminate\Pipeline\Pipeline
        // $pipeline = app(PipelinePipeline::class)
        // ->through($pipelines)
        // ->thenReturn();

        $products = $pipeline->paginate(config('myconfig.item_per_page'));
        // $products = $pipeline->get();
        // $products = Product::with('category')->paginate(999);

        // $products = DB::table('product')
        // ->join('product_category', 'product.product_category_id', '=', 'product_category.id')
        // ->select('product.*', 'product_category.name as product_category_name')
        // ->paginate(999);


        $maxPrice = Product::max('price');
        $minPrice = Product::min('price');

        return view('admin.product.list',
        [
            'products' => $products,
            'maxPrice' => $maxPrice,
            'minPrice' => $minPrice
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //SQL Raw
        // $productCategories = DB::select('select * from product_category where status = 1');

        //Query Builder
        // $productCategories = DB::table('product_category')->where('status', 1)->get();

        //Eloquent
        // $productCategories = ProductCategory::all();
        $productCategories = ProductCategory::where('status', 1)->get();

        return view('admin.product.create')->with('productCategories', $productCategories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //validate
        // $request->validate([
        //     'name' => 'required',
        //     'product_category_id' => 'required'
        // ]);

        //SQL Raw
        // $check = DB::insert("INSERT INTO product ('name') VALUES (?) ", [$request->name]);
        //Query Builder
        // $check = DB::table('product')->insert(['name' => $request->name]);

        $fileName = null;
        if ($request->hasFile('image_url')) {
            $originName = $request->file('image_url')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('image_url')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('image_url')->move(public_path('images'), $fileName);
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'information' => $request->information,
            'qty' => $request->qty,
            'shipping' => $request->shipping,
            'weight' => $request->weight,
            'status' => $request->status,
            'product_category_id' => $request->product_category_id,
            'image_url' => $fileName
        ]);

        $message = $product ? 'create success' : 'create failed';

        return redirect()->route('admin.product.index')->with('message', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //SQL Raw
        // $product = DB::select('select * from product where id = ?', [$id]);
        //Query Builder
        // $product = DB::table('product')->where('id', $id)->first();
        //Eloquent
        // $product = Product::find($id);
        $productCategories = ProductCategory::where('status', 1)->get();

	    return view('admin.product.edit', ['product' => $product, 'productCategories' => $productCategories]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
         //validate
        //  $request->validate([
        //     'name' => 'required',
        //     'product_category_id' => 'required'
        // ]);

        //Tim record se~ update
        // $product = Product::find($id);

        $fileName = $product->image_url;
        if ($request->hasFile('image_url')) {
            $originName = $request->file('image_url')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('image_url')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('image_url')->move(public_path('images'), $fileName);

            //Remove old images
            if (!is_null($product->image_url) && file_exists("images/" . $product->image_url)) {
                unlink("images/" . $product->image_url);
            }
        }

        $check = $product->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'information' => $request->information,
            'qty' => $request->qty,
            'shipping' => $request->shipping,
            'weight' => $request->weight,
            'status' => $request->status,
            'product_category_id' => $request->product_category_id,
            'image_url' => $fileName
        ]);

        $message = $check ? 'update success' : 'update failed';

        return redirect()->route('admin.product.index')->with('message', $message);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // $product = Product::find($id);
        $check = $product->delete();

        $message = $check ? 'delete success' : 'delete failed';

        return redirect()->route('admin.product.index')->with('message', $message);
    }

    public function restore(string $product){
        $product = Product::withTrashed()->find($product);
        // $product->deleted_at = null;
        // $product->save();
        $product->restore();

        return redirect()->route('admin.product.index')->with('message', 'Restore success');
    }
}
