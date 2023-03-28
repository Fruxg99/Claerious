<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Favorite as ModelsFavorite;
use Illuminate\Http\Request;

class favorite extends Controller
{
    public function load() {
        session_start();

        $favorite = ModelsFavorite::where("id_user", json_decode($_SESSION["user"])->id_user)
                                    ->join("products", "products.id_product", "=", "favorites.id_product")
                                    ->get();

        $data               = []; 
        $data["categories"] = Category::all();
        $data["items"]      = $favorite;
        $data["search"]     = "";

        return view('Store.favorite', ["data" => $data]);
    }

    public function addFavorite(Request $request) {
        session_start();

        $favorite = ModelsFavorite::where("id_product", $request->input("product_id"))->where("id_user", json_decode($_SESSION["user"])->id_user)->first();

        if ($favorite !== null) {
            $favorite->delete();

            return response()->json([
                'success' => true,
                'message' => 'Removed from favorite'
            ], 200);
        } else {
            $favorite               = new ModelsFavorite();
            $favorite->id_user      = json_decode($_SESSION["user"])->id_user;
            $favorite->id_product   = $request->input("product_id");
            $favorite->save();

            return response()->json([
                'success' => true,
                'message' => 'Added to favorite'
            ], 200);
        }
    }

    public function favoriteCount(Request $request) {
        session_start();

        $favorite = ModelsFavorite::where("id_user", json_decode($_SESSION["user"])->id_user)->count();

        return response()->json([
            'success' => true,
            'message' => json_encode($favorite)
        ], 200);
    }

    public function checkFavorite(Request $request) {
        session_start();

        $favorite = ModelsFavorite::where("id_user", json_decode($_SESSION["user"])->id_user)->where("id_product", $request->input("id_product"))->count();

        if ($favorite) {
            return response()->json([
                'success' => true,
                'message' => "favorited"
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => "unfavorited"
            ], 200);
        }
    }
}