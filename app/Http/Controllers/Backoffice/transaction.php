<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\T_detail;
use App\Models\T_head;
use Illuminate\Http\Request;

class transaction extends Controller
{
    public function load() {
        return view('Backoffice.transaction');
    }

    public function crud(Request $request, $mode) {
        session_start();

        if ($mode == "select") {
            $data = [];
            $data["data"] = T_head::select("users.name", "addresses.address", "addresses.city_name", "addresses.postal_code", "t_heads.id_trans", "t_heads.status", "t_heads.total")
                                ->where("t_heads.status", ">", "1")
                                ->leftJoin("t_details", "t_details.id_trans", "=", "t_heads.id_trans")
                                ->leftJoin("users", "users.id_user", "=", "t_heads.id_user")
                                ->leftJoin("addresses", "addresses.id_address", "=", "t_heads.id_address")
                                ->where("t_details.id_seller", json_decode($_SESSION["seller"])->id_seller)
                                ->get();

            return json_encode($data);
        } else if ($mode == "selectByID") {
            $data = [];
            $data["transaction"]    = T_head::where("t_details.id_seller", json_decode($_SESSION["seller"])->id_seller)
                                        ->where("t_heads.id_trans", $request->input("id_trans"))
                                        ->leftJoin("t_details", "t_details.id_trans", "=", "t_heads.id_trans")
                                        ->leftJoin("addresses", "addresses.id_address", "=", "t_heads.id_address")
                                        ->get();
            $data["items"]          = T_detail::select("products.thumbnail", "products.name as product_name", "t_details.qty", "products.price", "sellers.name as seller_name")
                                        ->where("t_details.id_trans", $request->input("id_trans"))
                                        ->leftJoin("products", "products.id_product", "=", "t_details.id_product")
                                        ->leftJoin("sellers", "sellers.id_seller", "=", "t_details.id_seller")
                                        ->orderBy("sellers.id_seller")
                                        ->get();

            return json_encode($data);
        } else if ($mode == "rejectOrder") {
            $transaction = T_head::where("id_trans", $request->input("id_trans"))->first();
            $transaction->status = 0;
            $transaction->save();
        } else if ($mode == "sendOrder") {
            $transaction = T_head::where("id_trans", $request->input("id_trans"))->first();
            $transaction->status = 4;
            $transaction->save();
        }
    } 
}
