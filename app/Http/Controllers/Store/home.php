<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class home extends Controller
{
    public function load() {
        return view('Store.home');
    }
}
