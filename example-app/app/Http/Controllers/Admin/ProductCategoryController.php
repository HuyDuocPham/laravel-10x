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
        // $check = DB::insert('insert into product_category_2 (name, slug, status) values (?, ?, ?)', [
        //     $request->name,
        //     $request->slug,
        //     $request->status
        // ]);

        //Cach 2 : Query Builder

        $check = DB::table('product_category_2')->insert([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'status' => $request->status
        ]);

        // $lastId = DB::table('product_category_2')->insertGetId([
        //     'name' => $request->name,
        //     'slug' => $request->slug,
        //     'status' => $request->status
        // ]);
        //// return ID


        $msg = $check ? 'Create Product Category Success' : 'Create Product Category Failed';
        return redirect()->route('admin.product_category_2.list')->with('message', $msg);
    }

    public function index()
    {
        //SQL RAW
        $productCategories = DB::select('select * from product_category');
        return view('admin.product_category.list', compact('productCategories'));
    }

    public function detail($id)
    {
        $productCategory = DB::select('select * from product_category where id');
        return view('admin.product_category.detail', ['productCategory' => $productCategory]);
    }
    public function update(Request $request)
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
        //update into DB
        $check = DB::update(
            'UPDATE product_category SET name = ?, slug = ?, status = ?, where id =?',
            [
                $request->name,
                $request->slug,
                $request->status,
                $request->id
            ]
        );
        $message = $check ? 'success' : 'failed';
        return view('admin.product_category.list')->with('message', $message);
    }
}
