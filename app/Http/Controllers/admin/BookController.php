<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\Quantity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SebastianBergmann\Timer\Duration;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('quantity')->filter(request(['search', 'category']))->simplePaginate(10);
        $categories=Category::lazy();
        return view('pages.admin.books', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::lazy();
        return view("pages.admin.add-book", compact("categories"));
    }

    public function store(Request $req)
    {
        $attributes=$req->validate([
            "title" => ["bail", "required" ,"regex:/^[a-zA-Z\s\d&()-]*$/", "min:3", "max:50", "unique:books,title"],
            "author" => ["bail", "required" ,"regex:/^[a-zA-Z\s.]*$/", "min:3", "max:50"],
            "category" => ["bail", "required", "exists:category,id"],
            "copies" => ["bail", "required", "integer"],
            "rent" => ["bail", "required", "decimal:0,2"],
            "fine" => ["bail", "required", "decimal:0,2"],
            "cover" => ["bail", "required", "image"],
            "description" => ["bail", "required", "min:3"]
        ]);

        $bookData=[
            'title' => $attributes['title'],
            'author' => $attributes['author'],
            'description' => $attributes['description'],
            'cover' => $attributes["cover"]->store("books"),
            'category_id' => $attributes['category'],
            'rent' => $attributes['rent'],
            'fine' => $attributes['fine'],
        ];

        $book=Book::create($bookData);

        Quantity::create([
            'book' => $book->title,
            'copies' => $attributes['copies'],
            'available' => $attributes['copies']
        ]);
        return redirect("/admin/books")->with("success", $attributes['title'] . " has been added.");
    }

    public function rentedBooks()
    {
        $books = Book::has('orders')->with([
            'orders' => function ($query) {
                $query->with('user');
            },
            'quantity'
        ])->simplePaginate();
        $books->each(function ($book) {
            $book->orders->each->setAppends(['duration', 'rent', 'overdueDays', 'fine']);
        });

        return view('pages.admin.rented-books', compact('books'));
    }
}
