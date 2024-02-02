<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\Quantity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SebastianBergmann\Timer\Duration;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('quantity')->simplePaginate(10);
        return view('pages.admin.books', compact('books'));
    }

    public function rentedBooks()
    {
        // $books = Book::with('quantity')
        //     ->whereHas('quantity', function ($query) {
        //         $query->whereColumn('copies', '<>', 'available');
        //     })
        //     ->simplePaginate();
        // $books = Book::with(['orders', 'quantity']);
        $books = Book::has('orders')->with([
            'orders' => function ($query) {
                $query->with('user');
            },
            'quantity'
        ])->simplePaginate();
        $books->each(function ($book) {
            $book->orders->each->setAppends(['duration', 'rent', 'overdueDays', 'fine']);
        });

        // return $books;
        return view('pages.admin.rented-books', compact('books'));
    }
}
