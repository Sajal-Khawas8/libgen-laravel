<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        return view("pages.client.login");
    }

    public function store(Request $req)
    {
        $credentials=$req->validate([
            "email"=> ["bail", "required", "email", "exists:users,email"],
            "password"=>["required"]
        ]);

        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                "email"=> "Your Email Address and Password do not match",
                "password"=> "Your Email Address and Password do not match"
            ]);
        }

        return redirect()->intended();
    }

    public function destroy()
    {
        auth()->logout();
        return redirect("/");
    }
}
