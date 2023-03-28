<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Group_price;
use App\Models\Product;
use App\Models\Product_thumbnail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class item extends Controller
{
    public function load() {
        $data = [];
        $data["categories"] = Category::all();

        return view('Backoffice.product', ["data" => $data]);
    }

    public function crud(Request $request, $mode) {
        session_start();

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
        } else if ($mode == "getPrices") {
            $prices = Group_price::where("id_product", $request->input("id_product"))->get();

            $data = [];
            $data["prices"] = $prices;

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
            $data->id_product       = $productID;
            $data->id_category      = $request->input("id_category");
            $data->id_seller        = json_decode($_SESSION["seller"])->id_seller;
            $data->name             = $request->input('name');
            $data->description      = $request->input('description');
            $data->thumbnail        = $request->input('thumbnail');
            $data->stock            = intval($request->input('stock'));
            $data->price            = intval($request->input('price'));
            $data->weight           = intval($request->input('weight'));
            $data->rating           = 0;
            $data->rating_count     = 0;
            $data->status           = 1;
            $data->save();

            $groupPrices = json_decode($request->input("groupPrice")[0]);

            for($i = 0 ; $i < sizeof($groupPrices) ; $i++) {
                $newPrice = new Group_price();
                $newPrice->id_product           = $productID;
                $newPrice->target_accumulation  = $groupPrices[$i]->minPurchase;
                $newPrice->price                = $groupPrices[$i]->price;
                $newPrice->save();
            }

            if ($request->file("file")) {
                for($i = 0 ; $i < sizeof($request->file("file")) ; $i++) {
                    // Get filename with extension
                    $filenamewithextension = $request->file("file")[$i]->getClientOriginalName();

                    // Get filename without extension
                    $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                    // Get file extension
                    $extemsion = $request->file("file")[$i]->getClientOriginalExtension();
                    
                    // Filename to store
                    $filenametostore = $productID . "-" . ($i + 1) . $extemsion;

                    // Upload file to S3 Bucket
                    Storage::disk('s3')->put($filenametostore, fopen($request->file("file")[$i], 'r+'), 'public');

                    // Insert Product Image to DB
                    $newImage = new Product_thumbnail();
                    $newImage->id_product   = $productID;
                    $newImage->thumbnail    = Storage::disk('s3')->url($filenametostore);   // Get image S3 URL
                    $newImage->save();
                }
            }

            return $productID;
        } else if ($mode == "update") {
            // dd($request);
            $update = Product::where('id_product', $request->input('id_product'))->first();
            $update->id_category        = $request->input("id_category");
            $update->description        = $request->input('description');
            $update->thumbnail          = $request->input('thumbnail');
            $update->stock              = intval($request->input('stock'));
            $update->price              = intval($request->input('price'));
            $update->weight             = intval($request->input('weight'));
            $update->save();

            $oldPrices = Group_price::where('id_product', $request->input('id_product'))->delete();
            $oldImages = Product_thumbnail::where('id_product', $request->input('id_product'))->delete();
            $groupPrices = json_decode($request->input("groupPrice")[0]);
            // dd($groupPrices);
            for($i = 0 ; $i < sizeof($groupPrices) ; $i++) {
                $newPrice = new Group_price();
                $newPrice->id_product           = $request->input('id_product');
                $newPrice->target_accumulation  = $groupPrices[$i]->minPurchase;
                $newPrice->price                = $groupPrices[$i]->price;
                $newPrice->save();
            }

            if ($request->file("file")) {
                for($i = 0 ; $i < sizeof($request->file("file")) ; $i++) {
                    // Get filename with extension
                    $filenamewithextension = $request->file("file")[$i]->getClientOriginalName();
    
                    // Get filename without extension
                    $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
    
                    // Get file extension
                    $extemsion = $request->file("file")[$i]->getClientOriginalExtension();
                    
                    // Filename to store
                    $filenametostore = $request->input('id_product') . "-" . ($i + 1) . $extemsion;
    
                    // Upload file to S3 Bucket
                    Storage::disk('s3')->put($filenametostore, fopen($request->file("file")[$i], 'r+'), 'public');
    
                    // Insert Product Image to DB
                    $newImage = new Product_thumbnail();
                    $newImage->id_product   = $request->input('id_product');
                    $newImage->thumbnail    = Storage::disk('s3')->url($filenametostore);   // Get image S3 URL
                    $newImage->save();
                }
            }
        } else if ($mode == "delete") {
            $delete = Product::where('id_product', $request->input('id_product'))->first();
            $delete->delete();
        }
    }
}
