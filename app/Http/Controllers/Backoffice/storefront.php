<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class storefront extends Controller
{
    public function load() {
        return view('Backoffice.storefront');
    }

    public function crud($mode) {
        if ($mode == "select") {

        } else if ($mode == "insert") {

        } else if ($mode == "update") {

        } else if ($mode == "delete") {

        }
    }
}
