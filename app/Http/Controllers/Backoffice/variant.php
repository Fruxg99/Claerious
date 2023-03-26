<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class variant extends Controller
{
    public function load() {
        return view('Backoffice.variant');
    }

    public function crud($mode) {
        if ($mode == "select") {

        } else if ($mode == "insert") {

        } else if ($mode == "update") {

        } else if ($mode == "delete") {

        }
    }
}
