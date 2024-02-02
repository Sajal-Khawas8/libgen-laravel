<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $admins=User::where("role", "<>", 1)->simplePaginate(4);
        return view("pages.admin.admins", compact("admins"));
    }
}
