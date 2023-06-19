<?php

use App\Http\Controllers\ProfileController;
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



Route::get('admin', function () {
    return view('admin.layout.master');
})->name('admin')->middleware('auth.admin');

Route::get('home', function () {
    return view('client.pages.home');
})->name('home');


Route::get('chivas', function () {
    return '<h1>Chivas</h1>';
})->middleware('checkage18');

Route::get('cocacola', function () {
    return '<h1>Cocacola</h1>';
});


// //Route: route + name + group 
// Route::middleware('auth.admin')->name('admin.')->group( function () {
//     Route::get('admin/user', function() {
//         return view('admin.pages.user');
//     })->name('user');
// });

// // Route: route + group 
// Route::middleware('auth.admin')->group( function () {
//     Route::get('admin/user', function() {
//         return view('admin.pages.user');
//     })->name('admin.user');
// });


require __DIR__ . '/auth.php';
