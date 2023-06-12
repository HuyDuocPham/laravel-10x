<?php

use App\Http\Controllers\Client\NguoiDungController;
use App\Http\Controllers\ProductCategoryController as AdminProductCategoryController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route get + closure
Route::get('test', function () {
    return '<h1>Test</h1>';
});

Route::get('user/index', function () {
    return '<h1>List User</h1>';
});

// Route + Controller + Action
Route::get('test/index', [TestController::class, 'index']);
Route::get('test/detail', [TestController::class, 'detail']);

// Route dynamic paramter
//Route::get('user/detail/id', [TestController::class, 'show']); {id} --> id động
//Route::get('user/detail/{id}', [TestController::class, 'show']); /{test} --> test động
//Route::get('user/detail/{id}/{test}', [TestController::class, 'show']); ? --> khi không có sẽ không bị lỗi
Route::get('user/detail/{id?}/{test?}', [TestController::class, 'show']);

//Ruote return views (resources/views/ add user/list_user.php)
Route::get('/', function () {
    return view('welcome');
});

Route::get('list_user', function () {
    return view('user.list_user');
});



Route::get('list_category', function () {
    return view('admin.product.product_category.list_category');
});


Route::get('list_category', [ProductCategoryController::class, 'index']);

Route::get('list_user_blade', function () {
    return view('user.list_user_blade');
});

Route::get('list_user_blade', [ProductController::class, 'index']);

Route::get('basic_template', function () {
    return view('user.basic_template');
});

Route::get('about_us', function () {
    return view('about_us');
});
Route::get('contact', function () {
    return view('contact');
});

// CLIENT
Route::get('home', function () {
    return view('client.pages.home');
})->name('home');
Route::get('blog', function () {
    return view('client.pages.blog');
});

Route::get('cart', function () {
    return view('client.pages.cart');
});
Route::get('checout', function () {
    return view('client.pages.checkout');
});
Route::get('contact', function () {
    return view('client.pages.contact');
});
Route::get('product_detail', function () {
    return view('client.pages.product_detail');
});
Route::get('product_list', function () {
    return view('client.pages.product_list');
});

//ADMIN
Route::get('admin', function() {
    return view('admin.layout.master');
});


Route::get('dangky', function() {
    return view('client.pages.dangky');
});

Route::post('luuNguoiDung', [NguoiDungController::class,'luuNguoiDung']) ->name('nguoidung.dangky');
Route::post('dangnhap', [NguoiDungController::class,'dangnhap']) ->name('nguoidung.dangnhap');



