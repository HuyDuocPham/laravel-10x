<?php

use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\GoogleController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\ProfileController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
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

require __DIR__ . '/auth.php';

Route::get('home', [HomeController::class, 'index'])->name('home');

Route::get('about-us', function () {
    return view('about_us');
});
Route::get('contact', function () {
    return view('client.pages.contact');
});

Route::get('blog', function () {
    return view('client.pages.blog');
});
Route::get('product-list', function () {
    return view('client.pages.product_list');
});
Route::get('product/{slug}', [ClientProductController::class, 'getProductBySlug'])->name('product.detail');



Route::get('checkout', [OrderController::class, 'index'])->name('checkout');


Route::middleware('auth.admin')->name('admin.')->group(function () {
    Route::get('admin', function () {
        return view('admin.pages.dashboard');
    })->name('admin');

    Route::get('admin/user', function () {
        return view('admin.pages.user');
    })->name('user');

    Route::get('admin/blog', function () {
        return view('admin.pages.blog');
    })->name('blog');

    // Route::get('admin/product',function (){
    //     return view('admin.pages.product');
    // })->name('product');

    // Route::get('admin/product/create',[ProductController::class, 'create'])->name('product.create');
    // Route::post('admin/product/store',[ProductController::class, 'store'])->name('product.store');
    // Route::get('admin/product/show/{id}',[ProductController::class, 'show'])->name('product.store');
    // Route::get('admin/product/update',[ProductController::class, 'update'])->name('product.store');
    // Route::post('admin/product/delete',[ProductController::class, 'destroy'])->name('product.store');

    Route::resource('admin/product', ProductController::class);

    Route::post('admin/product/restore/{product}', [ProductController::class, 'restore'])->name('product.restore');

    Route::get('admin/product_category', [ProductCategoryController::class, 'index'])->name('product_category.list');

    Route::get('admin/product_category/create', function () {
        return view('admin.product_category.create');
    })->name('product_category.create');

    Route::post('admin/product_category/save', [ProductCategoryController::class, 'store'])
        ->name('product_category.save');

    Route::post('admin/product_category/slug', [ProductCategoryController::class, 'getSlug'])
        ->name('product_category.slug');

    Route::get('admin/product_category/{id}', [ProductCategoryController::class, 'detail'])->name('product_category.detail');

    Route::post('admin/product_category/update/{id}', [ProductCategoryController::class, 'update'])
        ->name('product_category.update');

    Route::post('admin/product_category/delete/{id}', [ProductCategoryController::class, 'destroy'])
        ->name('product_category.delete');
});

// Google Developer Socialite



Route::get('google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');

Route::get('google/callback', [GoogleController::class, 'callback'])->name('google.callback');

require __DIR__ . '/cart/web.php';
