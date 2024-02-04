<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\PaidItem;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Quantity;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $data = cache()->rememberForever("dashboard", function () {
            $data = [
                'books' => Book::all()->count(),
                'categories' => Category::all()->count(),
                'quantity' => Quantity::all()->sum("copies"),
                'orders' => Order::all()->count(),
                'users' => User::with('roles')->get()->countBy(function ($user) {
                    return $user->roles->name;
                }),
                'transactions' => Payment::all()->count(),
                'income' => Payment::all()->groupBy(function ($payment) {
                    return $payment->paymentType->payment_type;
                })->map(function ($group) {
                    return number_format($group->sum('amount'), 2);
                }),
            ];
            $data['income']['total'] = $data['income']->sum();
            $data = json_encode($data);
            return $data;
        });

        return view("pages.admin.index", ['data' => json_decode($data)]);
    }
}
