<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        // Google Chart
        $dataOrders = DB::table('order')
            ->selectRaw('status,count(status) as number')
            ->groupBy('status')
            ->get();
        $arrayDatas = [];
        $arrayDatas[] = ['status', 'Number'];
        foreach ($dataOrders as $data) {
            $arrayDatas[] = [$data->status, $data->number];
        }

        return view('admin.pages.google_chart', compact('arrayDatas'));
    }
}
