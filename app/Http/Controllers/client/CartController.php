<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where("user_id", auth()->user()->uuid)->with("book.quantity")->lazy();
        // return $cartItems;
        return view("pages.client.cart", compact("cartItems"));
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "book" => ["bail", "required", "uuid", "exists:books,uuid"]
        ]);

        if (
            $validator->fails() ||
            Cart::where("user_id", auth()->user()->uuid)
                ->where("book_id", $req->book)
                ->exists() ||
            Order::where("user_id", auth()->user()->uuid)
                ->where("book_id", $req->book)
                ->exists()
        ) {
            return back()->with("error", "Something went wrong! Please try again.");
        }

        Cart::create([
            "user_id" => auth()->user()->uuid,
            "book_id" => $req->book
        ]);

        return redirect()->route("cart.index")->with("success", "Book added to the cart.");
    }

    public function destroy(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "book" => ["bail", "required", "uuid", "exists:books,uuid"]
        ]);

        if (
            $validator->fails() ||
            !Cart::where("user_id", auth()->user()->uuid)
                ->where("book_id", $req->book)
                ->exists()
        ) {
            return back()->with("error", "Something went wrong! Please try again.");
        }

        Cart::where("user_id", auth()->user()->uuid)
            ->where("book_id", $req->book)
            ->delete();

        return back()->with("success", "Book has been removed from the cart.");
    }
}
