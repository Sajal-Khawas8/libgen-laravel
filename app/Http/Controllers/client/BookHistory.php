<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\RentHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BookHistory extends Controller
{
    public function index()
    {
        $currentReads = Order::where("user_id", auth()->user()->uuid)->with("book")->get();
        $previousReads = RentHistory::where("user_id", auth()->user()->uuid)->with("book")->get();
        // return $currentReads;
        return view("pages.client.my-books", compact("currentReads", "previousReads"));
    }

    public function returnBook(Book $book)
    {
        try {
            $rentData = Order::where("user_id", auth()->user()->uuid)
                ->where("book_id", $book->uuid)->firstOrFail()->setAppends(['duration', 'rent', 'overdueDays', 'fine']);
            // return $rentData;
            // return $book;
            return view("pages.client.return-book", compact("book", "rentData"));
        } catch (ModelNotFoundException $ex) {
            abort(400, "No Record Found");
        }
    }

    public function rentHistory(Book $book)
    {
        try {
            $rentData = RentHistory::where("user_id", auth()->user()->uuid)
                ->where("book_id", $book->uuid)->firstOrFail()->setAppends(['duration', 'overdueDays']);
            return view("pages.client.book-history", compact("book", "rentData"));
        } catch (ModelNotFoundException $ex) {
            abort(400, "No Record Found");
        }
    }
}
