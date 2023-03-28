<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Category;
use App\Models\Group;
use App\Models\Group_members;
use App\Models\Product;
use App\Models\Seller;
use App\Models\T_detail;
use App\Models\T_head;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Null_;
use Psr\Http\Message\RequestInterface;
use stdClass;

class main extends Controller
{
    public function load() {
        $data                   = [];
        $data["categories"]     = Category::all();
        $data["items"]          = Product::all();
        $data["recent_items"]   = Product::orderByDesc("created_at")->limit(9)->get();
        $data["search"]         = "";
        $data["top_rated"]      = Product::orderByDesc("rating")->orderByDesc("rating_count")->limit(9)->get();
        $data["top_review"]     = Product::orderByDesc("rating_count")->orderBy("rating")->limit(9)->get();

        return view('Store.home', ["data" => $data]);
    }

    public function loadRegisterStore() {
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

        $data               = [];
        $data["provinces"]  = json_decode($provinces)->rajaongkir->results;

        return view('Store.register-store', ["data" => $data]);
    }

    public function getAddress() {

    }

    public function getCity(Request $request) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=" . $request->input("province"),
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

        $cities = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }

        return json_decode($cities)->rajaongkir->results;
    }

    public function getUser() {
        session_start();

        $data = [];
        $data["user"] = User::where("id_user", json_decode($_SESSION["user"])->id_user)->first();

        return json_encode($data);
    }

    public function setPassword(Request $request) {
        session_start();
        
        $user = User::where("id_user", json_decode($_SESSION["user"])->id_user)->first();
        $user->password = $request->input("password");
        $user->save();
    } 

    public function login(Request $request) {
        session_start();

        $email      = $request->input('email');
        $password   = $request->input('password');

        $user = User::all()->where("email", $email)->where("password", $password)->first();

        if ($user !== null) {
            $_SESSION["user"] = json_encode($user);

            return response()->json([
                'success' => true,
                'message' => 'Login Success!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!'
            ], 200);
        }
    }

    public function register(Request $request) {
        $email              = $request->input('email');
        $password           = $request->input('password');
        $name               = $request->input('name');
        $phone              = $request->input('phone') ? $request->input('phone') : "";
        $gender             = $request->input('gender');

        $user = User::all()->last();
        if ($user) {
            if (intval(substr($user->id_user, 1)) >= 1) {
                $userID = "U" . str_pad((intval(substr($user->id_user, 1)) + 1), 4, "0", STR_PAD_LEFT);
            }
            else {
                $userID = "U0001";
            }
        } else {
            $userID = "U0001";
        }

        $checkEmail = User::where("email", $email)->first();

        $validated = $request->validate([
            'email'                     => 'required|email:rfc,dns',
            'password'                  => 'required|alpha_num|min:8',
            'confirm_password'          => 'required|same:password',
            'name'                      => 'required',
            'phone'                     => 'nullable|numeric'
        ], [
            'email.required'            => 'Email tidak boleh kosong!',
            'email.email'               => 'Email tidak valid!',
            'password.required'         => 'Kata sandi tidak boleh kosong!',
            'password.alpha_num'        => 'Kata sandi hanya boleh huruf dan angka!',
            'password.min'              => 'Kata sandi minimal 8 karakter!',
            'confirm_password.required' => 'Kata sandi tidak sama!',
            'name.required'             => 'Nama tidak boleh kosong!',
            'phone.numeric'             => 'Nomor telepon hanya boleh angka!'
        ]);

        if ($validated) {
            if (!$checkEmail) {
                $user                   = new User();
                $user->id_user          = $userID;
                $user->id_google        = "";
                $user->profile_picture  = "";
                $user->name             = $name;
                $user->email            = $email;
                $user->password         = $password;
                $user->phone            = $phone;
                $user->gender           = $gender;
                $user->saldo            = 0;
                $user->status           = 1;
                $user->save();

                return redirect('/register')->with('success', 'Berhasil mendaftarkan akun. Silahkan kembali ke halaman awal untuk login');
            } else {
                return redirect('/register')->with('warning', 'Email sudah digunakan. Silahkan daftar dengan email lain.');
            }
        }
    }

    public function registerStore(Request $request) {
        session_start();

        $seller = Seller::all()->last();
        if ($seller) {
            if (intval(substr($seller->id_seller, 1)) >= 1) {
                $sellerID = "S" . str_pad((intval(substr($seller->id_seller, 1)) + 1), 4, "0", STR_PAD_LEFT);
            }
            else {
                $sellerID = "S0001";
            }
        } else {
            $sellerID = "S0001";
        }

        $validated = $request->validate([
            'name'                      => 'required',
            'address'                   => 'required',
            'province'                  => 'required',
            'city'                      => 'required',
            'phone'                     => 'nullable|numeric'
        ], [
            'name.required'             => 'Nama tidak boleh kosong!',
            'address.required'          => 'Alamat tidak boleh kosong!',
            'province.required'         => 'Provinsi tidak boleh kosong!',
            'city.required'             => 'Kota tidak boleh kosong!',
            'phone.numeric'             => 'Nomor telepon hanya boleh angka!'
        ]);

        if ($validated) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/city?id=" . $request->input("city"),
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

            $city = curl_exec($curl);

            curl_close($curl);

            $seller                     = new Seller();
            $seller->id_seller          = $sellerID;
            $seller->id_user            = json_decode($_SESSION["user"])->id_user;
            $seller->profile_picture    = $request->input('profile') ? $request->input('profile') : "";
            $seller->name               = $request->input('name');
            $seller->address            = $request->input('address');
            $seller->city               = $request->input('city');
            $seller->city_name          = json_decode($city)->rajaongkir->results->city_name;
            $seller->province           = $request->input('province');
            $seller->postal_code        = $request->input('postal_code');
            $seller->phone              = $request->input('phone') ? $request->input('phone') : "";
            $seller->saldo              = 0;
            $seller->status             = 1;
            $seller->save();

            return redirect('/profile')->with('success', 'Berhasil membuka toko.');
        }
    }

    public function logout() {
        session_start();
        session_destroy();

        return redirect('');
    }

    public function loadProfile() {
        session_start();

        $user = User::where("id_user", json_decode($_SESSION["user"])->id_user)->first();
        $store = Seller::where("id_user", json_decode($_SESSION["user"])->id_user)->first();

        if (!$store) {
            $store = "";
        }

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

        $data               = [];
        $data["provinces"]  = json_decode($provinces)->rajaongkir->results;
        $data["store"]      = $store;
        $data["user"]       = $user;

        return view('Store.profile', ["data" => $data]);
    }

    public function addressCRUD(Request $request, $mode) {
        session_start();

        if ($mode == "select") {
            $address = Address::where("id_user", json_decode($_SESSION["user"])->id_user)->get();

            return json_encode($address);
        } else if ($mode == "insert") {
            $address = Address::all()->last();
            if ($address) {
                if (intval(substr($address->id_address, 1)) >= 1) {
                    $addressID = "A" . str_pad((intval(substr($address->id_address, 1)) + 1), 4, "0", STR_PAD_LEFT);
                }
                else {
                    $addressID = "A0001";
                }
            } else {
                $addressID = "A0001";
            }

            $validated = $request->validate([
                'shipReceiver'              => 'required',
                'shipPhone'                 => 'required|numeric',
                'shipName'                  => 'required',
                'shipAddress'               => 'required',
                'shipCityId'                => 'required|numeric',
                'shipCity'                  => 'required',
                'shipPostal'                => 'required|numeric'
            ], [
                'shipReceiver.required'     => 'Nama penerima tidak boleh kosong!',
                'shipPhone.required'        => 'No telepon penerima tidak boleh kosong!',
                'shipName.required'         => 'Label alamat tidak boleh kosong!',
                'shipAddress.required'      => 'Alamat tidak boleh kosong!',
                'shipCityId.required'       => 'Kota tidak boleh kosong!',
                'shipCity.required'         => 'Kota tidak boleh kosong!',
                'shipPostal.required'       => 'Kode pos tidak boleh kosong!',
                'shipPhone.numeric'         => 'Nomor telepon hanya boleh angka!',
                'shipPostal.numeric'        => 'Kode pos hanya boleh angka!'
            ]);
    
            if ($validated) {
                $newAddress                     = new Address();
                $newAddress->id_address         = $addressID;
                $newAddress->id_user            = json_decode($_SESSION["user"])->id_user;
                $newAddress->receiver_name      = $request->input("shipReceiver");
                $newAddress->receiver_phone     = $request->input("shipPhone");
                $newAddress->label              = $request->input("shipName");
                $newAddress->address            = $request->input("shipAddress");
                $newAddress->id_city            = $request->input("shipCityId");
                $newAddress->city_name          = $request->input("shipCity");
                $newAddress->postal_code        = $request->input("shipPostal");
                $newAddress->save();
            }
        } else if ($mode == "update") {

        } else if ($mode == "delete") {
            $delete = Address::where('id_address', $request->input('id_address'))->first();
            $delete->delete();
        } else if ($mode == "selectById") {
            $address = Address::where('id_address', $request->input('id_address'))->first();
            $address->delete();

            return json_encode($address);
        }
    }

    public function voucherCRUD(Request $request, $mode) {
        if ($mode == "selectBySeller") {
            $vouchers = Voucher::where("id_seller", $request->input("id_seller"))->get();

            return $vouchers;
        } else if ($mode == "selectById") {
            $voucher = Voucher::where("id_voucher", $request->input("id_voucher"))->first();
            
            return $voucher;
        } else if ($mode == "update") {

        } else if ($mode == "delete") {
            
        }
    }

    public function shipmentCRUD(Request $request, $mode) {
        if ($mode == "selectBySeller") {
            $seller = Seller::where("id_seller", $request->input("id_seller"))->first();
            $address = Address::where("id_address", $request->input("id_address"))->first();

            $courier        = [];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "origin=" . $seller->city . "&destination=" . $address->id_city . "&weight=" . $request->input("weight") . "&courier=jne",
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: 5752982e47b4c3890af7becf4181bffa"
                ),
            ));

            $shipment = curl_exec($curl);

            curl_close($curl);

            // Create php object
            $new_courier            = new stdClass();
            $new_courier->courier   = "JNE";
            $new_courier->details   = json_decode($shipment)->rajaongkir->results;

            array_push($courier, $new_courier);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "origin=" . $seller->city . "&destination=" . $address->id_city . "&weight=" . $request->input("weight") . "&courier=pos",
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: 5752982e47b4c3890af7becf4181bffa"
                ),
            ));

            $shipment = curl_exec($curl);

            curl_close($curl);

            // Create php object
            $new_courier            = new stdClass();
            $new_courier->courier   = "POS Indonesia";
            $new_courier->details   = json_decode($shipment)->rajaongkir->results;

            array_push($courier, $new_courier);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "origin=" . $seller->city . "&destination=" . $address->id_city . "&weight=" . $request->input("weight") . "&courier=tiki",
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: 5752982e47b4c3890af7becf4181bffa"
                ),
            ));

            $shipment = curl_exec($curl);

            curl_close($curl);

            // Create php object
            $new_courier            = new stdClass();
            $new_courier->courier   = "TIKI";
            $new_courier->details   = json_decode($shipment)->rajaongkir->results;

            array_push($courier, $new_courier);

            $data = [];
            $data["address"]        = $address;
            $data["seller"]         = $seller->city;
            $data["destination"]    = $address->id_city;
            $data["weight"]         = $request->input("weight");

            return $courier;
        } else if ($mode == "selectById") {
            $voucher = Voucher::where("id_voucher", $request->input("id_voucher"))->first();
            
            return $voucher;
        } else if ($mode == "update") {

        } else if ($mode == "delete") {
            
        }
    }

    public function groupCRUD(Request $request, $mode) {
        session_start();

        if ($mode == "get") {
            $groups = Group_members::where("id_user", json_decode($_SESSION["user"])->id_user)
                        ->join("groups", "groups.id_gruop", "=", "group_members.id_group")
                        ->get();

            return $groups;
        } else if ($mode == "selectById") {
            $voucher = Voucher::where("id_voucher", $request->input("id_voucher"))->first();
            
            return $voucher;
        } else if ($mode == "update") {

        } else if ($mode == "delete") {
            
        }
    }

    public function transactionCRUD(Request $request, $mode) {
        session_start();

        if ($mode == "get") {
            $data = [];
            $data["transaction"] = T_head::select("t_heads.id_trans", "addresses.receiver_name", "addresses.address", "t_heads.created_at", "t_heads.total", "t_heads.status")
                                    ->where("t_heads.id_user", json_decode($_SESSION["user"])->id_user)
                                    ->whereMonth("t_heads.created_at", $request->input("month"))
                                    ->whereYear("t_heads.created_at", $request->input("year"))
                                    ->leftJoin("addresses", "addresses.id_address", "=", "t_heads.id_address")
                                    ->get();

            return json_encode($data);
        } else if ($mode == "selectById") {
            $transaction = T_head::where("id_trans", $request->input("id_trans"))->first();
            
            return json_encode($transaction);
        } else if ($mode == "update") {

        } else if ($mode == "delete") {
            
        } else if ($mode == "getDetails") {
            $data = [];
            $data["transaction"]    = T_head::where("t_heads.id_user", json_decode($_SESSION["user"])->id_user)
                                        ->where("t_heads.id_trans", $request->input("id_trans"))
                                        ->leftJoin("addresses", "addresses.id_address", "=", "t_heads.id_address")
                                        ->get();
            $data["items"]          = T_detail::select("products.thumbnail", "products.name as product_name", "t_details.qty", "products.price", "sellers.name as seller_name")
                                        ->where("t_details.id_trans", $request->input("id_trans"))
                                        ->leftJoin("products", "products.id_product", "=", "t_details.id_product")
                                        ->leftJoin("sellers", "sellers.id_seller", "=", "t_details.id_seller")
                                        ->orderBy("sellers.id_seller")
                                        ->get();
            // $data["items"]          = T_detail::where("t_details.id_trans", $request->input("id_trans"))->get();

            return json_encode($data);
        }
    }
}
