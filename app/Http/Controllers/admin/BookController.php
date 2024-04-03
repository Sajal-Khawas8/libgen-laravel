<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\Quantity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use SebastianBergmann\Timer\Duration;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('quantity')->filter(request(['search', 'category']))->simplePaginate(10);
        $categories = Category::lazy();
        return view('pages.admin.books', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::lazy();
        return view("pages.admin.add-book", compact("categories"));
    }

    public function store(Request $req)
    {
        $attributes = $req->validate([
            "title" => ["bail", "required", "regex:/^[a-zA-Z\s\d&()-]*$/", "min:3", "max:50", "unique:books,title"],
            "author" => ["bail", "required", "regex:/^[a-zA-Z\s.]*$/", "min:3", "max:50"],
            "category" => ["bail", "exists:category,id"],
            "copies" => ["bail", "required", "integer"],
            "rent" => ["bail", "required", "decimal:0,2"],
            "fine" => ["bail", "required", "decimal:0,2"],
            "cover" => ["bail", "required", "image"],
            "description" => ["bail", "required", "min:3"]
        ]);

        $bookData = [
            'title' => $attributes['title'],
            'author' => $attributes['author'],
            'description' => $attributes['description'],
            'cover' => $attributes["cover"]->store("books"),
            'category_id' => $attributes['category'],
            'rent' => $attributes['rent'],
            'fine' => $attributes['fine'],
        ];

        $book = Book::create($bookData);

        Quantity::create([
            'book_id' => $book->uuid,
            'copies' => $attributes['copies'],
            'available' => $attributes['copies']
        ]);
        return redirect("/admin/books")->with("success", $attributes['title'] . " has been added.");
    }

    public function edit(Book $book)
    {
        $book->load('quantity');
        $categories = Category::lazy();
        return view("pages.admin.edit-book", compact("book", "categories"));
    }

    public function update(Request $req, Book $book)
    {
        $attributes = $req->validate([
            "title" => ["bail", "nullable", "regex:/^[a-zA-Z\s\d&()-]*$/", "min:3", "max:50", "unique:books,title,$book->id,id"],
            "author" => ["bail", "nullable", "regex:/^[a-zA-Z\s.]*$/", "min:3", "max:50"],
            "category" => ["bail", "exists:category,id"],
            "copies" => ["bail", "nullable", "integer"],
            "rent" => ["bail", "nullable", "decimal:0,2"],
            "fine" => ["bail", "nullable", "decimal:0,2"],
            "cover" => ["bail", "nullable", "image"],
            "description" => ["bail", "nullable", "min:3"]
        ]);

        $bookData = array_filter($attributes, function ($value) {
            return !is_null($value);
        });

        $booksOnRent = $book->quantity->copies - $book->quantity->available;
        if (($bookData['copies'] ?? $book->quantity->copies) < $booksOnRent) {
            throw ValidationException::withMessages([
                "copies" => "Please note: $booksOnRent books have been given on rent"
            ]);
        }

        Quantity::where('book_id', $book->uuid)->update([
            'copies' => $bookData['copies'] ?? $book->quantity->copies,
            'available' => $bookData['copies'] ?? false ? $bookData['copies'] - $booksOnRent : $book->quantity->available
        ]);

        if ($attributes['cover'] ?? false) {
            $bookData['cover'] = $attributes['cover']->store('books');
            Storage::delete($book->cover);
        }

        $book->update($bookData);
        $book->touch();

        return redirect("/admin/books")->with("success", "Book data has been updated.");
    }

    public function destroy(Book $book)
    {
        if ($book->quantity->available !== $book->quantity->copies) {
            return back()->with("error", $book->quantity->copies - $book->quantity->available . " copie(s) of this book are given on rent. So it cannot be deleted at the moment!!");
        }

        $book->delete();
        return redirect("/admin/books")->with("success", "Book has been deleted successfully.");
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
