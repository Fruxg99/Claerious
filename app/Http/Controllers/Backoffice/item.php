<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class item extends Controller
{
    public function load() {
        return view('Backoffice.item');
    }
}
