<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        session_start();

        try {

            $google_user = Socialite::driver('google')->stateless()->user();

            $user = User::where('id_google', $google_user->getId())->first();

            $last_user = User::all()->last();
            if (intval(substr($last_user->id_user, 1)) >= 1) {
                $userID = "U" . str_pad((intval(substr($last_user->id_user, 1)) + 1), 4, "0", STR_PAD_LEFT);
            }
            else {
                $userID = "U0001";
            }

            if ($user) {
                $_SESSION["user"] = json_encode($user);
            } else {
                $new_user                   = new User();
                $new_user->id_user          = $userID;
                $new_user->id_google        = $google_user->getId();
                $new_user->profile_picture  = $google_user->getAvatar();
                $new_user->name             = $google_user->getName();
                $new_user->email            = $google_user->getEmail();
                $new_user->password         = "";
                $new_user->phone            = "";
                $new_user->gender           = "2";
                $new_user->saldo            = 0;
                $new_user->status           = 1;
                $new_user->save();

                $user = User::where('id_google', $google_user->getId())->first();
                $_SESSION["user"] = json_encode($user);
            }

            return redirect('');

        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
