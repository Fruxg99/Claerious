<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class main extends Controller
{
    public function load() {
        return view('welcome');
    }
}
