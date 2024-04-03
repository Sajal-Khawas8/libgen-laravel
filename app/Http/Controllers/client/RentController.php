<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use App\Models\PaidItem;
use App\Models\Payment;
use App\Models\Quantity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RentController extends Controller
{
    private function getRazorpayApi()
    {
        return new Api(env("RAZORPAY_KEY"), env("RAZORPAY_SECRET"));
    }

    private function createOrder($amount)
    {
        try {
            $order = $this->getRazorpayApi()->order->create(
                [
                    'receipt' => str_replace('-', '', uuid_create()),
                    'amount' => $amount * 100,
                    'currency' => 'INR'
                ]
            );
            return $order->id;
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function getPaymentData($paymentId)
    {
        try {
            return $this->getRazorpayApi()->payment->fetch($paymentId);
        } catch (Exception $ex) {
            return redirect('/')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    public function acceptPayment(Request $req)
    {
        // Get payment response
        $paymentResponse = json_decode($req->paymentResponse);

        // Verify the signature
        $generated_signature = hash_hmac('sha256', session()->get('orderId') . '|' . $paymentResponse->razorpay_payment_id, $this->getRazorpayApi()->getSecret());
        $books = collect(session()->get('books'));
        session()->forget(['orderId', 'books']);
        if (!hash_equals($paymentResponse->razorpay_signature, $generated_signature)) {
            return redirect('/')->with('error', 'Something went wrong. Please try again later.');
        }

        // Get payment data using razorpay payment id
        $paymentData = $this->getPaymentData($paymentResponse->razorpay_payment_id);

        // Insert payment data in payments table
        Payment::create([
            "r_payment_id" => $paymentResponse->razorpay_payment_id,
            "method" => $paymentData->method,
            "user_id" => auth()->user()->uuid,
            "amount" => $paymentData->amount / 100,
            "payment_type" => 1,
            "json_response" => json_encode((array) $paymentData)
        ]);


        $books->each(function ($book) use ($paymentResponse) {
            // Extract book id and return date from the book which is created as 'bookUuid/returnDate'
            [$bookId, $returnDate] = explode('/', $book);

            // Add payment and book data to paid_items table
            PaidItem::create([
                'payment_id' => $paymentResponse->razorpay_payment_id,
                'book_id' => $bookId
            ]);

            // Remove the book from the cart of user
            Cart::where('user_id', auth()->user()->uuid)->where('book_id', $bookId)->delete();

            // Insert book data in orders table to list it on 'myBooks'
            Order::create([
                'book_id' => $bookId,
                'user_id' => auth()->user()->uuid,
                'issue_date' => now()->format('Y-m-d'),
                'due_date' => $returnDate
            ]);

            // Decrease the quantity of the book
            Quantity::where('book_id', $bookId)->decrement('available');
        });

        return redirect()->route("myBooks")->with('success', "Payment Successful");
    }

    public function bookPayment(Request $req, Book $book)
    {
        $attributes = $req->validate([
            "returnDate" => ["bail", "required", "after_or_equal:today"]
        ]);
        $amount = $book->rent * (now()->diffInDays($attributes["returnDate"]) + 1);
        $book->rent_payable = $amount;
        $books[] = $book;
        $orderId = $this->createOrder($amount);
        session(['orderId' => $orderId, 'books' => [$book->uuid . '/' . $attributes['returnDate']]]);
        return view("pages.client.checkout-page", ["books" => $books, "total_amount" => $amount, "orderId" => $orderId]);
    }


}
