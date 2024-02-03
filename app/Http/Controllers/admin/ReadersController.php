<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReadersController extends Controller
{
    public function index()
    {
        $users = User::where("role", 1)->with(["orders"=> function ($query){
            $query->with("book");
        }])->simplePaginate(6);
        $users->each(function ($user) {
            $user->orders->each->setAppends(['duration', 'rent', 'overdueDays', 'fine']);
        });
        // return $users;
        return view("pages.admin.readers", compact("users"));
    }

    public function destroy(Request $req)
    {
        $validator=Validator::make($req->all(), [
            "id"=>["bail", "required", "uuid", "exists:users,uuid"]
        ]);
        $user=User::find($req->id);
        if ($validator->fails() || $user->role !== 1) {
            return redirect()->back()->with("error", "Something went wrong! Please try again");
        }
        $user->delete();
        return redirect("/admin/readers")->with("success","User has been blocked!");
    }
}
