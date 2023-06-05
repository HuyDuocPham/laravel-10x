<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        return '<h1>Index of Test Controller</h1>';
    }
    public function detail()
    {
        return '<h1>Detail of Test Controller</h1>';
    }
    public function show($id, $test)
    {
        return "<h1>$id + $test Show of Test Controller</h1>";
    }
}