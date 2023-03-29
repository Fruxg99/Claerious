<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Chat as ModelsChat;
use App\Models\Group_chat;
use Illuminate\Http\Request;

class chat extends Controller
{
    public function load() {
        session_start();

        $data = [];
        $data["chats"] = ModelsChat::where("id_sender", json_decode($_SESSION["user"])->id_user)
                            ->orWhere("id_receiver", json_decode($_SESSION["user"])->id_user)
                            ->get();
        $data["chats"] = Group_chat::where("id_sender", json_decode($_SESSION["user"])->id_user)
                            ->orWhere("id_receiver", json_decode($_SESSION["user"])->id_user)
                            ->get();

        return view('Store.cart', ["data" => $data]);
    }
}
