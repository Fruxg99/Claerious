<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product as ModelsProduct;
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

    public function detail($product_name) {
        $data               = [];
        $data["categories"] = Category::all();
        $data["item"]       = ModelsProduct::where("products.name", urldecode($product_name))->first();
        $data["search"]     = "";
        $data["store"]      = ModelsProduct::where("products.name", urldecode($product_name))->join("sellers", "products.id_seller", "=", "sellers.id_seller")->first();

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
