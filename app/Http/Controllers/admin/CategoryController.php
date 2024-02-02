<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories=Category::with("books")->simplePaginate(9);
        // return $categories;
        return view("pages.admin.categories", compact("categories"));
    }
}
