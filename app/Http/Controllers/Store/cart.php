<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart as ModelsCart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class cart extends Controller
{
    public function load() {
        session_start();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 5752982e47b4c3890af7becf4181bffa"
            ),
        ));

        $provinces = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }

        $data = [];
        $data["address"]    = Address::where("id_user", json_decode($_SESSION["user"])->id_user)->get();
        $data["categories"] = Category::all();
        $data["cart"]       = ModelsCart::select("carts.id_product","products.thumbnail", "products.name", "products.price", "products.weight", "carts.qty", "sellers.id_seller", "sellers.name as seller_name", "sellers.profile_picture")
                            ->where("carts.id_user", json_decode($_SESSION["user"])->id_user)
                            ->join("products", "products.id_product", "=", "carts.id_product")
                            ->join("sellers", "sellers.id_seller", "=", "products.id_seller")->orderBy("sellers.id_seller")
                            ->get();
        $data["provinces"]  = json_decode($provinces)->rajaongkir->results;

        return view('Store.cart', ["data" => $data]);
    }

    public function getCart() {
        session_start();

        $data["cart"] = ModelsCart::where("id_user", json_decode($_SESSION["user"])->id_user)->join("products", "products.id_product", "=", "carts.id_product")->orderBy("id_seller")->get();
        
        return response()->json([
            'success'   => true,
            'message'   => json_encode($data)
        ], 200);
    }

    public function addToCart(Request $request) {
        session_start();

        $cart = ModelsCart::where("id_product", $request->input("product_id"))->where("id_user", json_decode($_SESSION["user"])->id_user)->first();

        if ($cart !== null) {
            $cart->qty          = $cart->qty + $request->input("qty");
            $cart->save();
        } else {
            $cart               = new ModelsCart();
            $cart->id_user      = json_decode($_SESSION["user"])->id_user;
            $cart->id_product   = $request->input("product_id");
            $cart->qty          = $request->input("qty");
            $cart->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Added to cart'
        ], 200);
    }

    public function updateCartQty(Request $request) {
        session_start();

        $cart           = ModelsCart::where("id_product", $request->input("product_id"))->where("id_user", json_decode($_SESSION["user"])->id_user)->first();
        $cart->qty      = $request->input("qty");
        $cart->save();

        $product        = Product::where("id_product", $request->input("product_id"))->first();

        return response()->json([
            'success' => true,
            'message' => $product->weight
        ], 200);
    }

    public function removeCartItem(Request $request) {
        session_start();

        $cart = ModelsCart::where("id_product", $request->input("product_id"))->where("id_user", json_decode($_SESSION["user"])->id_user)->first();
        $cart->delete();

        $seller = Product::where("id_product", $request->input("product_id"))->first();
        $id_seller = $seller->id_seller;

        return response()->json([
            'success'   => true,
            'message'   => $id_seller
        ], 200);
    }

    public function cartCount() {
        session_start();

        $cart = ModelsCart::where("id_user", json_decode($_SESSION["user"])->id_user)->sum("qty");

        return response()->json([
            'success'   => true,
            'message'   => json_encode($cart)
        ], 200);
    }
}
