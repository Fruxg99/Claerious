<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class home extends Controller
{
    public function load() {
        $data = [];
        $data["items"] = Product::get();

        dd($data);

        // return view('Store.home', ["data" => $data]);
    }
}
