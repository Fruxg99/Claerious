<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Voucher as ModelsVoucher;
use Illuminate\Http\Request;

class voucher extends Controller
{
    public function load() {
        return view('Backoffice.voucher');
    }

    public function crud(Request $request, $mode) {
        if ($mode == "select") {
            $sellerID = $request->input('id_seller');
            $vouchers = ModelsVoucher::where('id_seller', $sellerID)->get();

            $data = [];
            $data["data"] = $vouchers;

            return json_encode($data);
        } else if ($mode == "selectByID") {
            $voucher = ModelsVoucher::where("id_voucher", $request->input("id_voucher"))->first();

            $data = [];
            $data["data"] = $voucher;

            return json_encode($data);
        } else if ($mode == "insert") {
            // Generate Voucher ID
            $voucher = ModelsVoucher::all()->last();
            if ($voucher) {
                if (intval(substr($voucher->id_voucher, 1)) >= 1) {
                    $voucherID = "V" . str_pad((intval(substr($voucher->id_voucher, 1)) + 1), 4, "0", STR_PAD_LEFT);
                }
                else {
                    $voucherID = "V0001";
                }
            } else {
                $voucherID = "V0001";
            }

            // Insert Voucher to DB
            $data                       = new ModelsVoucher;
            $data->id_voucher           = $voucherID;
            $data->id_seller            = $request->input('id_seller');
            $data->name                 = $request->input('name');
            $data->type                 = intval($request->input('type'));
            $data->min_purchase         = intval($request->input('min_purchase'));
            $data->max_discount         = intval($request->input('max_discount'));
            $data->discount_percentage  = intval($request->input('discount_percentage'));
            $data->usage_count          = 0;
            $data->usage_limit          = intval($request->input('usage_limit'));
            $data->effective_date       = $request->input('effective_date');
            $data->due_date             = $request->input('due_date');
            $data->save();

            return $voucherID;
        } else if ($mode == "update") {
            $update                         = ModelsVoucher::where('id_voucher', $request->input('id_voucher'))->first();
            $update->type                   = intval($request->input('type'));
            $update->min_purchase           = intval($request->input('min_purchase'));
            $update->max_discount           = intval($request->input('max_discount'));
            $update->discount_percentage    = intval($request->input('discount_percentage'));
            $update->usage_limit            = intval($request->input('usage_limit'));
            $update->effective_date         = $request->input('effective_date');
            $update->due_date               = $request->input('due_date');
            $update->save();

        } else if ($mode == "delete") {
            $delete = ModelsVoucher::where('id_voucher', $request->input('id_voucher'))->first();
            $delete->delete();
        }
    }
}
