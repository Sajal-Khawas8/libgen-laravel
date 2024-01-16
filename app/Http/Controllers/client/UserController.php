<?php

namespace App\Http\Controllers\client;

use App\Models\User;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        return view("pages.client.register");
    }

    public function store(Request $req)
    {
        $attributes = $req->validate([
            "name" => ["bail", "required", "min:3", "max:50", "regex:/^[a-zA-Z\s]*$/"],
            "email" => ["bail", "required", "email", "unique:users,email"],
            "profilePicture" => [File::image()],
            "address" => ["required"],
            "password" => ["bail", "required", Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()->rules(["max:16"]), "confirmed"]
        ]);
        $userData = [
            "name" => $attributes["name"],
            "email" => $attributes["email"],
            "password" => $attributes["password"],
            "address" => $attributes["address"],
        ];
        if ($req->hasFile("profilePicture")) {
            $userData["image"]=basename($attributes["profilePicture"]->store("users", "uploads"));
        }
        $user = User::create($userData);
        auth()->login($user);
        return redirect()->intended();
    }
}
