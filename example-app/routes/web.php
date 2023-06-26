<?php

use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\NguoiDungController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
Route::get('checkout', function () {
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
Route::get('admin', function () {
    return view('admin.layout.master');
});


Route::get('dangky', function () {
    return view('client.pages.dangky');
});

Route::post('luuNguoiDung', [NguoiDungController::class, 'luuNguoiDung'])->name('nguoidung.dangky');
Route::post('dangnhap', [NguoiDungController::class, 'dangnhap'])->name('nguoidung.dangnhap');


Route::get('admin/product_category/list', [ProductCategoryController::class, 'index'])->name('admin.product_category.list');
Route::get('admin/product_category/create', [ProductCategoryController::class, 'store'])->name('admin.product_category.create');



require __DIR__ . '/auth.php';
