<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class mainBackoffice extends Controller
{
    public function dashboard() {
        return view('Backoffice.dashboard');
    }
}
