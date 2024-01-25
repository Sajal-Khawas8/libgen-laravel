<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        return view("pages.client.login");
    }

    public function store(Request $req)
    {
        $credentials = $req->validate([
            "email" => ["bail", "required", "email", "exists:users,email"],
            "password" => ["required"]
        ]);
        $credentials["active"] = 1;

        if (!Auth::attempt($credentials, true)) {
            throw ValidationException::withMessages([
                "email" => "Your Email Address and Password couldn't be verified",
                "password" => "Your Email Address and Password couldn't be verified"
            ]);
        }
        $req->session()->regenerate();
        return redirect()->intended(auth()->user()->role === 1 ? '/' : '/admin')->with("success","Welcome back, " . auth()->user()->name ."!");
    }

    public function destroy(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect('/')->with("success", "Logged Out Successfully!");
    }
}
