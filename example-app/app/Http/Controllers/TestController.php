<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function index(){
        return '<h1>Index of Test Controller</h1>';
    }

    public function detail(){
        return '<h1>Detail of Test Controller</h1>';
    }

    public function show($id = 1, $test = 'test'){
        return "<h1>Show $id + $test of Test Controller</h1>";
    }
}
