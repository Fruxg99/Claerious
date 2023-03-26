<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class dashboard extends Controller
{
    //
    public function load() {
        return view('Backoffice.dashboard');
    }
}
