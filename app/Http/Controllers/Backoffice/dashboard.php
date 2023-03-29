<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\T_detail;
use App\Models\T_head;
use Illuminate\Http\Request;

class dashboard extends Controller
{
    //
    public function load() {
        session_start();

        $data = [];
        $data["newOrder"] = T_head::where("t_heads.status", "=", "2")
                            ->join("t_details", "t_details.id_trans", "=", "t_heads.id_trans")
                            ->where("t_details.id_seller", json_decode($_SESSION["seller"])->id_seller)
                            ->get();
        $data["completeOrder"] = T_head::where("t_heads.status", ">", "4")
                                ->join("t_details", "t_details.id_trans", "=", "t_heads.id_trans")
                                ->where("t_details.id_seller", json_decode($_SESSION["seller"])->id_seller)
                                ->get();

        return view('Backoffice.dashboard', ["data" => $data]);
    }
}
