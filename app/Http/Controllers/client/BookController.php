<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with("category")->lazy()->groupBy(fn($book) => $book->category->name);
        return view("pages.client.index", ["books" => $books]);
    }

    public function show(Book $book)
    {
        $book = $book->load(["category", "quantity"]);
        $showAddToCart = !Cart::where("user_id", auth()->user()->uuid)
            ->where("book_id", $book->uuid)
            ->exists() &&
            !Order::where('book_id', $book->uuid)
                ->where('user_id', auth()->user()->uuid)
                ->exists();
        $isRentable = !Order::where('book_id', $book->uuid)
                ->where('user_id', auth()->user()->uuid)
                ->exists();
        return view("pages.client.book-details", compact("book", "showAddToCart", "isRentable"));
    }
}
