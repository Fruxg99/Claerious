<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Models\T_detail;
use App\Models\T_head;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class mainBackoffice extends Controller
{
    public function load() {
        return view('Backoffice.login');
    }

    public function loadCategory() {
        return view('Backoffice.category');
    }

    public function login(Request $request) {
        session_start();

        $email      = $request->input('email');
        $password   = $request->input('password');

        $user = User::where("email", $email)->where("password", $password)->join("sellers", "users.id_user", "=", "sellers.id_user")->first();

        if ($user !== null) {
            $_SESSION["seller"] = json_encode($user);

            return response()->json([
                'success' => true,
                'message' => 'Login Success!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!'
            ], 404);
        }
    }

    public function logout() {
        session_start();
        session_destroy();

        return redirect('');
    }

    public function crudCategory(Request $request, $mode) {
        if ($mode == "select") {
            $categories = Category::all();

            $data = [];
            $data["data"] = $categories;

            return json_encode($data);
        } else if ($mode == "selectByID") {
            $category = Category::where("id_category", $request->input("id_category"))->first();

            $data = [];
            $data["data"] = $category;

            return json_encode($data);
        } else if ($mode == "insert") {
            // Generate Category ID
            $category = Category::all()->last();
            if ($category) {
                if (intval(substr($category->id_category, 1)) >= 1) {
                    $categoryID = "C" . str_pad((intval(substr($category->id_category, 1)) + 1), 4, "0", STR_PAD_LEFT);
                }
                else {
                    $categoryID = "C0001";
                }
            }
            else {
                $categoryID = "C0001";
            }

            // Insert Category to DB
            $data = new Category;
            $data->id_category  = $categoryID;
            $data->name         = $request->input('name');
            $data->thumbnail    = $request->input('thumbnail');
            $data->save();

            return $categoryID;
        } else if ($mode == "update") {
            // Update Category to DB
            $update = Category::where('id_category', $request->input('id_category'))->first();
            $update->name       = $request->input('name');
            $update->thumbnail  = $request->input('thumbnail');
            $update->save();
        } else if ($mode == "delete") {
            // Delete Category from DB
            $delete = Category::where('id_category', $request->input('id_category'))->first();
            $delete->delete();
        }
    }

    public function loadSalesReport() {
        return view('Backoffice.report.sales');
    }
    public function salesReportCRUD(Request $request, $mode) {
        session_start();

        if ($mode == "select") {
            $data = [];
            $data["data"] = Product::select("products.id_product", "products.thumbnail", "products.name", DB::raw("sum(t_details.qty) as total"))
                            ->where("products.id_seller", json_decode($_SESSION["seller"])->id_seller)
                            ->whereDate("t_details.created_at", ">=", $request->input("dateStart"))
                            ->whereDate("t_details.created_at", "<=", $request->input("dateEnd"))
                            ->leftJoin("t_details", "t_details.id_product", "=", "products.id_product")
                            ->groupBy("products.id_product", "products.thumbnail", "products.name")
                            ->get();

            return json_encode($data);
        }
    }

    public function loadStockReport() {
        return view('Backoffice.report.stock');
    }
    public function stockReportCRUD(Request $request, $mode) {
        session_start();

        if ($mode == "select") {
            $data = [];
            $data["data"] = Product::select("products.id_product", "products.thumbnail", "products.name", "products.stock", DB::raw("sum(t_details.qty) as total"))
                            ->where("products.id_seller", json_decode($_SESSION["seller"])->id_seller)
                            ->whereDate("t_details.created_at", ">=", $request->input("dateStart"))
                            ->whereDate("t_details.created_at", "<=", $request->input("dateEnd"))
                            ->leftJoin("t_details", "t_details.id_product", "=", "products.id_product")
                            ->groupBy("products.id_product", "products.thumbnail", "products.name", "products.stock")
                            ->get();

            return json_encode($data);
        }
    }

    public function loadIncomeReport() {
        return view('Backoffice.report.income');
    }
    public function incomeReportCRUD($mode) {
        session_start();

        if ($mode == "select") {
            $jan = sizeof(T_head::whereMonth("created_at", "=", "01")
                    ->whereYear("created_at", "=", 2023)->get()) * 5000;
            $feb = sizeof(T_head::whereMonth("created_at", "=", "02")
                    ->whereYear("created_at", "=", 2023)->get()) * 5000;
            $mar = sizeof(T_head::whereMonth("created_at", "=", "03")
                    ->whereYear("created_at", "=", 2023)->get()) * 5000;
            $apr = sizeof(T_head::whereMonth("created_at", "=", "04")
                    ->whereYear("created_at", "=", 2023)->get()) * 5000;
            $may = sizeof(T_head::whereMonth("created_at", "=", "05")
                    ->whereYear("created_at", "=", 2023)->get()) * 5000;
            $jun = sizeof(T_head::whereMonth("created_at", "=", "06")
                    ->whereYear("created_at", "=", 2023)->get()) * 5000;
            $jul = sizeof(T_head::whereMonth("created_at", "=", "07")
                    ->whereYear("created_at", "=", 2023)->get()) * 5000;
            $aug = sizeof(T_head::whereMonth("created_at", "=", "08")
                    ->whereYear("created_at", "=", 2023)->get()) * 5000;
            $sep = sizeof(T_head::whereMonth("created_at", "=", "09")
                    ->whereYear("created_at", "=", 2023)->get()) * 5000;
            $oct = sizeof(T_head::whereMonth("created_at", "=", "10")
                    ->whereYear("created_at", "=", 2023)->get()) * 5000;
            $nov = sizeof(T_head::whereMonth("created_at", "=", "11")
                    ->whereYear("created_at", "=", 2023)->get()) * 5000;
            $dec = sizeof(T_head::whereMonth("created_at", "=", "12")
                    ->whereYear("created_at", "=", 2023)->get()) * 5000;
            $data = [$jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct, $nov, $dec];

            return json_encode($data);
        }
    }

    public function loadSellerReport() {
        return view('Backoffice.report.seller');
    }
    public function sellerReportCRUD(Request $request, $mode) {
        session_start();

        if ($mode == "select") {
            $data = [];
            $data["data"] = Seller::select("sellers.id_seller", "sellers.profile_picture", "sellers.name", DB::raw("count(t_details.id_seller) as total"))
                            ->leftJoin("t_details", "t_details.id_seller", "=", "sellers.id_seller")
                            ->leftJoin("t_heads", "t_details.id_trans", "=", "t_heads.id_trans")
                            ->whereDate("t_details.created_at", ">=", $request->input("dateStart"))
                            ->whereDate("t_details.created_at", "<=", $request->input("dateEnd"))
                            ->groupBy("sellers.id_seller", "sellers.profile_picture", "sellers.name")
                            ->get();

            return json_encode($data);
        }
    }

    public function loadVoucherReport() {
        return view('Backoffice.report.voucher');
    }
    public function voucherReportCRUD(Request $request, $mode) {
        session_start();

        if ($mode == "select") {
            $data = [];
            $data["data"] = Voucher::where("id_seller", json_decode($_SESSION["seller"])->id_seller)->get();

            return json_encode($data);
        }
    }

    public function loadUserReport() {
        return view('Backoffice.report.user');
    }
    public function userReportCRUD(Request $request, $mode) {
        session_start();

        if ($mode == "select") {
            $data = [];
            $data["data"] = Product::select("products.id_product", "products.thumbnail", "products.name", "products.stock", DB::raw("sum(t_details.qty) as total"))
                            ->where("products.id_seller", json_decode($_SESSION["seller"])->id_seller)
                            ->whereDate("t_details.created_at", ">=", $request->input("dateStart"))
                            ->whereDate("t_details.created_at", "<=", $request->input("dateEnd"))
                            ->leftJoin("t_details", "t_details.id_product", "=", "products.id_product")
                            ->groupBy("products.id_product", "products.thumbnail", "products.name", "products.stock")
                            ->get();

            return json_encode($data);
        }
    }
}
