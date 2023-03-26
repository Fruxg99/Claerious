<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class item extends Controller
{
    public function load() {
        return view('Backoffice.product');
    }

    public function crud(Request $request, $mode) {
        if ($mode == "select") {
            $sellerID = $request->input('id_seller');
            $products = Product::where('id_seller', $sellerID)->get();

            $data = [];
            $data["data"] = $products;

            return json_encode($data);
        } else if ($mode == "selectByID") {
            $products = Product::where("id_product", $request->input("id_product"))->first();

            $data = [];
            $data["data"] = $products;

            return json_encode($data);
        } else if ($mode == "insert") {
            // Generate Product ID
            $products = Product::all()->last();
            if ($products) {
                if (intval(substr($products->id_product, 1)) >= 1) {
                    $productID = "P" . str_pad((intval(substr($products->id_product, 1)) + 1), 4, "0", STR_PAD_LEFT);
                }
                else {
                    $productID = "P0001";
                }
            }
            else {
                $productID = "P0001";
            }

            // Insert Product to DB
            $data = new Product;
            $data->id_product = $productID;
            $data->id_seller = "S0001";
            $data->name = $request->input('name');
            $data->description = $request->input('description');
            $data->thumbnail = $request->input('thumbnail');
            $data->stock = intval($request->input('stock'));
            $data->price = intval($request->input('price'));
            $data->weight = intval($request->input('weight'));
            $data->status = 1;
            $data->save();

            return $productID;
        } else if ($mode == "update") {
            $update = Product::where('id_product', $request->input('id_product'))->first();
            $update->description = $request->input('description');
            $update->thumbnail = $request->input('thumbnail');
            $update->stock = intval($request->input('stock'));
            $update->price = intval($request->input('price'));
            $update->weight = intval($request->input('weight'));
            $update->save();
        } else if ($mode == "delete") {
            $delete = Product::where('id_product', $request->input('id_product'))->first();
            $delete->delete();
        }
    }
}
