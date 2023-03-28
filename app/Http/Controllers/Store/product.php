<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Category;
use App\Models\Group;
use App\Models\Group_price;
use App\Models\Product as ModelsProduct;
use App\Models\Product_thumbnail;
use App\Models\Seller;
use App\Models\T_detail;
use App\Models\T_head;
use Illuminate\Http\Request;

class product extends Controller
{
    public function load(Request $request) {
        if ($request->input("search") !== null && $request->input("search") != "") {
            $data               = [];
            $data["categories"] = Category::all();
            $data["items"]      = ModelsProduct::where('name', 'like', '%' . $request->input('search') . '%')->get();
            $data["search"]     = $request->input("search");
    
            return view('Store.product', ["data" => $data]);
        } else {
            $data               = [];
            $data["categories"] = Category::all();
            $data["items"]      = ModelsProduct::all();
            $data["search"]     = "";
    
            return view('Store.product', ["data" => $data]);
        }
    }

    public function loadGroupPayment(Request $request) {
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
        $data["address"]        = Address::where("id_user", json_decode($_SESSION["user"])->id_user)->get();
        $data["categories"]     = Category::all();
        $data["cart"]           = ModelsProduct::select("products.id_product","products.thumbnail", "products.name", "products.price", "products.weight", "sellers.id_seller", "sellers.name as seller_name", "sellers.profile_picture")
                                    ->where("products.id_product", $request->input("productID"))
                                    ->join("sellers", "sellers.id_seller", "=", "products.id_seller")->orderBy("sellers.id_seller")
                                    ->first();
        $data["price"]          = Group_price::where("id", $request->input("groupPriceSelect"))->first()->price;
        $data["group_price"]    = $request->input("groupPriceSelect");
        $data["qty"]            = str_replace('.', '', $request->input("groupQtyPurchase"));
        $data["leader"]         = $request->input("leaderID");
        $data["groupID"]        = $request->input("groupID");
        $data["provinces"]      = json_decode($provinces)->rajaongkir->results;

        return view('Store.group-payment', ["data" => $data]);
    }

    public function loadJoinGroupPayment(Request $request) {
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
        $data["address"]        = Address::where("id_user", json_decode($_SESSION["user"])->id_user)->get();
        $data["categories"]     = Category::all();
        $data["cart"]           = ModelsProduct::select("products.id_product","products.thumbnail", "products.name", "products.price", "products.weight", "sellers.id_seller", "sellers.name as seller_name", "sellers.profile_picture")
                                    ->where("products.id_product", $request->input("productID"))
                                    ->join("sellers", "sellers.id_seller", "=", "products.id_seller")->orderBy("sellers.id_seller")
                                    ->first();
        $data["price"]          = Group_price::where("id", $request->input("groupPriceID"))->first()->price;
        $data["group_price"]    = $request->input("groupPriceID");
        $data["qty"]            = str_replace('.', '', $request->input("joinQtyPurchase"));
        $data["leader"]         = $request->input("leaderID");
        $data["groupID"]        = $request->input("groupID");
        $data["provinces"]      = json_decode($provinces)->rajaongkir->results;

        return view('Store.group-payment', ["data" => $data]);
    }

    public function detail($product_name) {
        $productID = ModelsProduct::where("products.name", urldecode($product_name))->first()->id_product;

        $data                   = [];
        $data["categories"]     = Category::all();
        $data["item"]           = ModelsProduct::where("products.name", urldecode($product_name))->first();
        $data["search"]         = "";
        $data["store"]          = ModelsProduct::where("products.name", urldecode($product_name))->join("sellers", "products.id_seller", "=", "sellers.id_seller")->first();
        $data["product_images"] = Product_thumbnail::where("id_product", $productID)->get();
        $data["prices"]         = Group_price::where("id_product", $productID)->get();
        $data["groups"]         = Group::where("groups.id_product", $productID)
                                    ->join("users", "users.id_user", "=", "groups.id_leader")
                                    ->join("group_prices", "group_prices.id", "=", "groups.id_group_price")
                                    ->get();

        // dd($data["groups"]);

        return view('Store.detail', ["data" => $data]);
    }

    public function filter(Request $request) {
        $categoryID = $request->input("id_category");
        $priceMin = $request->input("min_price");
        $priceMax = $request->input("max_price");
        $stockMin = $request->input("min_stock");

        $category = Category::where("id_category", $categoryID)->first();

        $products = ModelsProduct::where("price", ">=", $priceMin)
                                ->when($priceMax > 0, function ($query) use ($priceMax) {
                                    $query->where("price", "<=", $priceMax);
                                })
                                ->where("stock", ">=", $stockMin)
                                ->when($categoryID, function ($query) use ($categoryID) {
                                    $query->where("id_category", $categoryID);
                                })
                                ->get();

        return response()->json([
            'success'   => true,
            'message'   => $products,
            'category'  => $category,
            'minPrice'  => $request->input("min_price"),
            'maxPrice'  => $request->input("max_price"),
            'minStock'  => $request->input("min_stock")
        ], 200);
    }

    public function getGroup(Request $request) {
        $data = [];
        $data["group"] = Group::where("groups.id_group", $request->input("id_group"))
                            ->join("users", "users.id_user", "=", "groups.id_leader")
                            ->join("group_prices", "group_prices.id", "=", "groups.id_group_price")
                            ->get();

        return json_encode($data);
    }

    public function getPrices(Request $request) {
        $data = [];
        $data["prices"] = Group_price::where("id_product", $request->input("product_id"))->get();

        return json_encode($data);
    }

    public function loadSeller($sellerName, $sellerID) {
        $data = [];
        $data["categories"]     = Category::all();
        $data["items"]          = ModelsProduct::where('id_seller', $sellerID)->get();
        $data["search"]         = "";
        $data["seller"]         = Seller::where("id_seller", $sellerID)->first();
        $data["transaction"]    = T_detail::where("id_seller", $sellerID)
                                    ->join("t_heads", "t_heads.id_trans", "=", "t_details.id_trans")
                                    ->where("t_heads.status", ">", 3)
                                    ->count();

        return view('Store.seller', ["data" => $data]);
    }

    public function sellerFilter(Request $request) {
        $sellerID = $request->input("id_seller");
        $categoryID = $request->input("id_category");
        $priceMin = $request->input("min_price");
        $priceMax = $request->input("max_price");
        $stockMin = $request->input("min_stock");

        $category = Category::where("id_category", $categoryID)->first();

        $products = ModelsProduct::where("price", ">=", $priceMin)
                                ->when($priceMax > 0, function ($query) use ($priceMax) {
                                    $query->where("price", "<=", $priceMax);
                                })
                                ->where("stock", ">=", $stockMin)
                                ->when($categoryID, function ($query) use ($categoryID) {
                                    $query->where("id_category", $categoryID);
                                })
                                ->where("id_seller", $sellerID)
                                ->get();

        return response()->json([
            'success'   => true,
            'message'   => $products,
            'category'  => $category,
            'minPrice'  => $request->input("min_price"),
            'maxPrice'  => $request->input("max_price"),
            'minStock'  => $request->input("min_stock")
        ], 200);
    }
}
