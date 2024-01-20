<?php

namespace App\Http\Controllers\client;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show()
    {
        return view("pages.client.settings");
    }

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
            $userData["image"] = $attributes["profilePicture"]->store("users");
        }
        $user = User::create($userData);
        Auth::login($user, true);
        return redirect()->intended();
    }

    public function edit()
    {
        return view("pages.client.edit-user");
    }

    public function update(Request $req)
    {
        $user = auth()->user();
        $attributes = $req->validate([
            "name" => ["bail", "nullable", "min:3", "max:50", "regex:/^[a-zA-Z\s]*$/"],
            "email" => ["bail", "nullable", "email", Rule::unique(User::class)->ignore($user)],
            "profilePicture" => [File::image()],
            "address" => ["nullable"],
            "current_password" => ["bail", "required", "current_password"],
            "password" => ["bail", "required", Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()->rules(["max:16"])]
        ]);
        $userData = [
            "name" => $attributes["name"] ?? $user->name,
            "email" => $attributes["email"] ?? $user->email,
            "address" => $attributes["address"] ?? $user->address,
            "password" => $attributes["password"],
        ];
        if ($req->hasFile("profilePicture")) {
            $userData["image"] = $attributes["profilePicture"]->store("users");
            Storage::delete($user->image);
        }

        $user->update($userData);
        return redirect("/settings");
    }

    public function destroy(Request $req)
    {
        $userUuid = Auth::user()->uuid;
        
        // Logout the user
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();

        // Delete the user account
        User::destroy($userUuid);

        // Redirect the user
        return redirect("/");
    }
}
