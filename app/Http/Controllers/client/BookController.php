<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with("category")->lazy()->groupBy(fn($book) => $book->category->name);
        // return $books;
        return view("pages.client.index", ["books" => $books]);
    }

    public function show(Book $book)
    {
        session(["url.intended" => url()->current()]);
        $book = $book->load(["category", "quantity"]);
        return view("pages.client.book-details", ["book" => $book]);
    }
}
