<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $products = Product::query();
        $keyword = $request->keyword;
        $status = $request->status;
        $amountStart = $request->amount_start;
        $amountEnd = $request->amount_end;
        $sort =  $request->sort;

        $filter = [];
        //Search by keyword
        if (!is_null($keyword)) {
            $filter[] = ['name', 'like', '%' . $keyword . '%'];
        }
        //Search by status
        if (!is_null($status)) {
            $filter[] = ['status', $status];
        }
        //Search by price
        if (!is_null($amountStart) && !is_null($amountEnd)) {
            $filter[] = ['price', '>=', $amountStart];
            $filter[] = ['price', '<=', $amountEnd];
        }

        //Sort
        $sortBy = ['id', 'desc'];
        switch ($sort) {
            // case 0:
            //     $sortBy = ['id', 'desc'];
            //     break;
            case 1:
                $sortBy = ['price', 'asc'];
                break;
            case 2:
                $sortBy = ['price', 'desc'];
                break;
                default : 
        }


        // $products = Product::where($filter)->paginate(5);

        $products = Product::where($filter)->orderBy($sortBy[0], $sortBy[1])->paginate(5);
        $maxPrice = Product::max('price');
        $minPrice = Product::min('price');



        // if (is_null($keyword)) {
        //     $products = Product::paginate(5);
        // } else {
        //     $products = Product::where('name', 'like', '%' . $keyword . '%')->paginate(5);
        // }



        //Eloquent
        // $products = Product::all(); //Nếu dùng hàm link phải đổi all()->paginate
        // $products = Product::paginate(5); //paginate(config(myconfig.item_per_page))

        //Query Builder
        // $products = DB::table('product')
        //     ->join('product_category', 'product_category.id', '=', 'product.product_category_id')
        //     ->select('product.*', 'product_category.name as product_category_name')
        //     ->paginate(5); //paginate(config(myconfig.item_per_page))


        return view('admin.product.list', [
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
        //SQP RAW
        // $productCategories = DB::select("SELECT * from product_category where status = 1");

        //Query Builder
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
        // ]); // move Http/Request/StoreProductRequest

        $fileName = null;
        if ($request->hasFile('image_url')) {
            $originName = $request->file('image_url')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('image_url')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('image_url')->move(public_path('images'), $fileName);
        }

        //SQL RAW
        // $check = DB::insert("INSERT INTO product('name') VALUES (?)", [ $request->name]);

        // Eloquent
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
            'image_url' => $fileName,
        ]);

        $message = $product ? 'Create product success' : 'Create failed';

        return redirect()->route('admin.product.index')->with('message', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        // //SQL RAW
        // $product = DB::select('SELECT * FROM product WHERE id = ?', [$id]);

        // //Query Builder
        // $product = DB::table('product')->where('id',$id)->first();

        //Eloquent
        $product = Product::find($id);
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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'product_category_id' => 'required'
        ]);
        $product = Product::find($id);

        $fileName = $product->image_url;
        if ($request->hasFile('image_url')) {
            $originName = $request->file('image_url')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('image_url')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('image_url')->move(public_path('images'), $fileName);

            //remove old image
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
            'image_url' => $fileName,
        ]);

        $message = $check ? 'Update success' : 'Update failed';
        return redirect()->route('admin.product.index')->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     $product = Product::find($id);
    //     $check = $product->delete();
    //     $message = $check ? 'Delete success' : 'Delete failed';
    //     return redirect()->route('admin.product.index')->with('message', $message);
    // }
    public function destroy(Product $product)
    {
        // $product = Product::find($id);
        $check = $product->delete();
        $message = $check ? 'Delete success' : 'Delete failed';
        return redirect()->route('admin.product.index')->with('message', $message);
    }
}
