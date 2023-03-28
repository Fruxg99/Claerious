<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Store\cart as StoreCart;
use App\Models\Cart;
use App\Models\Group;
use App\Models\Group_members;
use App\Models\Group_price;
use App\Models\Product;
use App\Models\T_detail;
use App\Models\T_head;
use Illuminate\Http\Request;
use stdClass;

class checkout extends Controller
{
    public function checkout(Request $request) {
        session_start();

        $transactions = T_head::all()->last();
        if ($transactions) {
            if (intval(substr($transactions->id_trans, 1)) >= 1) {
                $transID = "T" . str_pad((intval(substr($transactions->id_trans, 1)) + 1), 4, "0", STR_PAD_LEFT);
            }
            else {
                $transID = "T0001";
            }
        }
        else {
            $transID = "T0001";
        }

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to false
        \Midtrans\Config::$is3ds = false;

        $params = array(
            'transaction_details' => array(
                'order_id' => $transID,
                'gross_amount' => $request->input('total'),
            ),
            'customer_details' => array(
                'name' => json_decode($_SESSION["user"])->name,
                'email' => json_decode($_SESSION["user"])->email,
                'phone' => json_decode($_SESSION["user"])->phone,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $cart = Cart::where("id_user", json_decode($_SESSION["user"])->id_user)->join("products", "products.id_product", "=", "carts.id_product")->orderBy("id_seller")->get();

        // Insert Product to Trans Head
        $data_head = new T_head();
        $data_head->id_trans = $transID;
        $data_head->id_user = json_decode($_SESSION["user"])->id_user;
        $data_head->id_group = "";
        $data_head->coupon = $request->input('coupon');
        $data_head->shipping_cost = $request->input('shipping');
        $data_head->total = $request->input('total');
        $data_head->snap_token = $snapToken;
        $data_head->expired = Date('y:m:d', strtotime('+1 days'));
        $data_head->status = 1; // Unpaid
        $data_head->save();

        for($i = 0 ; $i < sizeof($cart) ; $i++) {
            // Insert Each Product to Trans Detail
            $data_detail = new T_detail();
            $data_detail->id_trans = $transID;
            $data_detail->id_seller = $cart[$i]->id_seller;
            $data_detail->id_product = $cart[$i]->id_product;
            $data_detail->qty = $cart[$i]->qty;
            $data_detail->total = intval($cart[$i]->qty) * intval($cart[$i]->price);
            $data_detail->save();
        }
        
        $data_return = new stdClass();
        $data_return->trans_id = $transID;
        $data_return->snap_token = $snapToken;

        Cart::where("id_user", json_decode($_SESSION["user"])->id_user)->delete();

        return $data_return;
    }

    public function checkoutGroup(Request $request) {
        session_start();

        $transactions = T_head::all()->last();
        if ($transactions) {
            if (intval(substr($transactions->id_trans, 1)) >= 1) {
                $transID = "T" . str_pad((intval(substr($transactions->id_trans, 1)) + 1), 4, "0", STR_PAD_LEFT);
            }
            else {
                $transID = "T0001";
            }
        }
        else {
            $transID = "T0001";
        }

        $groupPrice = Group_price::where("id", $request->input("id_group_price"))->first();
        $groupID = Group::where("id_group", $request->input("id_group"))->first();

        if ($groupID) {
            $groupData = Group::where("id_group", $request->input("id_group"))->first();
            $groupData->current_accumulation    = $groupData->current_accumulation + $request->input("qty");
            $groupData->save();
        } else {
            // Create new group
            $groups = Group::all()->last();
            if ($groups) {
                if (intval(substr($groups->id_group, 1)) >= 1) {
                    $groupID = "G" . str_pad((intval(substr($groups->id_group, 1)) + 1), 4, "0", STR_PAD_LEFT);
                }
                else {
                    $groupID = "G0001";
                }
            }
            else {
                $groupID = "G0001";
            }

            $groupData = new Group();
            $groupData->id_group                = $groupID;
            $groupData->id_group_price          = $request->input("id_group_price");
            $groupData->id_product              = $request->input("id_product");
            $groupData->id_leader               = $request->input("id_leader");
            $groupData->current_accumulation    = $request->input("qty");
            $groupData->target_accumulation     = $groupPrice->target_accumulation;
            $groupData->status                  = 0;
            $groupData->save();
        }

        $groupMember = new Group_members();
        $groupMember->id_group          = $groupID;
        $groupMember->id_user           = json_decode($_SESSION["user"])->id_user;
        $groupMember->total_purchase    = $request->input("qty");
        $groupMember->save();

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to false
        \Midtrans\Config::$is3ds = false;

        $params = array(
            'transaction_details' => array(
                'order_id' => $transID,
                'gross_amount' => $request->input('total'),
            ),
            'customer_details' => array(
                'name' => json_decode($_SESSION["user"])->name,
                'email' => json_decode($_SESSION["user"])->email,
                'phone' => json_decode($_SESSION["user"])->phone,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Insert Product to Trans Head
        $data_head = new T_head();
        $data_head->id_trans = $transID;
        $data_head->id_user = json_decode($_SESSION["user"])->id_user;
        $data_head->id_group = $groupID;
        $data_head->coupon = $request->input('coupon');
        $data_head->shipping_cost = $request->input('shipping');
        $data_head->total = $request->input('total');
        $data_head->snap_token = $snapToken;
        $data_head->expired = Date('y:m:d', strtotime('+1 days'));
        $data_head->status = 1; // Unpaid
        $data_head->save();

        // Insert Each Product to Trans Detail
        $data_detail = new T_detail();
        $data_detail->id_trans = $transID;
        $data_detail->id_seller = $request->input("id_seller");
        $data_detail->id_product = $request->input("id_product");
        $data_detail->qty = $request->input("qty");
        $data_detail->total = intval($request->input("qty")) * intval($groupPrice->price);
        $data_detail->save();
        
        $data_return = new stdClass();
        $data_return->trans_id = $transID;
        $data_return->snap_token = $snapToken;

        // return $cart[1]->id_seller;
        return $data_return;
    }

    public function paymentSuccess(Request $request) {
        $transID = $request->input('id_trans');

        $transaction            = T_head::where("id_trans", $transID)->first();
        $transaction->status    = 2; // Status Paid
        $transaction->save();
    }

    public function paymentFailed(Request $request) {
        $transID = $request->input('id_trans');

        // Delete transaction head
        $transaction            = T_head::where("id_trans", $transID)->first();

        $groupID = $transaction->id_group;
        $group = Group::where("id_group", $groupID)->first();
        if ($group) {
            $group->delete();
        }

        $transaction->delete();

        // Delete transaction detail
        T_detail::where("id_trans", $transID)->delete();
    }
}