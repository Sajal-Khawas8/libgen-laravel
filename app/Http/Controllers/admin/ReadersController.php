<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
}
