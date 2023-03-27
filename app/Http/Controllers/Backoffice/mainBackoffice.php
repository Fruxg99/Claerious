<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

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
}
