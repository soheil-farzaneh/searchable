<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\services\DataTable\DataTable;

class TestController extends Controller
{
    public function test(Request $request) {
        $request = $request->all();

        $query = User::query()->with('posts' , 'products');
        $service = new DataTable();
        $response = $service->processRequest($request);
        $test = $service->applySearchRelation($query);dd($test);
    }
}
