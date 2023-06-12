<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class NguoiDungController extends Controller
{
    public function luuNguoiDung ( Request $request) {
        $request->validate([
            'email' => 'required|min:3|max:255',
            'name' => 'required|min:3|max:255',
            'password' => 'required|min:3|max:20',
        ],
    [   
        'email.email' =>'Email khong duoc dinh dang!',
        'email.required' =>'Email la bat buoc!',
        'email.min' =>'Email nho nhat la 3!',
        'email.max' =>'Email lon nhat la 255!',
    ]);

    // save into database
    $password = Hash::make($request->password);
    $check = DB::insert('INSERT INTO nguoidung(name, email, password) VALUES (?, ?, ?)',[$request->name, $request->email, $password]);
    // sửa .env (DB. . . )

    return redirect()->route('home')->with('message','Dang ky thanh  cong');
    }

    public function dangnhap(Request $request) {
        dd($request->all());
    }
}
