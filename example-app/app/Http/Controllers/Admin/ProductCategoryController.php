<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{

    public function store(Request $request)
    {
        // Validate data from client
        $request->validate(
            [
                'name' => 'required|min:1|max:255|string',
                'slug' => 'required|min:1|max:255|string',
                'status' => 'required|boolean'
            ],
            [
                'name.required' => 'Ten bat buoc phai nhap'
            ]
        );
        //save into DB 
        // cach1: SQL RAW
        // $check = DB::insert('insert into product_category (name, slug, status) values (?, ?, ?)', [
        //     $request->name,
        //     $request->slug,
        //     $request->status
        // ]);
        //Cach 2 : Query Builder
        $check = DB::table('product_category')->insert([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'status' => $request->status
        ]);
        // $lastId = DB::table('product_category')->insertGetId([
        //     'name' => $request->name,
        //     'slug' => $request->slug,
        //     'status' => $request->status
        // ]);
        //// return ID
        $msg = $check ? 'Create Product Category Success' : 'Create Product Category Failed';
        return redirect()->route('admin.product_category.list')->with('message', $msg);
    }

    public function index(Request $request)
    {   
        // $page = $request->page ?? 1;
        // //Cach 1: Tao file .env
        // //$itemPerPage = env('ITEM_PER_PAGE'); // file .env
        // //Cach 2: Tao file myconfig.php trong file config
        // $itemPerPage = config('myconfig.item_per_page'); // file config->myconfig


        // $pageFirst = ($page - 1) * $itemPerPage;

        // //SQL RAW
        // $query = DB::select('SELECT * from product_category');
        // $numberOfPage = ceil(count($query)/$itemPerPage);

        // $productCategories = DB::select("select * from product_category limit $pageFirst, $itemPerPage");


        // return view('admin.product_category.list', compact('productCategories', 'numberOfPage'));

        $productCategories = DB::table('product_category')->paginate(config('myconfig.item_per_page'));
        return view('admin.product_category.list', compact('productCategories'));

    }

    public function detail($id)
    {
        $productCategory = DB::select('select * from product_category where id=?',[$id]);
        return view('admin.product_category.detail', ['productCategory' => $productCategory]);
    }
    public function update(Request $request, string $id)
    {
        // Validate data from client
        $request->validate(
            [
                'name' => 'required|min:1|max:255|string'.$id,
                'slug' => 'required|min:1|max:255|string',
                'status' => 'required|boolean'
            ],
            [
                'name.required' => 'Ten bat buoc phai nhap'
            ]
        );
        //update into DB - SQL RAW
        // $check = DB::update(
        //     'UPDATE product_category SET name = ?, slug = ?, status = ?, where id =?',
        //     [
        //         $request->name,
        //         $request->slug,
        //         $request->status,
        //         $id
        //     ]
        // );

        //QUery builder
        $check = DB::table('product_category')->where('id', $id)->update([
            $request->name,
            $request->slug,
            $request->status,
        ]);
        $message = $check ? 'success' : 'failed';
        return view('admin.product_category.list')->with('message', $message);
    }
    public function destroy($id)
    {
        //Query Builder
        $check = DB::table('product_category')->where('id', $id)->delete();
        $message = $check ? 'Delete success' : 'Delete failed';
        return view('admin.product_category.list')->with('message', $message);
    }
}
