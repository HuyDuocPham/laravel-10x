<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function store(Request $request)
    {
        //Validate data from client
        $request->validate([
            'name' => 'required|min:1|max:255|string|unique:product_category,name',
            'slug' => 'required|min:1|max:255|string',
            'status' => 'required|boolean'
        ], [
            'name.required' => 'Ten buoc phai nhap !'
        ]);

        //SQL RAW
        // $check = DB::insert('insert into product_category(name,slug,status) values (?, ?, ?)',
        //  [$request->name, $request->slug, $request->status]);

        //Query Builder
        $check = DB::table('product_category')->insert([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status
        ]);

        //
        // $lastId = DB::table('product_category')->insertGetId([
        //     'name' => $request->name,
        //     'slug' => $request->slug,
        //     'status' => $request->status
        // ]);

        $msg = $check ? 'Create Product Category Success' : 'Create Product Category Failed';

        return redirect()->route('admin.product_category.list')->with('message', $msg);
    }

    public function getSlug(Request $request)
    {
        $slug = Str::slug($request->name);
        return response()->json(['slug' => $slug]);
    }

    public function index(Request $request)
    {
        // $page = $request->page ?? 1;

        // $itemPerPage = config('myconfig.item_per_page');
        // $pageFirst = ($page-1) * $itemPerPage ;

        // //SQL RAW
        // $query = DB::select('select * from product_category');
        // $numberOfPage = ceil (count($query) / $itemPerPage);

        // $productCategories = DB::select("select * from product_category
        // limit $pageFirst, $itemPerPage");

        // return view('admin.product_category.list',
        // compact('productCategories', 'numberOfPage'));

        // $productCategories = DB::table('product_category')
        // ->paginate(config('myconfig.item_per_page'));

        //Phải dùng Query builder để chạy {{ProductCategories ->prpducts->count()}}
        $productCategories = ProductCategory::paginate(config('myconfig.item_per_page'));
        return view('admin.product_category.list', compact('productCategories'));
    }

    public function detail($id)
    {
        $productCategory = DB::select('select * from product_category where id = ?', [$id]);

        return view('admin.product_category.detail', ['productCategory' => $productCategory]);
    }

    public function update(Request $request, string $id)
    {
        //validate input from user
        $request->validate([
            'name' => 'required|min:1|max:255|string|unique:product_category,name,' . $id,
            'slug' => 'required|min:1|max:255|string',
            'status' => 'required|boolean'
        ], [
            'name.required' => 'Ten buoc phai nhap !'
        ]);

        //Update into DB - SQL Raw
        // $check = DB::update('UPDATE product_category SET name = ?, slug = ?, status = ? where id = ?',
        // [
        //     $request->name,
        //     $request->slug,
        //     $request->status,
        //     $id
        // ]);
        //Query Builder
        $check = DB::table('product_category')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
            ]);

        $message = $check ? 'update success' : 'update failed';

        return redirect()->route('admin.product_category.list')->with('message', $message);
    }

    public function destroy($id)
    {
        //SQL RAW
        // $check = DB::delete('delete from product_category where id = ?', [$id]);

        //Query Builder
        $check = DB::table('product_category')->where('id', $id)->delete();

        $message = $check ? 'delete success' : 'delete failed';
        //redirect to list product category page with message ( session flash )
        return redirect()->route('admin.product_category.list')->with('message', $message);
    }
}
