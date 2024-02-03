<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where("role", "<>", 1)->orderBy("id")->simplePaginate(4);
        return view("pages.admin.admins", compact("admins"));
    }

    public function show()
    {
        return view("pages.admin.settings");
    }

    public function edit()
    {
        return view("pages.admin.edit-user");
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
        return redirect("/admin/settings")->with("success", "Your data has been updated Successfully!");
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
        return redirect("/")->with("success", "Your account has been deleted Successfully! Sorry to see you go!!");
    }

    public function makeSuperAdmin(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "id" => ["bail", "required", "uuid", "exists:users,uuid", "not_in:" . auth()->user()->uuid]
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with("error", "Something went wrong! Please try again");
        }
        $user = User::find($req->id);
        $user->role = 3;
        $user->save();
        return redirect()->back()->with("success", $user->name . " is now a Super Admin");
    }

    public function removeSuperAdmin(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "id" => ["bail", "required", "uuid", "exists:users,uuid", "not_in:" . auth()->user()->uuid]
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with("error", "Something went wrong! Please try again");
        }
        $user = User::find($req->id);
        $user->role = 2;
        $user->save();
        return redirect()->back()->with("success", $user->name . " is not a Super Admin anymore");
    }

    public function removeAdmin(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "id" => ["bail", "required", "uuid", "exists:users,uuid", "not_in:" . auth()->user()->uuid]
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with("error", "Something went wrong! Please try again");
        }

        User::destroy($req->id);
        return redirect("/admin/team")->with("success", "Admin has been removed");
    }

    public function create()
    {
        return view("pages.admin.add-admin");
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
        $user->role = 2;
        $user->save();
        return redirect("/admin/team")->with("success", "New User has been registered Successfully!");
    }
}
