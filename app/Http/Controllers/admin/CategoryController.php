<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with("books")->simplePaginate(9);
        // return $categories;
        return view("pages.admin.categories", compact("categories"));
    }

    public function create()
    {
        return view("pages.admin.add-category");
    }

    public function store(Request $req)
    {
        $attributes = $req->validate([
            "category" => ["bail", "required", "min:3", "max:50", "regex:/^[a-zA-Z\s]*$/", "unique:category,name"]
        ]);
        $category = new Category();
        $category->name= $attributes["category"];
        $category->save();
        return redirect("/admin/categories")->with("success", $attributes['category'] . " category has been added.");
    }

    public function edit(Category $category){
        return view("pages.admin.edit-category", compact("category"));
    }

    public function update(Request $req, Category $category)
    {
        $attributes = $req->validate([
            "category" => ["bail", "nullable", "min:3", "max:50", "regex:/^[a-zA-Z\s]*$/", Rule::unique(Category::class, "name")->ignore($category)],
        ]);
        $category->name= $attributes["category"] ?? $category->name;
        $category->save();
        return redirect("/admin/categories")->with("success", "Category Updated Successfully");
    }

    public function destroy(Request $req)
    {
        $validator=Validator::make($req->all(), [
            "id"=>["required", "exists:category,name"]
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with("error", "Something went wrong! Please try again");
        } elseif ($booksCount=Category::where("name", $req->id)->withCount("books")->first()->books_count) {
            return redirect()->back()->with("error", "This category has " . $booksCount . " books. So it can't be deleted at this moment.");
        }
        Category::where("name", $req->id)->delete();
        return redirect("/admin/categories")->with("success","Category Deleted Successfully");
    }
}
