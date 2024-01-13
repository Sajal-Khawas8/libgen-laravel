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
        return view("client.index", ["books" => $books]);
    }
}
