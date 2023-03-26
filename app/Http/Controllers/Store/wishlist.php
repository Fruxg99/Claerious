<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Wishlist as ModelsWishlist;
use Illuminate\Http\Request;

class wishlist extends Controller
{
    public function load() {
        session_start();

        $wishlist = ModelsWishlist::where("id_user", json_decode($_SESSION["user"])->id_user)->join("products", "products.id_product", "=", "wishlists.id_product")->get();

        $data               = [];
        $data["categories"] = Category::all();
        $data["items"]      = $wishlist;
        $data["search"]     = "";

        return view('Store.wishlist', ["data" => $data]);
    }

    public function addWishlist(Request $request) {
        session_start();

        $wishlist = ModelsWishlist::where("id_product", $request->input("product_id"))->where("id_user", json_decode($_SESSION["user"])->id_user)->first();

        if ($wishlist !== null) {
            $wishlist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Removed from wishlist'
            ], 200);
        } else {
            $wishlist               = new ModelsWishlist();
            $wishlist->id_user      = json_decode($_SESSION["user"])->id_user;
            $wishlist->id_product   = $request->input("product_id");
            $wishlist->save();

            return response()->json([
                'success' => true,
                'message' => 'Added to wishlist'
            ], 200);
        }
    }

    public function wishlistCount() {
        session_start();

        $wishlist = ModelsWishlist::where("id_user", json_decode($_SESSION["user"])->id_user)->count();

        return response()->json([
            'success' => true,
            'message' => json_encode($wishlist)
        ], 200);
    }

    public function checkWishlist(Request $request) {
        session_start();

        $wishlist = ModelsWishlist::where("id_user", json_decode($_SESSION["user"])->id_user)->where("id_product", $request->input("id_product"))->count();

        if ($wishlist) {
            return response()->json([
                'success' => true,
                'message' => "wishlisted"
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => "unwishlisted"
            ], 200);
        }
    }
}